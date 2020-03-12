@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>
            Centres
        </h1>
        <div class="row mb-2">
            <div class="col-md-6">
                <p>A list of all centres within the system.</p>
            </div>
            <div class="col-md-6">
                <a href="{{ route('centres.create') }}">
                    <button class="btn btn-primary float-right">Create Centre</button>
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Centres</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                @if(! $centres->isEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Name</th>
                                                <th>Actions</th>
                                            </tr>
                                            @foreach($centres as $centre)
                                                <tr>
                                                    <td class="bg-light">{{ $centre->name }} ({{ $centre->users->count() }} members)</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('centres.edit', [$centre]) }}" title="Edit" data-toggle="tooltip" data-placement="bottom" rel="tooltip">
                                                            <button class="btn btn-sm btn-success text-white" style="width:33px"><i class="fas fa-edit"></i></button>
                                                        </a>
                                                        <form action="{{ route('centres.destroy', [$centre]) }}" method="POST" class="no-form-style d-none" id="delete-form-{{ $centre->id }}">
                                                            @METHOD('DELETE')
                                                            <button class="btn btn-danger" type="submit">Delete</button>
                                                            @csrf
                                                        </form>
                                                        <button class="btn btn-sm btn-danger confirm-delete text-white" style="width:33px" title="Delete" data-toggle="tooltip" data-placement="bottom" rel="tooltip" data-form-id="delete-form-{{ $centre->id }}" data-title="Are you sure?" data-body="This will permanently delete this centre and all exams. Be careful!">
                                                            <i class="fas fa-times"></i>
                                                        </button>                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center pt-3">
                                        {{$centres->links()}}
                                    </div>
                                @else
                                    <p>There are no centres in the system.</p>
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
