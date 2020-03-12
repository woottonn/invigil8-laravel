@extends('layouts.app')

@section('content')
<div class="container">
    <p><a href="{{ route('permissions.index') }}">« Back to permissions list</a></p>
    <h1 class="mb-3">Create Permission</h1>
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('permissions.store') }}" method="post">
                    @include('permissions.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

