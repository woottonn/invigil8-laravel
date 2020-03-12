<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Centre;
use App\Group;
use App\Participation;
use App\Run;
use App\Season;
use App\Exam;
use App\Timeline;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Charts\DashboardLineChart;
use App\Charts\ParticipationBarChart;
use App\Charts\PercentDoughnutChart;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DashboardController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $cID, $sID;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->sID= session('season')->id ?? '';
            $this->cID= session('centre')->id ?? '';

            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (@$request->user) {
            $user = User::findOrFail($request->user);
        } else {
            $user = Auth::user();
        }

        if((auth()->user()->hasRole('Super Admin')||auth()->user()->centre_id==$user->centre_id)||($user->id==auth()->user()->id)){

            $exams = \App\Exam::orderBy('date','DESC')
                ->when(session('season')->id, function ($query) {
                    return $query->where('exams.season_id', session('season')->id);
                })
                ->when(session('centre')->id, function ($query) {
                    return $query->where('exams.centre_id',  session('centre')->id);
                })
                ->get();

            $data = [];
            foreach($exams as $exam){
                if(Participation::where('exam_id', $exam->id)->where('user_id', auth()->user()->id)->exists()){
                    $highlight = 'orange';
                    $registered =  ' (Registered)';
                }else{
                    $highlight = 'blue';
                    $registered =  '';
                }
                $new_exam =
                    array(
                        'customData' => array(
                            'id' => $exam->id
                        ),
                        'bar'=> $highlight,
                        'popover'=> array(
                            'label' => $exam->description . ' - ' . $exam->pretty_time . ' - ' . $exam->pretty_duration . ' - ' . $exam->location->name . @$registered,
                        ),
                        'dates' => $exam->date,

                    );
                array_push($data, $new_exam);
            }

        $timelines = Timeline::orderBy('id', 'DESC')->where('user_id', 0)->orWhere('user_id', auth()->user()->id)->get();

        $title = "Dashboard - Invigilator";
        $subtitle = "Data overview.";
        $include_icon_create = 1;
        return view('dashboard', compact('include_icon_create', 'user', 'title', 'subtitle', 'data', 'timelines'));

    }else{abort('403');}}

    public function centreadmin_index()
    {

        $exams = \App\Exam::orderBy('date','DESC')
            ->when(session('season')->id, function ($query) {
                return $query->where('exams.season_id', session('season')->id);
            })
            ->when(session('centre')->id, function ($query) {
                return $query->where('exams.centre_id',  session('centre')->id);
            })
            ->get();

        $data = [];
        foreach($exams as $exam){
            $new_exam =
                array(
                    'customData' => array(
                        'id' => $exam->id
                    ),
                    'highlight'=> array(
                        'backgroundColor' => "#000"
                    ),
                    'popover'=> array(
                        'label' => $exam->description . ' - ' . $exam->pretty_time . ' - ' . $exam->pretty_duration . ' - ' . $exam->location->name,
                    ),
                    'dates'=> array(
                        'start' => $exam->date,
                        'end' => $exam->date,
                    ),
                );
            array_push($data, $new_exam);
        }

        $timelines = Timeline::orderBy('id', 'DESC')->get();

        $title = "Dashboard - Centre Admin";
        $subtitle = "Overview of your exams and users.";

        $include_icon_create = 1;
        return view('dashboard-centre', compact('title', 'subtitle', 'timelines', 'data', 'include_icon_create'));
    }

    public function superadmin_index(Request $request)
    {

        if(session('centre')->id){ $centre_count = 1; }else{  $centre_count = Centre::where('active', 1)->count(); }

        $participations_count = Participation::
            join('exams','exams.id','=','participations.exam_id')
            ->when($this->sID, function ($query){
                return $query->where('season_id', $this->sID);
            })
            ->when($this->cID, function ($query){
                return $query->where('centre_id', $this->cID);
            })
            ->count();

        $exams = Exam::orderBy('date')
            ->when($this->sID, function ($query){
                return $query->where('season_id', $this->sID);
            })
            ->when($this->cID, function ($query){
                return $query->where('centre_id', $this->cID);
            })
            ->get();
        $exams_count = $exams->count();

        $users_count = User::
            when($this->cID, function ($query){
                return $query->where('centre_id', $this->cID);
            })
            ->count();

        $total_time = Exam::
            selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC( `duration` ))) AS duration')
            ->when($this->sID, function ($query){
                return $query->where('season_id', $this->sID);
            })
            ->when($this->cID, function ($query){
                return $query->where('centre_id', $this->cID);
            })
            ->get();
        $total_time = dashboardTotalDuration($total_time);

        $include_icon_create = 1;

        return view('dashboard-superadmin', compact('exams_count', 'centre_count', 'exams', 'users_count',  'include_icon_create', 'participations_count', 'total_time'));
    }

}
