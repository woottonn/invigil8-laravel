@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Welcome to Scan</h1>
        <p>
            Scan is an app to help you keep track of your extra curricular club attendance.
            @guest
                <a class="btn btn-primary btn-lg mt-3 d-block" href="{{ route('login') }}" role="button">Log in</a>
            @else
                <a class="btn btn-primary btn-lg mt-3 d-block" href="{{ route('dashboard') }}" role="button">Click here to access your dashboard</a>
            @endguest
        </p>
        <!--<div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Your runs</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                Run is an app to help you keep track of your Streetly Mile run times.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
@endsection

