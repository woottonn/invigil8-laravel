@extends('layouts.app')

@section('content')
<div class="container">
    <p><a href="{{ route('roles.index') }}">« Back to role list</a></p>
    <h1 class="mb-3">Edit Role</h1>
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('roles.update', [$role]) }}" method="post">
                    @method('PATCH')
                    @include('roles.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

