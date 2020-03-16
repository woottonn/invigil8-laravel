<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Location;
use App\Centre;
use App\Charts\ChartsActivityTypes\PieGender;
use App\Charts\ChartsActivityTypes\PieHours;
use App\Charts\ChartsActivityTypes\PieParticipations;
use App\Charts\ChartsActivityTypes\PieSessions;
use App\Mail\DefaultEmail;
use App\Mail\DefaultEmailExamDelete;
use App\Mail\DefaultEmailExamUpdate;
use App\Participation;
use App\Exam;
use App\Http\Controllers\Controller;
use App\Timeline;
use App\User;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class ExamsController extends Controller
{

    private $cID, $sID;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); //stops any guests from seeing user data
        $this->middleware('permission:EXAMS-view')->only('index', 'show');
        $this->middleware('permission:EXAMS-create')->only('create', 'store');
        $this->middleware('permission:EXAMS-edit')->only('edit', 'update');
        $this->middleware('permission:EXAMS-delete')->only('destroy');

        $this->middleware(function ($request, $next) {
            $this->sID= session('season')->id ?? '';
            $this->cID= session('centre')->id ?? '';

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        if(auth()->user()->cannot('EXAMS-edit')) { $state = 1; }
        if($request->user_id){
            $exams = Exam::orderBy('date','DESC')
                ->join('participations','exams.id','=','participations.exam_id')
                ->when($this->sID, function ($query) {
                    return $query->where('exams.season_id', $this->sID);
                })
                ->when($this->cID, function ($query) {
                    return $query->where('exams.centre_id', $this->cID);
                })
                ->when(@$state, function ($query)  {
                    return $query->where('state', 1);
                })
                ->where('participations.user_id', auth()->user()->id)
                ->get('exams.*');
            $subtitle = "A list exams you are signed up to.";
        }else{
            $exams = Exam::orderBy('date','DESC')
                ->when($this->sID, function ($query) {
                    return $query->where('exams.season_id', $this->sID);
                })
                ->when($this->cID, function ($query) {
                    return $query->where('exams.centre_id', $this->cID);
                })
                ->when(@$state, function ($query)  {
                    return $query->where('state', 1);
                })
                ->get();
            $subtitle = "A list of all exams for this current season.";
        }

        $include_icon_create = 1;

        $title = "Exams";

        return view('exams.index', compact('exams', 'include_icon_create', 'title', 'subtitle'));
    }

    public function today(Request $request)
    {
        $centre_id = session('centre')->id ?? '';
        $season_id = session('season')->id ?? '';

        if(auth()->user()->can('EXAMS-edit')){

            $exams = Exam::orderBy('date','DESC')
                ->whereDate('date', Carbon::today())
                ->when($this->cID, function ($query) {
                    return $query->where('exams.centre_id', $this->cID);
                })
                ->get();

        }else{

        }

        $include_icon_create = 1;

        $title = "Today's Exams";
        $subtitle = "A list of all exams for today. Active exams will pulsate.";
        return view('exams.today', compact('exams', 'include_icon_create', 'title', 'subtitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        if (!session('season')->id || !session('centre')->id) {
            return redirect()->route('exams.index')->with('error', 'You must select a season & centre before creating an exam');
        }

        $exam = New Exam();
        $exam->duration = "00:00";
        $locations = Location::orderBy('name')->where('centre_id', session('centre')->id)->get();
        $centres = Centre::orderBy('name')->get();
        $exam->centre_id = session('centre')->id;
        $exam->duration = '01:00';

        $title = "Create exam";
        $subtitle = "Create a new exam.";

        return view('exams.create', compact('exam', 'locations', 'centres', 'title', 'subtitle'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bulk()
    {
        if (!session('season')->id || !session('centre')->id) {
            return redirect()->route('exams.index')->with('error', 'You must select a season & centre before creating an exam');
        }

        $exam = New Exam();
        $exam->duration = "00:00";
        $locations = Location::orderBy('name')->where('centre_id', session('centre')->id)->get();
        $centres = Centre::orderBy('name')->get();
        $exam->centre_id = session('centre')->id;
        $exam->duration = '01:00';

        $title = "Bulk creation of exams";
        $subtitle = "Enter multiple exams on this page and bulk submit";

        return view('exams.bulk', compact('exam', 'locations', 'centres', 'title', 'subtitle'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(auth()->user()->hasRole('Super Admin')){}else{
            $request['centre_id'] = session('centre')->id;
        }

        $this->validate(request(),[
            'description' => 'required|min:1',
            'date' => 'required|date',
            'exam_location_id' => 'required|integer|min:0',
            'duration' => 'required|date_format:H:i',
            'invigilators_lead_req' => 'sometimes|integer|min:0',
            'invigilators_req' => 'sometimes|integer|min:0',
            'students' => 'sometimes|integer|min:0',
            'hide_names' => 'required|integer|min:0',
            'centre_id' => 'required|integer|min:0',
            'state' => 'required|integer|min:0',
            'notes' => 'sometimes'
        ],[
            'date.required' => 'Please enter a date',
            'date.date' => 'Please enter a valid date',
            'duration.required' => 'Please enter a duration',
            'duration.date_format:H:i' => 'Please enter a valid duration',
        ]);

        $exam = new Exam;
        $exam->description = $request->description;
        $exam->date = date('Y-m-d H:i:s', strtotime($request->date));
        $exam->duration = date('H:i:s', strtotime($request->duration));
        $exam->author_id = auth()->user()->id;
        $exam->season_id = session('season')->id;
        $exam->location_id = $request->exam_location_id;
        $exam->invigilators_lead_req = $request->invigilators_lead_req;
        $exam->invigilators_req = $request->invigilators_req;
        $exam->hide_names = $request->hide_names;
        $exam->notes = $request->notes;
        $exam->students = $request->students;
        $exam->state = $request->state;
        $exam->centre_id = $request->centre_id;
        $exam->save();

        if($request->state==1){
            addToTimeline(0, $exam->author_id, $exam->id,session('centre')->id, session('season')->id,
                User::find($exam->author_id)->full_name." created a new exam: <a href='".url('/') . "/exams/" . $exam->id . "'>" . $exam->description . "</a>");
        }

        if($request->email==1){
            foreach (User::role('Invigilator')->where('lastname', 'Wootton')->where('centre_id', session('centre')->id)->get() as $user){
                $mail = new \stdClass();
                $mail->firstname = $user->firstname;
                $mail->name = $exam->description;
                $mail->id = $exam->id;
                $mail->date = $exam->pretty_date;
                $mail->duration = $exam->pretty_duration;
                $mail->location = $exam->location->name;
                $mail->centre_name = Centre::find($exam->centre_id)->name;
                $mail->lead = $exam->invigilators_lead_req;
                $mail->extra = $exam->invigilators_req;
                Mail::to($user->email)->send(new DefaultEmail($mail));
            }
        }

        return redirect()->route('exams.show', $exam)->with('success', 'Exam created successfully');
    }







    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storebulk(Request $request)
    {
        $request['centre_id'] = session('centre')->id;

            $this->validate(request(), [
                'description.*' => 'required|min:1',
                'date.*' => 'required|date',
                'exam_location_id.*' => 'required|integer|min:1',
                'duration.*' => 'required|date_format:H:i',
                'invigilators_lead_req.*' => 'sometimes|integer|min:0',
                'invigilators_req.*' => 'sometimes|integer|min:0',
                'students.*' => 'sometimes|integer|min:0',
                'hide_names.*' => 'required|integer|min:0',
                'state.*' => 'required|integer',
                'notes.*' => 'sometimes'
            ]);

        foreach(range(1,$request->total) as $i){

            //if(!@$request->email[$i]){ $email[$i] = '0';}

            $exam = new Exam;
            $exam->description = $request->description[$i];
            $exam->date = date('Y-m-d H:i:s', strtotime($request->date[$i]));
            $exam->duration = date('H:i:s', strtotime($request->duration[$i]));
            $exam->author_id = auth()->user()->id;
            $exam->season_id = session('season')->id;
            $exam->location_id = $request->exam_location_id[$i];
            $exam->invigilators_lead_req = $request->invigilators_lead_req[$i];
            $exam->invigilators_req = $request->invigilators_req[$i];
            $exam->hide_names = $request->hide_names[$i];
            $exam->notes = $request->notes[$i];
            $exam->students = $request->students[$i];
            $exam->state = $request->state[$i];
            $exam->centre_id = $request->centre_id;
            $exam->save();

            if ($request->state[$i] == 1) {
                addToTimeline(0, $exam->author_id, $exam->id,session('centre')->id, session('season')->id,
                    User::find($exam->author_id)->full_name . " created a new exam: <a href='" . url('/') . "/exams/" . $exam->id . "'>" . $exam->description . "</a>");
            }

            if (@$request->email[$i] == 1) {
                foreach (User::role('Invigilator')->where('lastname', 'Wootton')->where('centre_id', session('centre')->id)->get() as $user) {
                    $mail = new \stdClass();
                    $mail->firstname = $user->firstname;
                    $mail->name = $exam->description;
                    $mail->id = $exam->id;
                    $mail->date = $exam->pretty_date;
                    $mail->duration = $exam->pretty_duration;
                    $mail->location = $exam->location->name;
                    $mail->centre_name = Centre::find($exam->centre_id)->name;
                    $mail->lead = $exam->invigilators_lead_req;
                    $mail->extra = $exam->invigilators_req;
                    Mail::to($user->email)->send(new DefaultEmail($mail));
                }
            }
        }

        return redirect()->route('exams.index')->with('success', $request->total. ' exams created successfully');
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Exam $exam)
    {if((auth()->user()->hasRole('Centre Admin')||auth()->user()->hasRole('Super Admin'))||auth()->user()->hasRole('Invigilator')&&$exam->state==1){
        //Only show users who don't have participation and are in current centre
        $users = User::role('Invigilator')->whereDoesntHave('participations', function($q) use ($exam){
            $q->where('exam_id', $exam->id);
        })
            ->where('centre_id', $exam->centre_id)
            ->get();

        $include_icon_camera = 1;

        //Get assigned invigilators
        $lead_invigilators = $exam->participations_lead();
        $invigilators = $exam->participations_extra();

        //Is current user signed up on exam?
        if(Participation::where('user_id', auth()->user()->id)->where('exam_id', $exam->id)->exists()) {
            $signed_up = 1;
        }else{
            $signed_up = 0;
        }

        if($exam->state==1){ $s = "<span class='text-primary'>Live</span>"; }else {$s = "<span class='text-danger'>Draft</span>"; }
        $title = "[" . $s . "] " . $exam->description . " - " . $exam->pretty_date;
        $subtitle = "Full information for this exam is found on this page.";
        return view('exams.show', compact('exam', 'users', 'include_icon_camera', 'lead_invigilators', 'invigilators', 'title', 'subtitle', 'signed_up'));

    }else{abort('403');}}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Exam $exam)
    {if(auth()->user()->hasRole('Super Admin')||$exam->centre_id==auth()->user()->centre_id){

        $locations = Location::orderBy('name')->where('centre_id', session('centre')->id)->get();
        $centres = Centre::all();

        $include_icon_camera = 1;

        $title = $exam->description . " - Edit";
        $subtitle = "Edit this exam.";
        $type = "Edit";
        return view('exams.edit', compact('exam', 'locations', 'centres', 'include_icon_camera', 'title', 'subtitle', 'type'));

    }else{abort('403');}}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Exam $exam)
    {if(auth()->user()->hasRole('Super Admin')||$exam->centre_id==auth()->user()->centre_id){

        if(auth()->user()->hasRole('Super Admin')){}else{
            $request->centre_id = auth()->user()->centre_id;
        }


        $this->validate(request(),[
            'description' => 'required|min:1',
            'date' => 'required|date',
            'exam_location_id' => 'required|integer|min:0',
            'duration' => 'required|date_format:H:i',
            'invigilators_lead_req' => 'sometimes|integer|min:0',
            'invigilators_req' => 'sometimes|integer|min:0',
            'hide_names' => 'required|integer|min:0',
            'centre_id' => 'sometimes|integer|min:0',
            'state' => 'numeric',
            'students' => 'sometimes|integer|min:0',
            'notes' => 'sometimes'
        ],[
            'date.required' => 'Please enter a date',
            'date.date' => 'Please enter a valid date',
            'duration.required' => 'Please enter a duration',
            'duration.date_format:H:i' => 'Please enter a valid duration',
        ]);

        //Notify of discrepancy
        if($exam->participations_lead()->count() > $request->invigilators_lead_req){
            return redirect()->route('exams.show', $exam)->with('error', 'There are ' . $exam->participations_lead()->count() . ' lead invigilator(s) assigned to this exam. You can\'t change the requirement to ' . $request->invigilators_lead_req . ' until you remove some lead invigilators.');
        }
        if($exam->participations_extra()->count() > $request->invigilators_req){
            return redirect()->route('exams.show', $exam)->with('error', 'There are ' . $exam->participations_extra()->count() . ' invigilator(s) assigned to this exam. You can\'t change the requirement to ' . $request->invigilators_req . ' until you remove some invigilators.');
        }

        if($request->state==1) {
            //Get changes
            $changes = "";
            if ($exam->description !== $request->description) {
                $changes = $changes . "<br>&bull; Name of the exam changed to " . $request->description;
            }
            if (Carbon::parse($exam->date)->format('l jS F Y (g:ia)') !== Carbon::parse($request->date)->format('l jS F Y (g:ia)')) {
                $changes = $changes . "<br>&bull; Date changed from " . $exam->pretty_date . " to " . Carbon::parse($request->date)->format('l jS F Y (g:ia)');
            }
            if (substr($exam->duration, 0, -3) !== $request->duration) {
                $changes = $changes . "<br>&bull; Duration changed from " . $exam->pretty_duration . " to " . $request->duration;
            }
            if (intval($exam->location_id) !== intval($request->exam_location_id)) {
                $changes = $changes . "<br>&bull; Location changed from " . $exam->location->name . " to " . Location::find($request->exam_location_id)->name;
            }
            if (intval($exam->invigilators_lead_req) !== intval($request->invigilators_lead_req)) {
                $changes = $changes . "<br>&bull; Lead invigilators changed from " . $exam->invigilators_lead_req . " to " . $request->invigilators_lead_req;
            }
            if ($exam->invigilators_req !== intval($request->invigilators_req)) {
                $changes = $changes . "<br>&bull; Required invigilators changed from " . $exam->invigilators_req . " to " . $request->invigilators_req;
            }
            //End changes

            if (@$changes !== "") {
                addToTimeline(0, $exam->author_id, $exam->id,session('centre')->id, session('season')->id,
                    User::find($exam->author_id)->full_name . " edited some details on the following exam: <a href='" . url('/') . "/exams/" . $exam->id . "'>" . $exam->description . "</a>" . $changes);
            }
        }

        if($request->email==1){
            foreach (User::role('Invigilator')->where('lastname', 'Wootton')->where('centre_id', session('centre')->id)->get() as $user){
                $mail = new \stdClass();
                $mail->firstname = $user->firstname;
                $mail->name = $exam->description;
                $mail->id = $exam->id;
                $mail->date = $exam->pretty_date;
                $mail->duration = $exam->pretty_duration;
                $mail->location = $exam->location->name;
                $mail->centre_name = Centre::find($exam->centre_id)->name;
                $mail->lead = $exam->invigilators_lead_req;
                $mail->extra = $exam->invigilators_req;
                $mail->changes = $changes;
                Mail::to($user->email)->send(new DefaultEmailExamUpdate($mail));
            }
        }

        $exam->description = $request->description;
        $exam->date = date('Y-m-d H:i:s', strtotime($request->date));
        $exam->duration = date('H:i:s', strtotime($request->duration));
        $exam->season_id = session('season')->id;
        $exam->invigilators_lead_req = $request->invigilators_lead_req;
        $exam->invigilators_req = $request->invigilators_req;
        $exam->centre_id = $request->centre_id;
        $exam->hide_names = $request->hide_names;
        $exam->notes = $request->notes;
        $exam->students = $request->students;
        $exam->state = $request->state;
        $exam->location_id = $request->exam_location_id;
        $exam->save();

        return redirect()->route('exams.show', $exam)->with('success', 'Exam edited successfully');


    }else{abort('403');}}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Exam $exam)
    {if(auth()->user()->hasRole('Super Admin')||$exam->centre_id==auth()->user()->centre_id){

        $exam->participations()->delete();
        $exam->delete();

        // Remove referencse from timeline
        Timeline::where('exam_id', $exam->id)->delete();
        // Add to timeline
        addToTimeline(0, $exam->author_id, $exam->id, session('centre')->id, session('season')->id,
            User::find($exam->author_id)->full_name." deleted the following exam: <strong>" . $exam->description . "</strong>");

        foreach (Participation::where('exam_id', $exam->id)->get() as $participation){
            $user = User::find($participation->user_id);

            $mail = new \stdClass();
            $mail->firstname = $user->firstname;
            $mail->name = $exam->description;
            $mail->id = $exam->id;
            $mail->date = $exam->pretty_date;
            $mail->duration = $exam->pretty_duration;
            $mail->location = $exam->location->name;
            $mail->centre_name = Centre::find($exam->centre_id)->name;
            $mail->lead = $exam->invigilators_lead_req;
            $mail->extra = $exam->invigilators_req;

            Mail::to($user->email)->send(new DefaultEmailExamDelete($mail));

        }

        return redirect()->route('exams.index')->with('success', 'Exam deleted successfully');

    }else{abort(403);}}




}
