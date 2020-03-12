<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasRoles, HasRelationships;

    private $cID, $sID;

    public function __construct()
    {
        $this->sID= session('season')->id ?? '';
        $this->cID= session('centre')->id ?? '';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date:Y-m-d',
    ];

    public $appends = ['full_name', 'description'];

    public function getFullNameAttribute() //set full name as full_name
    {
        return ucfirst($this->firstname) . ' ' . ucfirst($this->lastname);
    }

    public function getFullNameInitialAttribute() //set full name with just initial
    {
        return ucfirst($this->firstname[0]) . ' ' . ucfirst($this->lastname);
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function sessions()
    {
        return $this->belongsToMany('App\Exam', 'participations');
    }

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

    public function participations_by_season(){
        return $this->hasMany('App\Participation')
            ->join('exams','participations.session_id','=','exams.id')
            ->when($this->sID, function ($query) {
                return $query->where('exams.season_id', $this->sID);
            });
    }

    public function locations()
    {
        //return $this->hasManyDeep(Activity::class, [Participation::class, Exam::class]);
        return $this->hasManyDeep(
            Activity::class,
            [Participation::class, Exam::class],
            [null, 'id', 'id'],
            [null, 'session_id', 'activity_id']

        )
        ->when($this->sID, function ($query) {
            return $query->where('exams.season_id', $this->sID);
        })
        ->when($this->cID, function ($query) {
            return $query->where('exams.centre_id', $this->cID);
        });
    }

    public function getAuthoredActivitiesAttribute()
    {
        return Activity::
        where('exams.author_id', $this->id)
            ->join('exams','exams.activity_id','=','activities.id')
            ->when($this->sID, function ($query){
                return $query->where('season_id', $this->sID);
            })
            ->get();
    }

    public function getAuthoredExamsAttribute()
    {
        return Activity::
        where('exams.author_id', $this->id)
            ->join('exams','exams.activity_id','=','activities.id')
            ->when($this->sID, function ($query){
                return $query->where('season_id', $this->sID);
            })
            ->get();
    }

    public function getParticipationTotalAttribute()
    {
        return Participation::
        where('participations.user_id', $this->id)
            ->join('exams','exams.id','=','participations.session_id')
            ->when($this->sID, function ($query){
                return $query->where('season_id', $this->sID);
            })
            ->count();
    }

    public function getAuthoredParticipationsAttribute()
    {
        return Participation::
        where('exams.author_id', $this->id)
            ->join('exams','participations.session_id','=','exams.id')
            ->when($this->sID, function ($query){
                return $query->where('season_id', $this->sID);
            })
            ->get();
    }

    public function getTotalDurationHoursAttribute(){

        $total_time =  DB::Table('exams')
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC( `duration` ))) AS duration')
            ->join('participations','exams.id','=','participations.session_id')
            ->where('user_id', $this->id)
            ->when($this->sID, function ($query) {
                return $query->where('exams.season_id', $this->sID);
            })
            ->get();

         if($total_time[0]->duration>0){
             foreach($total_time as $times){ return ltrim(substr($times->duration, 0, -6), 0);  }
         }else{
             return 0;
         }

    }




}
