<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{

    protected $guarded = [];

    /*public function newQuery() {

        $centre_id = session('centre')->id ?? '';
        $season_id = session('season')->id ?? '';

        return parent::newQuery($centre_id, $season_id)
            ->join('exams','exams.id','=','participations.session_id')
            ->when($season_id, function ($query, $season_id) {
                return $query->where('exams.season_id', $season_id);
            })->when($centre_id, function ($query, $centre_id) {
                return $query->where('exams.centre_id', $centre_id);
            });
    }*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo('App\Exam');
    }

}

