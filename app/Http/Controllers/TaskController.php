<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Professor;
use App\Student;
use App\Sesion;
use App\Task;
use App\Block;
use App\Group;
use App\SubjectMatter;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blockGroup = Professor::getBlockProfessor();
        if($blockGroup!=null){
            $blockGroupId = $blockGroup->block_id;
            $sesions = Sesion::where('block_id','=',$blockGroupId)->get();
            $tasks = Task::all();
            $validTasks=[];
            foreach ($tasks as $task) {
                foreach($sesions as $sesion){
                    if($task->sesion_id==$sesion->id && $sesion->block_id==$blockGroupId){
                        array_push($validTasks,$task);
                    }
                }
            }
            $sesion_max = $sesions->count();
            $data = [
                'sesion_max'=>$sesion_max,
                'sesions'=>$sesions,
                'blockGroup'=>$blockGroup,
                'tasks'=>$validTasks,
                'blockId' => $blockGroupId,
            ];
            return view('components.contents.professor.publishTasks', $data);
        }else{
            $data = [
                'sesion_max' => 0,
                'sesions' => [],
                'blockGroup' => [],
                'tasks' =>[],
                'blockId' => 0,
            ];
            return view('components.contents.professor.publishTasks', $data);
        }
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
    public function store()
    {
        $info = \request('info');
        dd($info);
    	$data = [];
        parse_str($info, $data);

        $sesion_number = Sesion::findOrFail($data['sesion_id'])->sesion_number;

        $blockGroupId = Professor::getBlockProfessor()->block_id;
        $dir = Block::where('id', '=', $blockGroupId)->get()->first()->block_path;
        try {
            if($data['practice']){
                $file = $data['practice'];
                $extension = $file->getClientOriginalExtension();
                if($extension=='rar'||$extension=='zip'||$extension=='tar.gz'||$extension=='pdf'){
                    //$file = $request->file('practice');
                    $name = $file->getClientOriginalName();
                    $semiPath ='/storage/'.$dir.'/sesion-'.$sesion_number.'/';
                    $path = public_path().$semiPath;
                    $file -> move($path,$name);
                    $task = [
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'sesion_id' => $data['sesion_id'],
                        'task_path' => $semiPath,
                    ];
                    Task::create($task);
                    $success = true;
                }
            }
        } catch (\Exception $exception) {
    		$success = false;
	    }
        return response()->json(['res' => $success]);
        //return dd($data['practice']);
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

    public function showStudentTask($idStudent, $idTask)
    {
        $student = Student::find($idStudent);
        $user = User::where('id', '=', $student->user_id)->get()->first();
        $task = Task::find($idTask);
        $group = Group::find($student->first()->group_id);
        $subjectMatter = SubjectMatter::where('id', '=', $group->subject_matter_id)->get()->first();

        $data = [
            'student' => $student,
            'user' => $user,
            'task' => $task,
            'group' => $group,
            'subject_matter' => $subjectMatter,
        ];
        return view('components.contents.professor.studentTask', $data);
    }

    public function getTasksBySesion($id){
        $sesion_id = Sesion::findOrFail($id)->id;
        $tasks = Task::where('sesion_id', '=', $sesion_id)->get();

        return $tasks;
    }
}
