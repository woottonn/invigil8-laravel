@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>
            Roles
        </h1>
        <div class="row mb-2">
            <div class="col-md-6">
                <p>A list of all roles within the system.</p>
            </div>
            <div class="col-md-6">
                <a href="{{ route('roles.create') }}">
                    <button class="btn btn-primary float-right">Create Role</button>
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Roles</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if(! $roles->isEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Name</th>
                                                <th class="d-none d-md-table-cell">Permissions</th>
                                                <th>Actions</th>
                                            </tr>
                                            @foreach($roles as $role)
                                                <tr>
                                                    <td class="bg-light">{{ $role->name }}</td>
                                                    <td class="d-none d-md-table-cell">
                                                        @if($role->name=="Super Admin")
                                                            All
                                                        @else
                                                            @foreach($role->getAllPermissions() as $permission)
                                                            <div class="d-inline-block">
                                                                &bull; {{ $permission->name }}
                                                            </div>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('roles.edit', [$role]) }}" class="ml-2" title="Edit"  data-toggle="tooltip" data-placement="bottom" rel="tooltip" title="Edit"><i class="fas fa-edit text-success"></i></a>
                                                        <form action="{{ route('roles.destroy', [$role]) }}" method="POST" class="no-form-style d-none" id="delete-form-{{ $role->id }}">
                                                            @METHOD('DELETE')
                                                            <button class="btn btn-danger" type="submit">Delete</button>
                                                            @csrf
                                                        </form>
                                                        <span class="confirm-delete pointer-underline text-danger ml-1" data-form-id="delete-form-{{ $role->id }}" data-title="Are you sure?" data-body="This will permanently delete this role. Be careful!"><i class="fas fa-times text-danger" title="Delete"  data-toggle="tooltip" data-placement="bottom" rel="tooltip" title="Delete"></i></span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @else
                                    <p>There are no roles in the system.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripting')

    @include('inc.modals.confirm')

@endpush
