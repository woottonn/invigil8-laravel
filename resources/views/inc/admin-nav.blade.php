<nav class="navbar navbar-light navbar-expand-md p-1 p-md-3 bg-light">
    <a class="navbar-brand"><img src="/img/admin.svg" class="admin-logo" alt="Admin" title="Admin" style="margin-right: 29px;"></a>
    <button class="navbar-toggler float-right" type="button" data-toggle="collapse" data-target="#admin-nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="admin-nav">
        <ul class="navbar-nav mr-auto">
            @can('LOCATIONS-modify')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Locations <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('locations.index') }}">View</a>
                    <a class="dropdown-item" href="{{ route('locations.create') }}">Create</a>
                </div>
            </li>
            @endcan
            @can('USERS-view')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Users <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    @can('USERS-view')
                        <a class="dropdown-item" href="{{ route('users.index') }}">View</a>
                    @endcan
                    @can('USERS-create')
                        <a class="dropdown-item" href="{{ route('users.create') }}">Create</a>
                    @endcan
                </div>
            </li>
            @endcan
            @can('CENTRES-modify')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Centres <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('centres.index') }}">View</a>
                    <a class="dropdown-item" href="{{ route('centres.create') }}">Create</a>
                </div>
            </li>
            @endcan
            @role('Super Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Roles <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('roles.index') }}">View</a>
                        <a class="dropdown-item" href="{{ route('roles.create') }}">Create</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Permissions <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('permissions.index') }}">View</a>
                        <a class="dropdown-item" href="{{ route('permissions.create') }}">Create</a>
                    </div>
                </li>
            @endrole
        </ul>
    </div>
</nav>
