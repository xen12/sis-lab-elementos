<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sesion;
use App\Task;
use App\Block;
use App\BlockGroup;
use App\Professor;
use App\Management;
use App\StudentSchedule;
use App\StudentTask;
use App\BlockSchedule;
use App\Group;
use Illuminate\Support\Facades\Auth;

class SesionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !Auth::user() ) { return redirect('/'); }
        $professor = Professor::join('users', 'professors.user_id', '=', 'users.id')
                              ->where('users.id', '=', Auth::user()->id)
                              ->select('users.*', 'professors.id as professor_id')
                              ->get()
                              ->first();
        $groups = Group::join('block_group', 'groups.id', '=', 'block_group.group_id')
                       ->join('blocks', 'block_group.block_id', '=', 'blocks.id')
                       ->join('managements', 'blocks.management_id', '=', 'managements.id')
                       ->select('groups.*', 'block_group.id as block_group_id', 'blocks.id as block_id', 'blocks.name as block_name','managements.id as management_id')
                       ->where('groups.professor_id', '=', $professor->professor_id)
                       ->get();
        $data = [
            'managements' => Management::all()->reverse(),
            'groups' => $groups
        ];
        return view('components.contents.professor.sesions', $data);
    }

    public function practicesInfo(){
        $id_groups = array();
        $block_groups = BlockGroup::all();
        for($i=0 ; $i<sizeof($block_groups) ; $i++) {
            array_push($id_groups, $block_groups[$i]->group_id);
        }
        $quantity_sesion_tasks = [];
        foreach(Sesion::all() as $sesion) {
            $quantity_sesion_tasks[$sesion->id] = 0;
        }
        foreach(Sesion::all() as $sesion) {
            foreach(Task::all() as $task) {
                if($task->sesion->id == $sesion->id) {
                    $quantity_sesion_tasks[$sesion->id]++;
                }
            }
        }
        $student_tasks = StudentTask::all();
        $tasks_by_sesion = [];
        foreach(Sesion::all() as $sesion) {
            $tasks_by_sesion[$sesion->id] = 0;
        }
        foreach(Sesion::all() as $sesion) {
            foreach ($student_tasks as $student_task) {
                if($student_task->task->sesion->id == $sesion->id){
                    $tasks_by_sesion[$sesion->id]++;
                }
            }
        }
        $data = [
            'sesions' => Sesion::all(),
            'id_groups' => $id_groups,
            'quantity_sesion_tasks' => $quantity_sesion_tasks,
            'tasks_by_sesion' => $tasks_by_sesion,
            'block_groups' => $block_groups
        ];
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $sesion = new Sesion();
        if($sesion->validate($input)){
            $start = $request->date_start;
            $end = $request->date_end;
            $sesions = Sesion::autodate($start,$end);
            $numberOfSesions = count($sesions);

            $existentSesions = Sesion::where('block_id','=',$request->block_id)->get();
            $indexSession=count($existentSesions);
            $index=1;
            if(($indexSession < $numberOfSesions) && $indexSession!=0){
               $endDate = $existentSesions[$indexSession-1]->date_end;
               $index = $existentSesions[$indexSession-1]->number_sesion;
               $index ++;
               $sesions = Sesion::autodate($endDate,$end);
            }else if($indexSession == $numberOfSesions){
                return back()->withInput()->withErrors(['errors'=>['Ya tiene sesiones generadas automáticamente']]);
            }else if($numberOfSesions < $indexSession){
                return back()->withInput()->withErrors(['errors'=>['Ya tiene sesiones generadas automáticamente']]);
            }
            foreach ($sesions as $value) {
                Sesion::create([
                    'number_sesion' => $index,
                    'block_id' => $request->block_id,
                    'date_start'=> $value['start'],
                    'date_end'=> $value['end'],
                ]);
                $index++;
            }
            return back();
        }else{
            return back()->withInput()->withErrors($sesion->errors);
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
        //
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
        //
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

    public function showStudentSesions($id)
    {
        $schedule = StudentSchedule::findOrFail($id);
        $sesions = Sesion::where('block_id', $schedule->getGroupAttribute()->blocks->first()->id)->get();
        $tasks = Task::all()->reject(function($item, $key) use ($schedule){
            if($item->getSesionAttribute()->block_id != $schedule->group->blocks->first()->id){
                return true;
            }
        })->values()->all();
        $sesion_max = Sesion::max('number_sesion');
        $student_tasks = StudentTask::where('student_id', $schedule->getStudentAttribute()->id)->get();
        $sesions_with_tasks = $schedule->getStudentAttribute()->tasks->count();
        $tasks_finisheds = array();
        if($student_tasks->isNotEmpty()){
            foreach($sesions as $sesion){
                $class = new \stdClass;
                $class->sesion_id = $sesion->id;
                $class->tasks = 0;
                $class->assist = 0;
                foreach($schedule->getStudentAttribute()->tasks as $task){
                    if($task->sesion_id == $sesion->id){
                        $class->tasks += 1;
                    }
                    foreach($schedule->getStudentAttribute()->assistances as $ass){
                        if($ass->sesion_id == $sesion->id){
                            $class->assist = 1;
                        }
                    }
                } 
                array_push($tasks_finisheds, $class);
            }
        }
        $student_tasks_ids = array_pluck($student_tasks->toArray(), 'task_id');
        $blockGroup = Professor::getBlockProfessor();
        $data = [
            'tasks_finisheds' => $tasks_finisheds,
            'student_tasks' => $student_tasks,
            'student_tasks_ids' => $student_tasks_ids,
            'schedule'=>$schedule,
            'sesions' => $sesions,
            'tasks' => $tasks,
            'blockGroup' => $blockGroup,
            'sesion_max' => $sesion_max,
        ];
        return view('components.contents.professor.studentSesions', $data);
    }

    public function getSesionsByBlock($blockId) {
        $block = Block::join('block_group', 'blocks.id', '=', 'block_group.block_id')
                      ->join('groups', 'block_group.group_id', '=', 'groups.id')
                      ->join('subject_matters', 'groups.subject_matter_id', '=', 'subject_matters.id')
                      ->where('blocks.id', '=', $blockId)
                      ->select('groups.*', 'blocks.id as block_id', 'subject_matters.name as subject_name')
                      ->get()
                      ->first();
        $sesions = Sesion::where('block_id', '=', $blockId)->get();
        $total_students_block = BlockSchedule::join('student_schedules', 'block_schedules.id', '=', 'student_schedules.block_schedule_id')
                                             ->where('block_schedules.block_id', '=', $blockId)
                                             ->get()
                                             ->count();
        $tasks_by_sesions = [];
        for($i=0 ; $i<sizeof($sesions) ; $i++) {
            $tasks_by_sesion = Sesion::join('tasks', 'sesions.id', '=', 'tasks.sesion_id')
                                     ->where('sesions.id', '=', $sesions[$i]->id)
                                     ->get()
                                     ->count();
            array_push($tasks_by_sesions, $tasks_by_sesion);
        }
        $commited_tasks_by_sesion = [];
        for($i=0 ; $i<sizeof($sesions) ; $i++) {
            $commited_task_by_sesion = StudentTask::join('tasks', 'student_tasks.task_id', '=', 'tasks.id')
                                                  ->where('tasks.sesion_id', '=', $sesions[$i]->id)
                                                  ->select('student_tasks.student_id')
                                                  ->get();
            $commited_task_by_sesion_array = [];
            for($j=0 ; $j<sizeof($commited_task_by_sesion) ; $j++) {
                array_push( $commited_task_by_sesion_array, $commited_task_by_sesion[$j]->student_id );
            }
            $commited_task_by_sesion_array = array_unique( $commited_task_by_sesion_array );
            array_push( $commited_tasks_by_sesion, sizeof($commited_task_by_sesion_array) );
        }
        $actualSesion = Sesion::getActualSesion($blockId);
        $data = [
            'block'                     => $block,
            'sesions'                   => $sesions,
            'total_students_block'      => $total_students_block,
            'tasks_by_sesions'          => $tasks_by_sesions,
            'commited_tasks_by_sesion'  => $commited_tasks_by_sesion,
            'actual_sesion'             => $actualSesion
        ];
        return $data;
    }

    public function getActualSesionBlock( $block_id ) {
        return Sesion::getActualSesion( $block_id );
    }
}
