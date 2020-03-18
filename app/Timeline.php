<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    public function getTimeSinceAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->updated_at))->diffForHumans();
    }

    public function getPrettyDateAttribute()
    {
        return Carbon::parse($this->updated_at)->format('l jS F Y (h:m:s)');
    }
}
