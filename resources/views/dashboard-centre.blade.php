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
        @include('inc.timeline')
    </div>
@endsection

