@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 float-left">
            <h1>
                @if(!session('centre'))
                    All Centres
                @else
                    {{session('centre')->name}}
                @endif
            </h1>
            <p>Here is your administration dashboard.</p>
        </div>
    </div>
    @if(!$exams->isEmpty())
        @include('inc.dashboard-data')
        @include('inc.exams-table',
                    ['tableheaders' => [
                        'date' => true,
                        'activity' => true,
                        'lead' => true,
                        'duration' => true,
                        'attendees' => true,
                        'data' => true,
                        'actions' => true
                        ],
                    'headers' => [
                        'title' => true,
                        'subtitle' => 'A list of recent exams'
                        ],
                ])

    @else
        <div>There exams to report on yet</div>
    @endif
</div>
@endsection

