@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Unique QR Code</h1>

    <div class="mb-3">
        <div class="card">
            <div class="card-body" style="text-align:center">
                <img src="{{ $user->qr }}" style="width:100%;max-width:500px">
            </div>
        </div>
    </div>
</div>
@endsection

