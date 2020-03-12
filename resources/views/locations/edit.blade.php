@extends('layouts.app')

@section('content')
<div class="container">
    <p><a href="{{ route('locations.index') }}">« Back to location list</a></p>
    @include('inc.titles')
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('locations.update', [$location]) }}" method="post">
                    @method('PATCH')
                    @include('locations.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

