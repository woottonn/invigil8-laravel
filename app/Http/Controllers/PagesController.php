<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class PagesController extends Controller
{
    use HasRoles;

    public function index(){

        if(Auth::check()){
            if(Auth::user()->hasRole('Super Admin')){
                return redirect()->route('superadmin.dashboard', ['centre_id' => auth()->user()->centre_id]) ;
            }elseif(Auth::user()->hasRole('Centre Admin')){
                return redirect()->route('centreadmin.dashboard') ;
            }elseif(Auth::user()->hasRole('Invigilator')) {
                return redirect()->route('dashboard') ;
            }
        }else{
                return view('auth.login') ;
        }

    }

    public function cookies(){

        return view('cookies');
    }

    public function change(){
        $include_icon_create = 1;
        $hide_centre_and_season = 1;

        return view('season-centre-change', compact('include_icon_create', 'hide_centre_and_season'))
;    }


}
