<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ValidationTrait;
use Illuminate\Support\Facades\Auth;

class Professor extends Model
{
    use ValidationTrait;
    protected $fillable = [
        'id', 'user_id'
    ];
    protected $rules = [
        'names' => 'required|max:100',
        'first_name' => 'required|max:100',
        'second_name' => 'required|max:100',
        'email' => 'email|required|max:150',
        'password' => 'required|min:8',
        'code_sis' => 'required|max:10|min:8'
    ];
    public function subjectMatters(){
        return $this->belongsToMany('App\SubjectMatter', 'groups', 'professor_id', 'subject_matter_id');
    }
    public static function getAllProfessors(){
        return Professor::join('users','user_id','=','users.id')->select('users.role_id', 'users.code_sis', 'users.names', 'users.first_name', 'users.second_name', 'users.email', 'users.password', 'users.img_path', 'users.remember_token', 'users.created_at', 'users.updated_at', 'professors.id')->get();
    }
    public static function getProfessor($id){
        $professors = self::getAllProfessors();
        return $professors->where('id', $id)->first();
    }

    public static function getBlockProfessor()
    {
        $professor = Professor::where('user_id','=', Auth::user()->id)->get()->first();
        $group = Group::where('professor_id', '=', $professor->id)->get()->first();
        if($group!=null){
            return BlockGroup::where('group_id', '=', $group->id)->get()->first(); //cambie le first
        }else{
            return null;
        }
    }
    public static function getBlocksProfessor()
    {
        $professor = Professor::where('user_id','=', Auth::user()->id)->get()->first();
        $groups = Group::where('professor_id', '=', $professor->id)->get();
        $blocks=[];
        foreach ($groups as $group) {
            $blockGroup= BlockGroup::where('group_id', '=', $group->id)->get()->first();
            if($blockGroup!=null){
                array_push($blocks,$blockGroup);
            }
        }
        return  $blocks;
    }
    public static function getSubjectByBlockGroup($id)
    {
        $group = BlockGroup::where('id','=',$id)->get()->first();
        $groupId = $group->group_id;
        $subject = Group::where('id','=',$groupId)->get()->first();
        $subjectId = $subject->subject_matter_id;
        $subjectName = SubjectMatter::where('id','=',$subjectId)->get()->first()->name;
        return $subjectName;
    }
    
    public function groups(){
        return $this->hasMany('App\Group');
    }

    public static function getBlockGroupsProfessor() {
        $professor = Professor::where('user_id', '=', Auth::user()->id)->get()->first();
        $groups = Group::where('professor_id', '=', $professor->id)->get();
        $blockGroups = BlockGroup::all();
        $result = [];
        foreach($groups as $gp){
            foreach($gp->blockGroups as $blockGroup){
                array_push($result,$blockGroup);
            }
        }
        // foreach( $blockGroups as $blockGroup ) {
        //     foreach ( $groups as $group ) {
        //         if( $group->id == $blockGroup->group_id ) {
        //             array_push( $result, $blockGroup );
        //             break;

        //         }
        //     }
        // }
        return $result;
    }
}
