@extends('layouts.app')

@section('content')
<div class="container">
    <p><a href="{{ route('users.index') }}">« Back to user list</a></p>
    @include('inc.titles')
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.update', [$user]) }}" method="post" autocomplete="off">
                    @method('PATCH')
                    @include('users.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

