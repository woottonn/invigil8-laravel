<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Location extends Model
{

    use HasRelationships;

    protected $guarded = [];

    private $cID, $sID;

    public $appends = ['exam_count'];

    public function __construct()
    {
        $this->sID= session('season')->id ?? '';
        $this->cID= session('centre')->id ?? '';
    }

    public function participations()
    {
        return $this->hasManyThrough(Participation::class,Exam::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function getParticipationCountAttribute()
    {
        return $this->hasManyDeep(
            Participation::class,
            [Location::class],
            [null, 'activity_id', 'exam_id'],
            [null, 'id', 'id'],
        )
            ->join('exams','exams.id','=','participations.exam_id')
            ->when($this->sID, function ($query) {
                return $query->where('exams.season_id', $this->sID);
            })
            ->when($this->cID, function ($query) {
                return $query->where('exams.centre_id', $this->cID);
            })
            ->count();
    }

    public function getExamCountAttribute(){

        $total = DB::Table('exams')
            ->selectRaw('count(exams.id) AS count')
            ->join('locations','locations.id','=','exams.location_id')
            ->when($this->sID, function ($query) {
                return $query->where('exams.season_id', $this->sID);
            })
            ->when($this->cID, function ($query) {
                return $query->where('exams.centre_id', $this->cID);
            })
            ->get();

        foreach($total as $count){
            if($count->count==0){
                return '0';
            }else{
                return $count->count;
            }
        }
    }

    public function getParticipationsByCentreAttribute()
    {
        return Participation::
        join('exams','exams.id','=','participations.exam_id')
            ->join('locations','exams.location_id','=','locations.id')
            ->where('locations.id', $this->id)
            ->when($this->sID, function ($query) {
                return $query->where('exams.season_id', $this->sID);
            })
            ->when($this->cID, function ($query) {
                return $query->where('exams.centre_id', $this->cID);
            })
            ->count();
    }

    public function getTotalDurationHoursAttribute(){

        $total_time =  DB::Table('exams')
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC( `duration` ))) AS duration')
            ->join('participations','exams.id','=','participations.exam_id')
            ->join('locations','locations.id','=','exams.location_id')
            ->where('locations.id', $this->id)
            ->when($this->sID, function ($query) {
                return $query->where('exams.season_id', $this->sID);
            })
            ->when($this->cID, function ($query) {
                return $query->where('exams.centre_id', $this->cID);
            })
            ->get();

        foreach($total_time as $times){
            if(substr($times->duration, 0, -6)==0){
                return '0';
            }else{
                return ltrim(substr($times->duration, 0, -6), "0");
            }
        }

    }
}
