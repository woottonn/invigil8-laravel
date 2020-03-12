@extends('layouts.app')
@section('content')
    <div class="container">
        @include('inc.titles')
        <vue-calendar
            :exams="{{ json_encode($data) }}"
            :screenscol="$screens({ default: 1, md: 2, lg: 3, xl: 4 })"
            :screensrow="$screens({ default: 1})"
        >
        </vue-calendar>
        @include('inc.timeline')
    </div>
@endsection
