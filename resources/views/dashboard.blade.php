@extends('layouts.app')
@section('content')
    <div class="container">
        @include('inc.titles')
        <?php
        $end = explode('-', config('sitevars.seasons')[session('season')->name]['date_end']);
        $start = explode('-', config('sitevars.seasons')[session('season')->name]['date_start']);
        ?>
        <vue-calendar
            :exams="{{ json_encode($data) }}"
            :screenscol="$screens({ default: 1, md: 2, lg: 3, xl: 4 })"
            :screensrow="$screens({ default: 1})"
            :theend="{{ json_encode($end) }}"
            :thestart="{{ json_encode($start) }}"
        >
        </vue-calendar>
        <?php $exams = @$exams_table; ?>
        @can('EXAMS-edit')
            <div class="mt-4"></div>
            @include('inc.exams-table',
            ['tableheaders' => [
                'date' => true,
                'state' => true,
                'location' => true,
                'invigilators_lead_req' => true,
                'invigilators_req' => true,
                'notes' => true,
                'duration' => true,
                'description' => true,
                'students' => true,
                'actions' => true
             ],
            'headers' => [
                'title' => true,
                'subtitle' => 'A list of '.$user->firstname.'\'s registered exams'
            ],
            'filter' => [
                'location' => true,
                'notes' => true,
             ],
                ])
        @endcan
        @include('inc.timeline')

    </div>
@endsection
