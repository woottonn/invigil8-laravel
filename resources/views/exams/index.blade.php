@extends('layouts.app')

@section('content')
    <div class="container">
        @include('inc.titles', ['title' => 'Exams'])
        @include('inc.exams-table',
            ['tableheaders' => [
                'date' => true,
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
                'subtitle' => 'A list of exams'
            ],
            'filter' => [
                'location' => true,
                'notes' => true,
             ],
                ])
    </div>
@endsection
