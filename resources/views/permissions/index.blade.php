@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Permissions</h1>
        <div class="row mb-2">
            <div class="col-md-6">
                <p>A list of all permissions within the system.</p>
            </div>
            <div class="col-md-6">
                <a href="{{ route('permissions.create') }}">
                    <button class="btn btn-primary float-right">Create Permission</button>
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Permissions</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                @if(! $permissions->isEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Name</th>
                                                <th>Actions</th>
                                            </tr>
                                            @foreach($permissions as $permission)
                                                <tr>
                                                    <td>{{ $permission->name }}</td>
                                                    <td>
                                                        <a href="{{ route('permissions.edit', [$permission]) }}" class="ml-2" title="Edit"  data-toggle="tooltip" data-placement="bottom" rel="tooltip" title="Edit"><i class="fas fa-edit text-success"></i></a>
                                                        <form action="{{ route('permissions.destroy', [$permission]) }}" method="POST" class="no-form-style d-none" id="delete-form-{{ $permission->id }}">
                                                            @METHOD('DELETE')
                                                            <button class="btn btn-danger" type="submit">Delete</button>
                                                            @csrf
                                                        </form>
                                                        <span class="confirm-delete pointer-underline text-danger ml-1" data-form-id="delete-form-{{ $permission->id }}" data-title="Are you sure?" data-body="This will permanently delete this run type. Be careful!"><i class="fas fa-times text-danger" title="Delete"  data-toggle="tooltip" data-placement="bottom" rel="tooltip" title="Delete"></i></span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @else
                                    <p>There are no permissions in the system.</p>
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
