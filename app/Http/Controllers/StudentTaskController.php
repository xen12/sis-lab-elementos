<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Student;
use App\StudentSchedule;
use Carbon\Carbon;
use App\Sesion;
use App\Task;
use App\Professor;
use App\BlockGroup;
use App\Group;
use App\BlockSchedule;
use App\Day;
use App\StudentTask;
use App\Management;
use App\ScheduleRecord;

class StudentTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id','=',$user->id)->first();
        $schedules = StudentSchedule::getDateTimeStudentSchedulesByStudentId($student->id);
        $management = Management::getActualManagement();
        $message = '';
        $sesions = [];
        if($schedules != []){
            foreach ($schedules as $schedule) {
                $sesionId = Sesion::getSesionIdToDayByBlock($schedule['block_id'], $schedule['schedule_id']);
                if($sesionId != -1){
                    $blockGroup = [
                        'sesionId' => $sesionId,
                        'group_id' => $schedule['group_id'],
                        'block_id' => $schedule['block_id'],
                        'schedule_id' => $schedule['schedule_id']
                    ];
                    array_push($sesions,(object)$blockGroup); 
                }
            }
        }else{
            $message = 'No te encuentras inscrito a ninguna materia aún';        
            $data = [
                'student' => $student,
                'sesions' => [],
                'management' => $management,
            ];
            return view('components.contents.student.activities')->with($data)->withErrors($message);
        }
        $sesionOfWeek=[];
        foreach ($sesions as $sesion) {
            $sesionWeek = Sesion::find($sesion->sesionId);
            $tasks = Task::where('sesion_id',$sesion->sesionId)->get()->all();
            $taskAll = [];
            $taskDone = null;
            foreach ($tasks as $task) {
                $taskDone = StudentTask::where('task_id',$task->id)->where('student_id',$student->id)->get()->first();
                $taskData = [
                    'task' => $task,
                    'done' => $taskDone
                ]; 
                array_push($taskAll,(object)$taskData);
            }
            $totalSesions = count(Sesion::where('block_id',$sesion->block_id)->get()->all());
            $day = Day::find(ScheduleRecord::find($sesion->schedule_id)->day_id)->name;
            $sesionTask = [
               // 'tasks' => $tasks,
                'tasks' => $taskAll,
                'sesion' => $sesionWeek,
                'subject' => Group::getSubjectById($sesion->group_id)->name,
                'block_id' => $sesion->block_id,
                'totalSesion' => $totalSesions,
                'day' => $day,
                'taskDone' => $taskDone,
                'schedule_id' =>$sesion->schedule_id,
            ];
            array_push($sesionOfWeek,(object)$sesionTask);
        }
        $data = [
            'student' => $student,
            'sesions' => $sesionOfWeek,
            'management' => $management,
        ];
        return view('components.contents.student.activities')->with($data);
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
        if($request->hasFile('practice')){
            $file = $request->file('practice');
            $extension = $file->getClientOriginalExtension();
            if($extension=='rar'||$extension=='zip'){
                $user = Auth::user();
                $student = Student::where('user_id','=',$user->id)->first();
                $hour = Carbon::now()->format('H:i:s');
                // Refactorizacion para ver si se entrego la tarea en el dia correcto
                //$inHour = StudentTask::inHour($hour, $request->schedule_id);
                $inDay = StudentTask::inDay($request->schedule_id);
                //$textInHour = "no";
                $textInDay = "no";
                // if($inHour){
                //     $textInHour = "yes";
                // }
                if($inDay){
                    $textInDay = "yes";
                }
                $fileName = $file->getClientOriginalName();
                $fileSesion = $request->sesion_number;
                $blockScheduleId = BlockSchedule::where('schedule_id',$request->schedule_id)->where('block_id',$request->block_id)->get()->first()->id;
                $studentSchedule = StudentSchedule::where('student_id',$student->id)->where('block_schedule_id',$blockScheduleId)->get()->first();
                if($studentSchedule!=[]){
                    $file -> move(storage_path('app').'/public/'.$studentSchedule->student_path.'/sesion-'.$fileSesion,$fileName);
                    $data = [
                        "description" => $request->description,
                        "task_id" => $request->task_id,
                        "student_id" => $student->id,
                        "task_name" => $fileName,
                        "task_path" => $studentSchedule->student_path.'/sesion-'.$fileSesion,
                        //"in_time" => $textInHour
                        "in_time" => $textInDay
                    ];
                    StudentTask::create($data);
                }
                return back();
            }else{
                return back()->withErrors('Procure enviar archivos formato: .zip .rar');
            }
        }else{
            return back()->withErrors('Archivo con tamaño mayor a 2MB');
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
        if($request->hasFile('practice')){
            $inDay = StudentTask::inDay($request->schedule_id);
            $textInDay = "no";
            if($inDay){
                $textInDay = "yes";
            }
            $file = $request->file('practice');
            $fileName = $file->getClientOriginalName();
            $studentTask = StudentTask::find($id);
            $studentTask->description = $request->description;
            $studentTask->task_name = $fileName;
            $studentTask->in_time = $textInDay;
            $studentTask->save();
            $file -> move(storage_path('app').'/public/'.$studentTask->task_path,$fileName);
            return back();
        }else{
            return back()->withErrors('Archivo con tamaño mayor a 2MB');
        }
    }

    public function updateAuxiliar(Request $request){
        $studentTask = StudentTask::find($request->id);
        $studentTask->observation = $request->observation;
        $studentTask->save();
        return response()->json(['success'=>'correcto!']);
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

    // REVISAR CREO QUE VERION OBSOLETA ////////////////////////////////////////////
    public function getTasksStudent( $student_id, $block_id ) {
        $studentTasks = StudentTask::where('student_tasks.student_id', $student_id)
                                   ->join('tasks', 'student_tasks.task_id', 'tasks.id')
                                   ->join('sesions', 'tasks.sesion_id', 'sesions.id')
                                   ->where('sesions.block_id', $block_id)->select('student_tasks.*')
                                   ->get();
        $block_tasks = Task::join('sesions', 'tasks.sesion_id', 'sesions.id')
                           ->where('sesions.block_id', $block_id)
                           ->select('tasks.*')
                           ->get();
        $data = [
            'block_sesions' => Sesion::where('block_id', $block_id)->get(),
            'block_tasks' => $block_tasks,
            'student_tasks' => $studentTasks
        ];
        return $data;
    }

    public function getStudentTask($student_id, $sesion_id) {
        $tasks = Task::join('student_tasks', 'tasks.id', '=', 'student_tasks.task_id')
                    ->where('student_tasks.student_id', '=', $student_id)
                    ->where('tasks.sesion_id', '=', $sesion_id)
                    ->select('student_tasks.*', 'tasks.title as task_title', 'tasks.published_by as task_published_by', 'tasks.description as task_description', 'tasks.task_path as path', 'tasks.task_file as file', 'tasks.updated_at as task_updated_at')
                    ->orderby('title')
                    ->get();
        $sesion_tasks = Task::where('sesion_id', '=', $sesion_id)->orderby('title')->get();
        $data = [
            'tasks' => $tasks,
            'sesion_tasks' => $sesion_tasks
        ];
        return $data;
    }

    public function studentTasks() {
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
        $sesions = Sesion::join('blocks', 'sesions.block_id', '=', 'blocks.id')
                         ->join('block_group', 'blocks.id', '=', 'block_group.block_id')
                         ->join('groups', 'block_group.group_id', '=', 'groups.id')
                         ->join('managements', 'blocks.management_id', '=', 'managements.id')
                         ->where('groups.professor_id', '=', $professor->professor_id)
                         ->select('sesions.*', 'groups.id as group_id', 'blocks.id as block_id', 'managements.id as management_id')
                         ->orderby('number_sesion')
                         ->get();
        $data = [
            'managements' => Management::all()->reverse(),
            'groups' => $groups,
            'sesions' => $sesions
        ];
        return view('components.contents.professor.studentTasks', $data);
    }
}
