@extends('layouts.app')

@section('content')
    <div class="container">
        @include('inc.titles')
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
                'title' => @session('centre')->name ?? 'All',
                'subtitle' => 'A list of exams',
                'create' => true
            ],
            'filter' => [
                'location' => true,
                'notes' => true,
             ],
             'config' => [
                 'date_range' => true,
            ]
        ])
    </div>
@endsection
