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
        //Auth::logout();
        if (@$request->user) {
            $user = User::findOrFail($request->user);
        } else {
            $user = Auth::user();
        }

        if((auth()->user()->hasRole('Super Admin')||auth()->user()->centre_id==$user->centre_id)||($user->id==auth()->user()->id)){

            $exams_table = \App\Exam::orderBy('date','DESC')
                ->join('participations','exams.id','=','participations.exam_id')
                ->where('participations.user_id', $user->id)
                ->when(session('season')->id, function ($query) {
                    return $query->where('exams.season_id', session('season')->id);
                })
                ->when(session('centre')->id, function ($query) {
                    return $query->where('exams.centre_id',  session('centre')->id);
                })
                ->get('exams.*');

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
                if(Participation::where('exam_id', $exam->id)->where('user_id', $user->id)->exists()){
                    $highlight = 'orange';
                    $registered =  ' (Registered)';
                }else{
                    $highlight = 'blue';
                    $registered =  '';
                }
                if((auth()->user()->id!==$user->id&&$registered!=="")||(auth()->user()->id==$user->id&&(@$registered||$exam->state==1))) {
                    $new_exam =
                        array(
                            'customData' => array(
                                'id' => $exam->id
                            ),
                            'bar' => $highlight,
                            'popover' => array(
                                'label' => $exam->description . ' - ' . $exam->pretty_time . ' - ' . $exam->pretty_duration . ' - ' . $exam->location->name . @$registered,
                            ),
                            'dates' => $exam->date,

                        );
                    array_push($data, $new_exam);
                }
            }


        if(auth()->user()->hasRole('Invigilator')){
            $timelines = Timeline::orderBy('id', 'DESC')
                ->where(function ($query) {
                    return $query
                        ->where('user_id', 0)
                        ->orWhere('user_id', auth()->user()->id);
                })
                ->when(session('season')->id, function ($query) {
                    return $query->where('season_id', session('season')->id);
                })
                ->when(session('centre')->id, function ($query) {
                    return $query->where('centre_id', session('centre')->id);
                })
                ->get();
            $title = $user->firstname . "'s Dashboard";
            $subtitle = "A timeline and an overview of exams";
         }else{
            $timelines = "";
            $title = $user->full_name . "'s Dashboard";
            $subtitle = "An overview of ".$user->firstname."'s exams";
        }

        $include_icon_create = 1;

        return view('dashboard', compact('exams_table', 'include_icon_create', 'user', 'title', 'subtitle', 'data', 'timelines', 'exams'));

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
            if($exam->live){
                $highlight = 'blue';
                $registered =  ' (Live)';
            }else{
                $highlight = 'red';
                $registered =  ' (Draft)';
            }
            $new_exam =
                array(
                    'customData' => array(
                        'id' => $exam->id
                    ),
                    'bar' => $highlight,
                    'popover' => array(
                        'label' => $exam->description . ' - ' . $exam->pretty_time . ' - ' . $exam->pretty_duration . ' - ' . $exam->location->name . @$registered,
                    ),
                    'dates' => $exam->date,

                );
            array_push($data, $new_exam);
        }

        $timelines = Timeline::orderBy('id', 'DESC')
            ->when(session('season')->id, function ($query) {
                return $query->where('season_id', session('season')->id);
            })
            ->when(session('centre')->id, function ($query) {
                return $query->where('centre_id',  session('centre')->id);
            })
            ->get();

        $title = "Dashboard - Centre Admin";
        $subtitle = "A timeline and an overview of your exams";

        $include_icon_create = 1;
        return view('dashboard-centre', compact('title', 'subtitle', 'timelines', 'data', 'include_icon_create'));
    }

    public function superadmin_index(Request $request)
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
            if($exam->state==0){
                $highlight = 'red';
                $registered =  ' (Draft)';
            }else{
                $highlight = 'blue';
                $registered =  ' (Live)';
            }
            $new_exam =
                array(
                    'customData' => array(
                        'id' => $exam->id
                    ),
                    'bar' => $highlight,
                    'popover' => array(
                        'label' => $exam->description . ' - ' . $exam->pretty_time . ' - ' . $exam->pretty_duration . ' - ' . $exam->location->name . @$registered,
                    ),
                    'dates' => $exam->date,

                );
            array_push($data, $new_exam);
        }

        $timelines = Timeline::orderBy('id', 'DESC')
            ->when(session('season')->id, function ($query) {
                return $query->where('season_id', session('season')->id);
            })
            ->when(session('centre')->id, function ($query) {
                return $query->where('centre_id',  session('centre')->id);
            })
            ->get();

        $title = "Dashboard - Super Admin";
        $subtitle = "A timeline and an overview of your exams";

        $include_icon_create = 1;

        return view('dashboard-superadmin', compact('title', 'exams', 'subtitle', 'timelines', 'data', 'include_icon_create'));
    }

}
