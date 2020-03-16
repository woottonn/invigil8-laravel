<nav class="navbar navbar-expand-md navbar-dark bg-custom p-1 p-md-3">

    <a class="navbar-brand" href="{{ route('login') }}">
        <img src="/img/logo.png" class="logo" alt="{{env("APP_NAME")}}" title="{{env("APP_NAME")}}">
        <div class="date d-xs-inline-block d-sm-inline-block d-md-none d-md-none d-lg-none d-xl-none">{{ date('l jS F Y') }}</div>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cookies') }}">Privacy &amp Cookie Policy</a>
                </li>
            @else
            <li class="nav-item">
                <a class="nav-link" href="/login">Dashboard</a>
            </li>

            @can('EXAMS-view')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('exams.today') }}">Today's Exams</a>
            </li>
            @endcan

            @role('Invigilator')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('exams.index') }}">Exams</a>
                </li>
            @endrole

            @can('EXAMS-create')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Exams <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('exams.index') }}">View</a>
                            <a class="dropdown-item" href="{{ route('exams.create') }}">Create</a>
                            <a class="dropdown-item" href="{{ route('exams.bulk') }}">Bulk Creation</a>
                        </div>
                    </li>
            @endcan


@endguest
</ul>
<!-- Right Side Of Navbar -->
<ul class="navbar-nav ml-auto">
<!-- Authentication Links -->
@guest
    <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
    </li>
@else
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->full_name }} ({{ @session('season')->name }})
            <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

            <a class="dropdown-item d-xs-block d-sm-block d-md-none d-md-none d-lg-none d-xl-none" href="{{ route('season-centre-change') }}">
                Change Season
                @role('Super Admin')
                    &amp; Centre
                @endrole
            </a>

            @role('Student')
                <a class="dropdown-item" href="{{ route('qr') }}">My QR Code</a>
            @endrole

            <a class="dropdown-item" href="{{ route('cookies') }}">Privacy &amp; Cookie Policy</a>

            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </li>
@endguest
</ul>
</div>
</nav>


