<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hour;
use App\Day;
use App\Laboratory;
use App\ScheduleRecord;
use App\BlockSchedule;
use App\Block;

class ScheduleRecordController extends Controller
{
    public function index(){
        $managements=Management::getAllManagements();
        $data=[
            'managements'=>$managements
        ];
        return view('components.contents.management.index',$data);
    }

    public function create(Request $request,$laboratory_id=1){
        // dd($request->block_id);
        // $scheduleRecords = ScheduleRecord::getSchedulesByLaboratory($laboratory_id);
        $laboratorys=Laboratory::getAllLaboratory();
        $days=Day::getAllDays();
        $hours=Hour::getAllHours();
        $data=[
            // 'scheduleRecords' => $scheduleRecords,
            'laboratories'    => $laboratorys,
            'days'            => $days,
            'hours'           => $hours
        ];
        return view('components.contents.scheduler.index',$data);
    }

    public function store(Request $request){
        //return view('components.contents.scheduler.index');
        if($request->ajax()){
            $id=ScheduleRecord::create([
                'laboratory_id' => $request->laboratory_id,
                'day_id' => $request->day_id,
                'hour_id' => $request->hour_id,
                'availability' => true
            ])->id;
            BlockSchedule::create([
                'schedule_id' => $id,
                'block_id' => $request->block_id
            ]);
            //ScheduleRecord::create($request->all());
            return response()->json([
                "success" => $request->all()
            ]);
        }
        // $input = $request->all();
        // $scheduleRecords = new ScheduleRecord();
        // $blockSchedule = new BlockSchedule();
        // if($scheduleRecords->validate($input)){

        //     $id = Management::create([
        //         'laboratory_id' => $request->laboratory_id,
        //         'day_id' => $request->day_id,
        //         'hour_id' => $request->hour_id,
        //         'availability' => true
        //     ])->id;
        //     BlockSchedule::create([
        //         'schedule_id' => $id,
        //         'block_id' => $request->block_id
        //     ]);


            Session::flash('status_message','Gestión añadida!');
            
        //     return redirect('/admin/managements');
        // }
        //     return redirect('/admin/management/create')->withInput()->withErrors($managements->errors);
    }

    public function edit($id){
        $management = Management::findOrFail($id);
        $semesters=['1','2','3','4'];
        $data=[
            'management' => $management,
            'semesters' => $semesters
        ];
        
        return view('components.contents.management.edit')->withTitle('Editar la Gestión')->with($data);
    }

    public function update(Request $request, $id){
        $management = Management::find($id);
        $input = $request->all();

        if($management->validate($input)){
            $management->semester = $request->semester;
            $management->start_management = $request->start_management;
            $management->end_management = $request->end_management;
            $management->save();

            Session::flash('status_message', 'Gestión Editada!');
            return redirect('/admin/managements');
        }
        return back()->withInput($input)->withErrors($management->errors);
    }

    public function destroy($id){
        try{
            $management = Management::findOrFail($id);
            $management->delete();
            $status_message = 'Gestión eliminada correctamente';
        }catch(ModelNotFoundException $e){
            $status_message = 'no Subject-matter with tha id';
        }

        Session::flash('status_message',$status_message);
        return redirect('/admin/managements');
    }
}
