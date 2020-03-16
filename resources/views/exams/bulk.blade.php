@extends('layouts.app')
@section('content')
<div class="container">
    @include('inc.titles')
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('exams.storebulk') }}" method="post">
                    @include('exams.formbulk')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

