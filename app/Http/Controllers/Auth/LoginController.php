<?php

namespace App\Http\Controllers\Auth;

use App\Centre;
use App\Http\Controllers\Controller;
use App\Season;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    protected function loggedOut() {
        return redirect('/login');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /* Hijacking this */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /* Hijacking this */
    protected function authenticated(Request $request, $user)
    {
        session(['api_token' => $user->api_token]);
        session(['season' => Season::where('active', 1)->first()]);
        session(['centres' => Centre::where('active', 1)->get()]);
        session(['centre' => Centre::findOrFail(auth()->user()->centre_id)]);
        session(['user' => $user]);

        return redirect()->route('home') ;

    }

}
