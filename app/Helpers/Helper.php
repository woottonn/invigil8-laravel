<?php


if (!function_exists('addToTimeline')) {
    function addToTimeline($user_id, $author_id, $exam_id, $centre_id, $season_id, $message){
        $timeline = new \App\Timeline();
        $timeline->user_id = $user_id;
        $timeline->author_id = $author_id;
        $timeline->exam_id = $exam_id;
        $timeline->season_id = $season_id;
        $timeline->centre_id = $centre_id;
        $timeline->message = $message;
        $timeline->save();
    }
}


if (!function_exists('checkCurrentSeason')) {
    function checkCurrentSeason($id)
    {
        if (!session('season')->id == $id) {
            abort(403);
        }
    }
}


if (!function_exists('prettyDateShort')) {
    function prettyDateShort($date)
    {
        return \Carbon\Carbon::parse($date)->format('jS M y');
        /*return $date->format('jS M y');*/
    }
}

if (!function_exists('checkSession')) {
    function checkSession()
    {
        if(@!session('season')){ return false;  }else{ return true; }
    }
}




