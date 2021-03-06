<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ValidationTrait;
use Carbon\Carbon;

class ScheduleRecord extends Model
{
    use ValidationTrait;
    //protected $table= 'schedules_record';
    protected $fillable = ['laboratory_id','day_id','hour_id','professor','subject','color'];
    protected $hidden = ['created_at','update_at'];
    protected $rules = [
        'laboratory_id' => 'required',
        'day_id'        => 'required',
        'hour_id'       => 'required',
        'professor'     => 'required',
        'subject'       => 'required',
        'color'         => 'required'
    ];
    protected $appends = ['blockid','name_block', 'laboratory', 'day', 'hour'];
    public static function getSchedulesByLaboratory($id){
        return self::where('laboratory_id', $id)->orderBy('day_id')->get();
    }
    public static function getDayAndHourFormatWithId($id){
        $date = self::find($id);
        $day = Day::find($date->day_id)->name;
        $hourStart = Hour::find($date->hour_id)->start;
        $hourEnd = Hour::find($date->hour_id)->end;
        $laboratory = Laboratory::find($date->laboratory_id)->name;
        $data=[
            "start"=>$hourStart,
            "end"=>$hourEnd,
            "day" => $day,
            "laboratory" => $laboratory,
        ];
        return $data;
    }
    public function blocks(){
        return $this->belongsToMany('App\Block','block_schedules','schedule_id','block_id');
    }
    public function getNameBlockAttribute(){
        return $this->blocks()->first()->name;
    }
    public function getBlockidAttribute(){
        return $this->blocks()->first()->id;
    }
    public function getLaboratoryAttribute(){
        return Laboratory::find($this->laboratory_id);
    }
    public function getDayAttribute(){
        return Day::find($this->day_id);
    }
    function getHourAttribute(){
        return Hour::find($this->hour_id);
    }
    public static function schedulesNow(){
        $date = Carbon::now();
        $data = [];
        $schedules = ScheduleRecord::all();
        $validSchedules = [];
        foreach ($schedules as $schedule) {
            $blockSchedules = BlockSchedule::where('schedule_id',$schedule->id)->get()->all();
            foreach ($blockSchedules as $blockSchedule) {
                $blockId = $blockSchedule->block_id;
                $block = Block::find($blockId);
                if(Management::getActualManagement()->id == $block->management_id){
                    array_push($validSchedules,(Object)$schedule);
                }
            }
        }
        foreach ($validSchedules as $schedule) {
            $sesions = Sesion::getSesionDayReal($schedule->id);
            $hourSesion = Carbon::parse($sesions['hour']);
            $endHour = new Carbon($hourSesion->toTimeString());
            $endHour->addHours(1)->addMinutes(30);
            if ($sesions['same'] && $date->between($hourSesion,$endHour,true)) {
                $sesions['laboratory_id'] = $schedule->laboratory_id;
                $sesions['schedule_id'] = $schedule->id;
                array_push($data,(Object)$sesions);
            }
        }
        return $data;
    }
}
