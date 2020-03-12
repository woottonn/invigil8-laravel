@extends('layouts.app')
@section('content')
<div class="container">
    <p><a href="{{ route('centres.index') }}">« Back to centres list</a></p>
    <h1 class="mb-3">Edit Centre</h1>
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('centres.update', [$centre]) }}" method="post">
                    @method('PATCH')
                    @include('centres.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

