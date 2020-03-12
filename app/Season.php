<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public function sessions()
    {
        return $this->hasMany(Exam::class);
    }

    public function participations()
    {
        return $this->hasManyThrough('App\Participation', 'App\Exam');
    }

}
