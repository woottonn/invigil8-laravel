@extends('layouts.app')

@section('content')

    <div class="container">
        @include('inc.titles')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 mt-2">Locations listed for your school</div>
                            <div class="col-md-6">
                                <a href="{{ route('locations.create') }}">
                                    <button class="btn btn-primary float-right">Create location</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if(! $locations->isEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Name</th>
                                                <th width="120" style="text-align:center">Actions</th>
                                            </tr>
                                            @foreach($locations as $location)
                                                <tr>
                                                    <td>{{ $location->name }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('locations.edit', [$location]) }}" data-toggle="tooltip" data-placement="bottom" rel="tooltip" title="Edit">
                                                            <button class="btn btn-sm btn-success text-white" style="width:33px"><i class="fas fa-edit"></i></button>
                                                        </a>
                                                        <form action="{{ route('locations.destroy', [$location]) }}" method="POST" class="no-form-style d-none" id="delete-form-{{ $location->id }}">
                                                            @METHOD('DELETE')
                                                            <button class="btn btn-danger" type="submit">Delete</button>
                                                            @csrf
                                                        </form>
                                                        <button class="btn btn-sm btn-danger text-white confirm-delete pointer-underline ml-1" data-form-id="delete-form-{{ $location->id }}" data-title="Are you sure?" data-body="This will permanently delete this location, all exams and all participations in those exams. Be careful!"   data-toggle="tooltip" data-placement="bottom" rel="tooltip" title="Delete">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @else
                                    <p>There are no activity types in the system.</p>
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
