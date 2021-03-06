<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubjectMatter;
use App\Group;
use App\Block;
use App\BlockGroup;
use App\Management;
use App\ScheduleRecord;
use App\BlockSchedule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blocks = Block::getAllBlocks();
        $data = [
            'blocks' => $blocks
        ];
        return view('components.contents.blocks.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $block_groups = BlockGroup::join('blocks', 'block_group.block_id', '=', 'blocks.id')
                                    ->join('managements', 'blocks.management_id', '=', 'managements.id')
                                    ->select('block_group.*', 'managements.id as management_id')
                                    ->get();
        $subjectMatters = SubjectMatter::getAllSubjectMatters();
        $managements = Management::getAllManagements()->reverse();
        $groupsID = BlockGroup::getAllBlockGroupsId();
        $registered_groups = Group::join('block_group', 'groups.id', '=', 'block_group.group_id')
                        ->join('blocks', 'block_group.block_id', '=', 'blocks.id')
                        ->join('managements', 'blocks.management_id', '=', 'managements.id')
                        ->select('groups.*', 'managements.id as management_id')
                        ->orderby('name')
                        ->get();
        $groups = [];
        for($i=0 ; $i<sizeof($registered_groups) ; $i++ ) {
            array_push( $groups, $registered_groups[$i] );
        }
        $unregistered_groups = Group::all();
        $registered_id_groups = [];
        for($i=0 ; $i<sizeof($groups) ; $i++) {
            array_push($registered_id_groups, $groups[$i]->id);
        }
        for($i=0 ; $i<sizeof($unregistered_groups) ; $i++) {
            $unregistered_groups[$i]->management_id = null;
            if( !in_array( $unregistered_groups[$i]->id, $registered_id_groups ) ) {
                array_push( $groups, $unregistered_groups[$i] );
            }
        }
        $data = [
            'block_groups' => $block_groups,
            'subjectMatters'=> $subjectMatters,
            'groups'=> $groups,
            'managements' => $managements
        ];
        return view('components.contents.blocks.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input =$request->all();
        $block = new Block();
        $man = Management::find($request->management_id);
        $name = 'Bloque-';
        $id_name = [];
        if($block->validate($input)){   
            $man = Management::find($request->management_id);
            $dir = $man->management_path.'/'.$request->name;
            $block->management_id = $request->management_id;
            $block->name = $name;
            $groupsID = $request->groups_id;
            $block->save();
            foreach($groupsID as $key=>$value){
                $group = Group::where('id', $value)->first();
                $block->groups()->attach($group->id);
                array_push( $id_name, $group->professor->names[0] );
            }
            $id_name = array_unique( $id_name );
            sort($id_name);
            for($i=0 ; $i<sizeof($id_name) ; $i++) {
                $name .= $id_name[$i];
            }
            $dir = $man->management_path.'/'.$name;
            $block->block_path = $dir;
            $block->name = $name;
            $block->save();
            Storage::makeDirectory($dir);
            return redirect('/admin/blocks');
        }
        else{
            return redirect('/admin/blocks/create')->withInput()->withErrors($block->errors);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $block = Block::findOrFail($id);
        $subjectMatters = SubjectMatter::getAllSubjectMatters();
        $managements = Management::getAllManagements()->reverse();
        $groupsID = BlockGroup::getAllBlockGroupsId();
        $groups = Group::where('subject_matter_id', $block->groups->first()->subject->id)
                        ->whereNotIn('id', $groupsID)                        
                        ->orderBy('name')->get();
        $data=['subjectMatters'=>$subjectMatters,
                'groups'=>$groups->merge($block->groups),
                'managements' =>$managements,
                'block' => $block
            ];
        return view('components.contents.blocks.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input =$request->all();
        $block = Block::findOrFail($id);
        $name = 'Bloque';
        if($block->validate($input)){   
            // Pedir ayuda a Abel
            //$man = Management::find($request->management_id);
            //$dir = $man->management_path.'/'.$request->name;
            $block->management_id = $request->management_id;
            $block->name = $name;
            $groupsID = $request->groups_id;
            $block->groups()->detach($block->groups->pluck('id')->toArray());
            foreach($groupsID as $key=>$value){
                $group = Group::where('id', $value)->first();
                $block->groups()->attach($group->id);
                $name .= '-'.$group->professor->first_name[0];
                //$name .= '-'.$block->id;
            }

            //$dir = $man->management_path.'/'.$name;
            //$block->block_path = $dir;
            $block->name = $name;
            $block->save();
            //Storage::makeDirectory($dir);
            return redirect('/admin/blocks');
        }
        else{
            return back()->withInput()->withErrors($block->errors);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function getGroups(Request $request, $id){
        $actual_management = Management::getActualManagement();
        $groups = Group::getGroupsBySubjects($id);
        $groupsBlocks = BlockGroup::getAllBlockGroupsId();
        $groups2 = $groups->reject(function($item, $key) use ($groupsBlocks){
            if (in_array($item->id, $groupsBlocks) && !$item->available)
                return true;
        });
        if($request->ajax()){
            return response()->json($groups2->values()->all());
        }
        return $groups2->values()->all();
    }

    public function getBlocksBySubjects(Request $request, $id){
        $blocks = Block::getAllBlocks();
        $blocks2 = $blocks->reject(function($item, $key) use ($id){
            if($item->groups->first()->subject_matter_id != $id)
                return true; 
            });
            if($request->ajax()){
                return response()->json($blocks2->values()->all());
            }
            return $blocks2->values()->all();       
    }
    public function getGroupsByBlocks(Request $request, $id){
        $block = Block::findOrFail($id);
        if($request->ajax()){
            return response()->json($block->groups);
        }
        return $block->groups;
    }

    public function getGroupSchedules($group_id, $block_id){
        $block_schedules = BlockSchedule::join('blocks', 'block_schedules.block_id', '=', 'blocks.id')
                                        ->join('block_group', 'blocks.id', '=', 'block_group.block_id')
                                        ->join('groups', 'block_group.group_id', '=', 'groups.id')
                                        ->join('schedule_records', 'block_schedules.schedule_id', '=','schedule_records.id')
                                        ->join('laboratories', 'schedule_records.laboratory_id', '=', 'laboratories.id')
                                        ->join('days', 'schedule_records.day_id', '=', 'days.id')
                                        ->join('hours', 'schedule_records.hour_id', '=', 'hours.id')
                                        ->where('blocks.id', '=', $block_id)
                                        ->where('groups.id', '=', $group_id)
                                        ->select('block_schedules.*', 'groups.id as group_id', 'groups.name as group_name')
                                        ->get();
        return $block_schedules;
    }

    public function setStatus($id, $value){
        $block = Block::findOrFail($id);
        $block->available = $value;
        $block->save();
        return response(["response" => $block]);
    }
}
