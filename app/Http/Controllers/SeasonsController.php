<?php

namespace App\Http\Controllers;

use App\Season;
use App\Exam;
use Illuminate\Http\Request;

class SeasonsController extends Controller
{
    public function change(Request $request)
    {
        if($request->change_season_id){
            session(['season' => Season::find($request->change_season_id)]);
        }else{
            $o= new \stdClass();
            $o->name = 'All Seasons';
            $o->id = '';
            session(['season' => $o]);
        }

        return redirect()->back();
    }
}
