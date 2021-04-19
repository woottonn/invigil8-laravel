<?php

namespace App\Http\Controllers\Auth;

use App\Centre;
use App\Http\Controllers\Controller;
use App\Season;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        //dd(1);
        $user = $this->guard()->user();

        session(['api_token' => $user->api_token]);

        //Auto login to season
        foreach(config('sitevars.seasons') as $key=>$season){
            if(Carbon::now()->format('Y-m-d') >= $season['date_start']&&Carbon::now()->format('Y-m-d') <= $season['date_end']) {
                $season_name = $key;
            }
        }
        session(['season' => Season::where('name', $season_name)->first()]);
        //End auto season

        session(['centres' => Centre::where('active', 1)->get()]);
        session(['centre' => Centre::findOrFail(auth()->user()->centre_id)]);
        session(['user' => $user]);

        return redirect()->route('home') ;
    }


}
