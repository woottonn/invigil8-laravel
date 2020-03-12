@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Today's sessions</h1>
        <div class="row mb-2">
            <div class="col-md-6">
                <p>A list of all today's sessions.</p>
            </div>
        </div>

        @include('inc.exams-table',
                    ['tableheaders' => [
                        'date' => true,
                        'start_finish' => true,
                        'activity' => true,
                        'lead' => true,
                        'duration' => true,
                        'attendees' => false,
                        'attendees_full' => true,
                        'data' => true,
                        'actions' => true
                        ],
                    'headers' => [
                        'title' => true,
                        'subtitle' => 'A list of todays exams'
                        ],
                    'config' => [
                        'active_pulsate' => true,
                        ],
                    'filter' => [
                        'activity' => true,
                        'lead' => true,
                        'attendees_full' => true,
                        ],
                ])

    </div>
@endsection

