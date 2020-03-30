@extends('layouts.app')

@section('content')
    <div class="page-error">
        <h1>Sorry!</h1>
        There has been an error on the page. <br><br>
        <a href="{{ route('home') }}"><button class="btn btn-primary">Go back to home</button></a>
    </div>
@endsection
