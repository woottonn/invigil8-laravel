<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Event;

class Exam extends Model
{
    protected $guarded = [];

    protected $fillable = ['date', 'duration'];

    /*public function newQuery() {

        $centre_id = session('centre')->id ?? '';
        $season_id = session('season')->id ?? '';

        return parent::newQuery($centre_id, $season_id)
            ->when($season_id, function ($query, $season_id) {
                return $query->where('season_id', $season_id);
            })->when($centre_id, function ($query, $centre_id) {
                return $query->where('centre_id', $centre_id);
            });
    }*/

    public function users()
    {
        return $this->belongsToMany('App\User', 'participations');
    }

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

    public function participations_lead(){
        return $this->hasMany('App\Participation')
            ->where('participation_type', 1)
            ->orderBy('updated_at')
            ->get();
    }

    public function participations_extra(){
        return $this->hasMany('App\Participation')
            ->where('participation_type', '!=', 1)
            ->orderBy('updated_at')
            ->get();
    }

    public function getLeadFullAttribute(){
        if($this->invigilators_lead_req==$this->participations_lead()->count()){
            return true;
        }
    }

    public function getLeadRemainingAttribute(){
        return $this->invigilators_lead_req - $this->participations_lead()->count();
    }

    public function getExtraFullAttribute(){
        if($this->invigilators_req==$this->participations_extra()->count()){
            return true;
        }
    }

    public function getExtraRemainingAttribute(){
        return $this->invigilators_req - $this->participations_extra()->count();
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getPrettyDurationAttribute()
    {
        return substr($this->duration, 0, -3);
    }

    public function getLiveAttribute()
    {
        if($this->state == 1) return true;

    }

    public function getPrettyDateAttribute()
    {
        return Carbon::parse($this->date)->format('l jS F Y (g:ia)');
    }

    public function getPrettyTimeAttribute()
    {
        return Carbon::parse($this->date)->format('g:ia');
    }

    public function getPrettyFinishTimeAttribute()
    {
        $h = substr($this->duration, 0, -6);
        $m = substr($this->duration, 3, -3);
        return Carbon::parse($this->date)->addHours($h)->addMinutes($m)->format('g:ia');
    }

    public function getFinishTimeAttribute()
    {
        $h = substr($this->duration, 0, -6);
        $m = substr($this->duration, 3, -3);
        return Carbon::parse($this->date)->addHours($h)->addMinutes($m)->format('Y-m-d H:i:s');
    }

    public function active()
    {
        if(Carbon::parse(now())->format('YmdHis') > Carbon::parse($this->date)->format('YmdHis')
            &&
            Carbon::parse(now())->format('YmdHis') < Carbon::parse($this->finish_time)->format('YmdHis')
        ){ return true; }
    }

    public function getPrettyDateShortAttribute()
    {
        return Carbon::parse($this->date)->format('jS M Y');
    }

    public function singleUserCount($user_id){
        return $this->participations->where('user_id', $user_id)->count();
    }

    public function invigilator_check($id){
        return Participation::where('user_id', $id)->where('exam_id', $this->id)->exists();
    }



}
