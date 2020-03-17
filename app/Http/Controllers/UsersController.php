<?php

namespace App\Http\Controllers;

use App\Centre;
use App\Http\Controllers\Controller;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); //stops any guests from seeing user data
        $this->middleware('permission:USERS-view')->only('index', 'show');
        $this->middleware('permission:USERS-create')->only('create', 'store');
        $this->middleware('permission:USERS-edit')->only('edit', 'update');
        $this->middleware('permission:USERS-delete')->only('destroy');
    }

    public function setup()
    {
        $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $centre_id = session('centre')->id ?? '';

        if(auth()->user()->hasRole('Super Admin')){
            $users =  User::orderBy('lastname','DESC')
                ->orderBy('firstname','DESC')
                ->when($centre_id, function ($query, $centre_id) {
                    return $query->where('centre_id', $centre_id);
                })
                ->get();
        }else{
            $centre_id = session('centre')->id;
            $users =  User::orderBy('lastname','ASC')
                ->orderBy('firstname','ASC')
                ->when($centre_id, function ($query, $centre_id) {
                    return $query->where('centre_id', $centre_id);
                })
                ->role(['Invigilator', 'Centre Admin'])
                ->get();
        }


        $title = "Users";
        $subtitle = "A list of all users in the system.";
        return view('users.index', compact('users', 'centre_id', 'title', 'subtitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $type = "add";
        $user = New User();
        $centres = Centre::all();

        if(auth()->user()->hasRole('Super Admin')){
            $roles = Role::all();
        }else{
            $roles = Role::where('name', '!=', 'Super Admin')->get();
        }

        if(old('roles')) { //if form data has old values
            $user_roles = old('roles'); // set them
        }else{
            $user_roles = NULL;
        }
        $title = "Create a user";
        $subtitle = "Fill in all the required fields.";
        return view('users.create', compact('user', 'centres', 'roles', 'type', 'user_roles', 'title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        if(auth()->user()->hasRole('Centre Admin')){
            $centre_id = auth()->user()->centre_id;
        }else{
            $centre_id = $request->centre_id;
        }

        $user_roles = $request->roles; // set them

        $this->validate($request, [
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'centre_id' => ['numeric'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]
        );

        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->centre_id = $centre_id;
        $user->email = $request->email;
        $user->api_token = Str::random(60);
        $user->password = Hash::make($request->password);

        $user->save();

        //Check if someone is trying to circumvent the roles and add a superadmin
        if(!empty($request->roles)){
                if(auth()->user()->hasRole('Super Admin')){}else{
                    if(Role::findById($role)->name=="Super Admin"){
                        return redirect()->route('users.index')->with('error', 'Cheeky!');
                 }
            }
        }
        //End check

        // assign roles
        if(!empty($request->roles)){
            $user->assignRole($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return string
     */
    public function show(User $user)
    {
        return redirect()->route('dashboard', ['user' => $user->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {if(auth()->user()->hasRole('Super Admin')||($user->centre_id==auth()->user()->centre_id)){

        $centres = Centre::all();

        if(auth()->user()->hasRole('Super Admin')){
            $roles = Role::all();
        }else{
            $roles = Role::where('name', '!=', 'Super Admin')->get();
        }

        if(old('roles')) { //if form data has old values
            $user_roles = old('roles'); // set them
        }else{ // otherwise
            $user_roles = $user->roles->pluck('id')->toArray(); //get users roles
        }

        $type = "edit";
        $title = "Edit - " . $user->full_name;
        $subtitle = "Fill in all the required fields.";
        return view('users.edit', compact('title', 'subtitle', 'user', 'centres', 'roles', 'type', 'user_roles'));

    }else{abort('403');}}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, Request $request)
    {

        if(auth()->user()->hasRole('Super Admin')){}else{
            $request->centre_id = auth()->user()->centre_id;
        }

        if(auth()->user()->hasRole('Super Admin')||($request->centre_id==auth()->user()->centre_id)){

        $user->update($this->validate(request(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'gender' => ['sometimes', 'numeric'],
            'sen' => ['sometimes', 'string', 'nullable'],
            'ppm' => ['sometimes', 'numeric', 'nullable'],
            'upn' => ['sometimes', 'string', 'nullable'],
            'dob' => ['sometimes', 'date', 'nullable'],
            'fsm' => ['sometimes', 'numeric', 'nullable'],
            'year' => 'sometimes|nullable|numeric',
            'form_group' => ['sometimes', 'string', 'nullable'],
            'disability' => ['sometimes', 'string', 'nullable'],
            'centre_id' => ['numeric'],
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'sometimes|nullable',
        ]));

        $user->password = Hash::make($user['password']);
        $user->save();

        //Check if someone is trying to circumvent the roles and add a superadmin
        if(!empty($request->roles)){
            if(auth()->user()->hasRole('Super Admin')){}else{
                if(Role::findById($role)->name=="Super Admin"){
                    return redirect()->route('users.index')->with('error', 'Cheeky!');
                }
            }
        }
        //End check

        // assign roles
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User edited successfully');
        }else{abort('403');}}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {if(auth()->user()->hasRole('Super Admin')||($user->centre_id==auth()->user()->centre_id)){

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');

    }else{abort('403');}}

    public function qrcode(Request $request){
        if(empty($request->id)){
            $user = auth()->user();
        }else{
            $user = User::find($request->id);
        }

        //Generate QR
        if ($user->upn) {$upn = $user->upn;} else {$upn = 1;}
        QrCode::format('png')->size(1000)->generate($upn, public_path('qr/' . $user->centre_id . '-' . $user->id . '.png'));
        // End QR

        return view('superadmin.users.qrcode', compact('user'));
    }






}
