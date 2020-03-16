<?php

namespace App\Http\Controllers;

use App\Centre;
use App\Http\Controllers\Controller;
use App\Mail\DefaultEmail;
use App\Mail\DefaultEmailInvAdd;
use App\Mail\DefaultEmailInvDelete;
use App\Participation;
use App\Exam;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ParticipationsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    { $exam = Exam::find($request->exam_id);  $user = User::find($request->user_id);
    if(auth()->user()->hasRole('Super Admin')||(auth()->user()->can('EXAMS-assign')&&$exam->centre_id==auth()->user()->centre_id)||($request->user_id==auth()->user()->id&&$exam->centre_id==auth()->user()->centre_id&&auth()->user()->can('EXAMS-signup'))) {

        $this->validate(request(), [
            'user_id' => 'required|numeric',
            'exam_id' => 'required|numeric',
            'participation_type' => 'required|numeric',
        ], [
            'user_id.required' => 'Please enter a user',
            'session_id.required' => 'Please enter a session',
        ]);

        //Is user already assigned? Show error
        if (Participation::where('user_id', '=', $request->user_id)->where('exam_id', '=', $request->exam_id)->exists()) {
            return redirect()->route('exams.show', [$request->exam_id])->with('error', 'This invigilator is already assigned to this exam.');
        }

        //Is this exam full?
        if ($request->participation_type == 1 && $exam->lead_full) {
            return redirect()->route('exams.show', [$request->exam_id])->with('error', 'The lead positions are already filled.');
        }
        if ($request->participation_type == 0 && $exam->extra_full) {
            return redirect()->route('exams.show', [$request->exam_id])->with('error', 'The extra positions are already filled.');
        }

        $participation = new Participation;
        $participation->author_id = auth()->user()->id;
        $participation->exam_id = $request->exam_id;
        $participation->participation_type = $request->participation_type;
        $participation->user_id = $request->user_id;
        $participation->save();

        // Add to timeline
        if($request->participation_type == 1){ $type = " as a lead invigilator "; }else{ $type = " as an invigilator "; }
        if ($participation->user_id==auth()->user()->id){
            addToTimeline($participation->user_id, $participation->author_id, $participation->exam_id,session('centre')->id, session('season')->id,
                "<a href='".url('/') . "/users/" . $participation->user_id . "'>".User::find($participation->user_id)->full_name."</a> signed up to <a href='".url('/') . "/exams/" . $exam->id . "'>" . $exam->description . "</a>" . $type);
        }else{
            addToTimeline($participation->user_id, $participation->author_id, $participation->exam_id,session('centre')->id, session('season')->id,
                User::find($participation->author_id)->full_name . " assigned <a href='".url('/') . "/users/" . $participation->user_id . "'>".User::find($participation->user_id)->full_name."</a> ".$type." to <a href='".url('/') . "/exams/" . $exam->id . "'>" . $exam->description . "</a>");

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
            Mail::to($user->email)->send(new DefaultEmailInvAdd($mail));

        }

        return redirect()->route('exams.show', [$request->exam_id])->with('success', 'Invigilator assigned successfully');
    }else{abort(403);}}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Participation $participation)
    {$exam = Exam::find($participation->exam_id); $user = User::find($participation->user_id);
    if(auth()->user()->hasRole('Super Admin')||(auth()->user()->can('EXAMS-assign')&&$exam->centre_id==auth()->user()->centre_id)||($participation->user_id==auth()->user()->id&&$exam->centre_id==auth()->user()->centre_id&&auth()->user()->can('EXAMS-signup'))) {

        $participation->delete();

        // Add to timeline
        if($participation->participation_type == 1){ $type = " as a lead invigilator "; }else{ $type = " as an invigilator "; }
        if ($participation->user_id==auth()->user()->id){
            addToTimeline($participation->user_id, $participation->author_id, $participation->exam_id,session('centre')->id, session('season')->id,
                "<a href='".url('/') . "/users/" . $participation->user_id . "'>".User::find($participation->user_id)->full_name."</a> removed themselves ".$type." from <a href='".url('/') . "/exams/" . $exam->id . "'>" . $exam->description . "</a>");
        }else{
            addToTimeline($participation->user_id, $participation->author_id, $participation->exam_id,session('centre')->id, session('season')->id,
                User::find($participation->author_id)->full_name . " removed <a href='".url('/') . "/users/" . $participation->user_id . "'>".User::find($participation->user_id)->full_name."</a> ".$type." from <a href='".url('/') . "/exams/" . $exam->id . "'>" . $exam->description . "</a>");

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
                Mail::to($user->email)->send(new DefaultEmailInvDelete($mail));

        }

        return redirect()->back()->with('success','Invigilator deleted successfully');

    }else{abort(403);}}
}
