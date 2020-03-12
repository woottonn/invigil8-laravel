<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Location;
use App\Exam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class LocationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->cID= session('centre')->id ?? '';
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $locations = Location::
              when($this->cID, function ($query){
                return $query->where('centre_id', $this->cID);
            })
            ->get();

        $title = "Locations";
        $subtitle = "A list of locations within the system. A location will be a room e.g. A5, or a hall, gym, etc";
        return view('locations.index', compact('title', 'subtitle', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $location = New Location();

        $title = "Create location";
        $subtitle = "Create a location e.g. A5, hall, gym, etc";
        return view('locations.create', compact('location', 'title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request['centre_id'] = session('centre')->id;

        $this->validate($request, [
            'name' => ['required', 'string', 'max:255', Rule::unique('locations')->where(function ($query) use ($request)  {
                return $query->where('centre_id', session('centre')->id)->where('name', $request->name);
            })],
            'centre_id' => ['required', 'numeric']
        ]);

        $location = new Location();
        $location->name = $request->name;
        $location->centre_id = session('centre')->id;
        $location->save();

        return redirect()->route('locations.index')->with('success', 'Location created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Location $location)
    {
        $title = "Edit location";
        $subtitle = "Edit a location e.g. A5, hall, gym, etc";
        return view('locations.edit', compact('location', 'title', 'subtitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Location $location)
    {
        $request['centre_id'] = session('centre')->id;

        $location->update($this->validate($request, [
                'name' => ['required', 'string', 'max:255', Rule::unique('locations')->where(function ($query) use ($request)  {
                    return $query->where('centre_id', session('centre')->id)->where('name', $request->name);
                })],
                'centre_id' => ['required', 'numeric']
            ]
        ));

        return redirect()->route('locations.index')->with('success', 'Location edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Location $location)
    {
        $location->participations()->delete();
        $location->exams()->delete();
        $location->delete();
        return redirect()->back()->with('success', 'Location deleted successfully - all exams associated with this location have also been deleted.');
    }

}
