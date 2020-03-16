@guest
@else
<?php

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
    $date = substr($exam->date, 0, -9);
    if(App\Participation::where('exam_id', $exam->id)->where('user_id', auth()->user()->id)->exists()){
        $highlight = 'orange';
        $registered =  ' (Registered)';
    }else{
        $highlight = 'blue';
        $registered =  '';
    }
    $new_exam =
        array(
            'customData' => array(
                'id' => $exam->id,
                'date' => $date
            ),
            'bar'=> $highlight,
            'popover'=> array(
                'label' => $exam->description . ' - ' . $exam->pretty_time . ' - ' . $exam->pretty_duration . ' - ' . $exam->location->name . @$registered,
            ),
            'dates' => $exam->date,

        );
    array_push($data, $new_exam);

    $end = explode('-', config('sitevars.seasons')[session('season')->name]['date_end']);
    $start = explode('-', config('sitevars.seasons')[session('season')->name]['date_start']);
}

?>
<div id="calendar_box">
    <i class="fa fa-times text-danger" id="calendar_close" style="position: fixed;right: 0;top: 0;font-size: 40px;padding:10px;cursor:pointer"></i>
    <div class="box m-2">
        <vue-calendar
            :exams="{{ json_encode($data) }}"
            :screenscol="$screens({ default: 1, md: 2, lg: 3, xl: 4 })"
            :screensrow="$screens({ default: 1, md: 2, lg: 2, xl: 3 })"
            :theend="{{ json_encode(@$end) }}"
            :thestart="{{ json_encode(@$start) }}"
        >
        </vue-calendar>
    </div>
</div>
@endguest
