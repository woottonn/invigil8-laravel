@extends('layouts.app')

@section('content')
    <div class="container">
        @include('inc.titles', ['title' => 'Today\'s exams'])
        @include('inc.exams-table',
            ['tableheaders' => [
                'date' => true,
                'location' => true,
                'invigilators_lead_req' => true,
                'invigilators_req' => true,
                'notes' => true,
                'start_finish' => true,
                'description' => true,
                'students' => true,
             ],
            'headers' => [
                'title' => true,
                'subtitle' => 'A list of exams'
            ],
            'filter' => [
                'location' => true,
                'notes' => true,
             ],
             'config' => [
                 'active_pulsate' => true,
            ]
                ])

    </div>
@endsection

