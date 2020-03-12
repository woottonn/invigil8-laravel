@guest
@else

    <div class="bg-custom season-box">
        @role('Super Admin')
        @else
            <div class="float-left date d-none d-sm-none d-md-none d-md-block d-lg-block d-xl-block mt-1">{{ date('l jS F Y') }}</div>
        @endrole
        @if(!@$hide_centre_and_season)
            @if(@session('season'))
                <div class="form-group float-right d-none d-sm-none d-md-none d-md-block d-lg-block d-xl-block" style="margin: 0;">
                    <form action="{{ route('season-change') }}" method="get">
                        <label for="change_season_id"></label>
                        <select class="form-control d-inline-block" id="change_season_id" name="change_season_id" style="
                    background: #20a7bf !important;
                    color: #fff !important;
                    font-weight:normal !important;
                    width:auto !important" onchange="submit()">
                            <option value="0">All</option>
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
                </div>
            @endif
            @role('Super Admin')
                <div class="form-group float-left d-none d-sm-none d-md-none d-md-block d-lg-block d-xl-block" style="margin: 0;">
                    <form action="{{ route('centre-change') }}" method="get">
                        <label for="change_centre_id"></label>
                        <select class="form-control d-inline-block" id="change_centre_id" name="change_centre_id" style="
                background: #20a7bf !important;
                color: #fff !important;
                font-weight:normal !important;
                width:auto !important" onchange="submit()">
                            <option value="0">All</option>
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
                </div>
            @endrole
        @endif
    </div>

    <div class="icons d-xs-block d-sm-block d-md-none d-md-none d-lg-none d-xl-none">

        <a href="{{route('exams.index')}}" style="text-decoration:none">
            <i class="fas fa-list"></i>
            <div class="mt-1"><strong>Exams</strong></div>
        </a>

        @role('Invigilator')
        <a href="{{route('exams.index', ['user_id' => Auth::user()->id])}}" style="text-decoration:none">
            <i class="fas fa-id-card"></i>
            <div class="mt-1"><strong>My Exams</strong></div>
        </a>
        @endrole

        @if(@$include_icon_create)
            @can('EXAMS-create')
                <a href="{{route('exams.create')}}" style="text-decoration:none;;background: #fff;color: #000;">
                    <i class="fas fa-plus"></i>
                    <div class="mt-1" style="color:#fff"><strong>Create</strong></div>
                </a>
            @endcan
        @endif

        <a href="{{ route('login') }}" style="text-decoration:none">
            <i class="fas fa-tachometer-alt"></i>
            <div class="mt-1"><strong>Dashboard</strong></div>
        </a>
    </div>

@endguest
