@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>
            Change Season @role('Super Admin') &amp; Centre @endrole
        </h1>
        <div class="row mb-2">
            <div class="col-md-12">
                <p>Change the app-wide season @role('Super Admin') &amp; centre @endrole  </p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h6>
                                    Season @role('Super Admin') &amp; Centre @endrole
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <form action="{{ route('season-change') }}" method="get">
                                <label for="change_season_id"><strong>Season:</strong> </label>
                                <select class="form-control d-inline-block" id="change_season_id" name="change_season_id" style="width:100% !important" onchange="submit()">
                                    @foreach(App\Season::all() as $season)
                                        <option
                                            @if($season->id==@session('season')->id)
                                            selected
                                            @endif
                                            value="{{ $season->id }}">
                                            {{ $season->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            @role('Super Admin')
                                <form action="{{ route('centre-change') }}" method="get" class="mt-3 mb-3">
                                    <label for="change_centre_id"><strong>Centre:</strong></label>
                                    <select class="form-control d-inline-block" id="change_centre_id" name="change_centre_id" style="width:100% !important" onchange="submit()">
                                        <option value="0">All</option>
                                        <option value="0" disabled></option>
                                        @foreach(App\Centre::all() as $centre)
                                            <option
                                                @if($centre->id==@session('centre')->id)
                                                selected
                                                @endif
                                                value="{{ $centre->id }}">
                                                {{ $centre->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripting')
    <script>


    </script>
@endpush
