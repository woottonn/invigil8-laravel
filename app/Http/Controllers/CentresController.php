<?php

namespace App\Http\Controllers;

use App\Centre;
use App\Season;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class CentresController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*$this->middleware('auth'); //stops any guests from seeing user data
        $this->middleware('role:Super Admin,PLT Student')->only('custom_groups_all');*/

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $centres = Centre::orderBy('name','ASC')->paginate(10);
        return view('centres.index', compact('centres'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $centre = New Centre();
        return view('centres.create', compact('centre'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $centre = $this->validate($request, [
                'name' => ['required', 'string', 'max:255', 'unique:centres,name'],
            ]
        );

        $centre = Centre::create([
            'name' => $centre['name'],
        ]);

        if(Auth::user()->hasRole('Super Admin')) {
            session(['centres' => Centre::where('active', 1)->get()]);
        }

        return redirect()->route('centres.index')->with('success', 'Centre created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Centre $centre)
    {
        return view('centres.edit', compact('centre'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Centre $centre)
    {
        $centre->update($this->validate($request, [
                'name' => ['required', 'string', 'max:255', 'unique:centres,name,'.$centre->id],
            ]
        ));

        return redirect()->route('centres.index')->with('success', 'Centre edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Centre $centre)
    {
        $centre->users()->delete();
        $centre->delete();
        if(Auth::user()->hasRole('Super Admin')) {
            session(['centres' => Centre::where('active', 1)->get()]);
        }
        return redirect()->back()->with('success', 'Centre deleted successfully');
    }

    public function change(Request $request)
    {
        if($request->change_centre_id){
            session(['centre' => Centre::find($request->change_centre_id)]);
        }else{
            $o= new \stdClass();
            $o->name = 'All Centres';
            $o->id = '';
            session(['centre' => $o]);
        }

        return redirect()->back();
    }

}
