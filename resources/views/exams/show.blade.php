@extends('layouts.app')
@section('content')
<div class="container">

    @include('inc.titles')

    <div class="mb-3 mt-3">
        <h5>Information</h5>
        <div class="card">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="d-inline-block" style="width:25px"><i class="fas fa-calendar text-dark-blue"></i></div>
                        Date: <strong>{{$exam->pretty_date}}</strong>
                    </li>
                    <li class="list-group-item">
                        <div class="d-inline-block" style="width:25px"><i class="fas fa-clock text-blue"></i></div>
                        Duration: <strong>{{$exam->pretty_duration}}</strong>
                    </li>
                    <li class="list-group-item">
                        <div class="d-inline-block" style="width:25px"><i class="fas fa-building text-purple"></i></div>
                        Location: <strong>{{$exam->location->name}}</strong>
                    </li>
                    <li class="list-group-item">
                        <div class="d-inline-block" style="width:25px"><i class="fas fa-users text-danger"></i></div>
                        Lead invigilators required: <strong>{{$exam->invigilators_lead_req}}</strong>
                    </li>
                    <li class="list-group-item">
                        <div class="d-inline-block" style="width:25px"><i class="fas fa-users text-green"></i></div>
                        Invigilators required: <strong>{{$exam->invigilators_req}}</strong>
                    </li>
                    <li class="list-group-item">
                        <div class="d-inline-block" style="width:25px"><i class="fas fa-user text-grey"></i></div>
                        Number of students: <strong>{{$exam->students}}</strong>
                    </li>
                    @if($exam->notes)
                        <li class="list-group-item">{{$exam->notes ?? 'None'}}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@if($exam->invigilators_lead_req > 0)
    <div class="mb-3">
        <h5>Lead Invigilators</h5>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <?php
                            foreach(range(1,$exam->invigilators_lead_req) as $index) {
                            ?>
                            @if(@!$lead_invigilators[$index-1])
                                @if(!@$shown)
                                    @can('EXAMS-assign')
                                        <form class="col-md-6 col-lg-4 col-xl-3" action="{{ route('participations.store') }}" method="post">
                                            <div class="form-group">
                                                <label for="user_id">Lead Invigilator {{$index}}</label>
                                                <select class="form-control" name="user_id" id="invigilators_lead_req_{{$index}}" style="width:100%">
                                                    @if(!$users->isEmpty())
                                                        <option>Select a lead invigilator</option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}">
                                                                {{ $user->full_name }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option>No users available</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <input type="hidden" name="exam_id" value="{{$exam->id}}">
                                            <input type="hidden" name="participation_type" value="1">
                                            @csrf
                                        </form>
                                    @elsecan('EXAMS-signup')
                                        @if($signed_up==0)
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <form action="{{ route('participations.store') }}" method="post">
                                                <label>Lead Invigilator {{$index}}</label>
                                                <button class="invigilator_signup" type="submit">
                                                    Sign-up to this slot
                                                </button>
                                                <input type="hidden" name="exam_id" value="{{$exam->id}}">
                                                <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                                                <input type="hidden" name="participation_type" value="1">
                                                @csrf
                                            </form>
                                        </div>
                                        @else
                                            <div class="col-md-6 col-lg-4 col-xl-3">
                                                <label>Lead Invigilator {{$index}}</label>
                                                <div class="invigilator_greyed">
                                                    Available slot
                                                </div>
                                            </div>
                                        @endif
                                    @endcan
                                    <?php $shown = 1; ?>
                                @else
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label>Lead Invigilator {{$index}}</label>
                                        <div class="invigilator_greyed">
                                            Available slot
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label>Lead Invigilator {{$index}}</label>
                                    <div class="@if(auth()->user()->id==$lead_invigilators[$index-1]->user_id) invigilator_me @else invigilator_assigned @endif">
                                        <span title="@if($lead_invigilators[$index-1]->author_id==$lead_invigilators[$index-1]->user_id)
                                              This user signed up to this exam
                                              @else
                                              This user was manually assigned by the admin
                                            @endif
                                            " data-toggle="tooltip" data-placement="bottom" rel="tooltip">
                                            @if($lead_invigilators[$index-1]->author_id==$lead_invigilators[$index-1]->user_id)
                                                <i class="fa fa-user"></i>
                                            @endif

                                            @if($exam->hide_names==1&&auth()->user()->id!==$lead_invigilators[$index-1]->user_id&&auth()->user()->cannot('EXAMS-edit'))
                                                Anonymous</span>
                                            @else
                                                {{App\User::find($lead_invigilators[$index-1]->user_id)->full_name}}</span>
                                            <a href="{{ route('users.show', [$lead_invigilators[$index-1]->user_id]) }}" class="text-white">
                                                <i class="fas fa-info-circle text-white" style="cursor:pointer" title="View {{App\User::find($lead_invigilators[$index-1]->user_id)->firstname}}'s dashboard" data-toggle="tooltip" data-placement="bottom" rel="tooltip"></i>
                                            </a>
                                            @endif


                                        <form action="{{ route('participations.destroy', [$lead_invigilators[$index-1]]) }}" method="POST" class="no-form-style d-none" id="delete-participation-form-{{ $lead_invigilators[$index-1]->user_id }}">
                                            @METHOD('DELETE')
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                            @csrf
                                        </form>
                                        @if(auth()->user()->id==$lead_invigilators[$index-1]->user_id||auth()->user()->can('EXAMS-assign'))
                                            <button class="btn btn-sm btn-danger confirm-delete text-whit float-right" style="width: 24px;padding: 2px;" title="Delete" data-toggle="tooltip" data-placement="bottom" rel="tooltip" data-form-id="delete-participation-form-{{ $lead_invigilators[$index-1]->user_id }}" data-title="Are you sure?" data-body="This will remove @if(auth()->user()->id==$lead_invigilators[$index-1]->user_id) you @else this invigilator @endif from this exam.">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <?php
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($exam->invigilators_req > 0)
    <?php $shown = ""; ?>
    <div class="mb-3">
        <h5>Invigilators</h5>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <?php
                            foreach(range(1,$exam->invigilators_req) as $index) {
                            ?>
                            @if(@!$invigilators[$index-1])
                                @if(@!$shown)
                                    @can('EXAMS-assign')
                                        <form class="col-md-6 col-lg-4 col-xl-3" action="{{ route('participations.store') }}" method="post">
                                            <div class="form-group">
                                                <label for="user_id">Invigilator {{$index}}</label>
                                                <select class="form-control @error('session_location_id') is-invalid @enderror" name="user_id" id="invigilators_req_{{$index}}" style="width:100%">
                                                    @if(!$users->isEmpty())
                                                        <option>Select an invigilator</option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}">
                                                                {{ $user->full_name }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option>No users available</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <input type="hidden" name="exam_id" value="{{$exam->id}}">
                                            <input type="hidden" name="participation_type" value="0">
                                            @csrf
                                        </form>
                                    @elsecan('EXAMS-signup')
                                        @if($signed_up==0)
                                            <div class="col-md-6 col-lg-4 col-xl-3">
                                                <form action="{{ route('participations.store') }}" method="post">
                                                    <label>Invigilator {{$index}}</label>
                                                    <button class="invigilator_signup" type="submit">
                                                        Sign-up to this slot
                                                    </button>
                                                    <input type="hidden" name="exam_id" value="{{$exam->id}}">
                                                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                                                    <input type="hidden" name="participation_type" value="0">
                                                    @csrf
                                                </form>
                                            </div>
                                        @else
                                            <div class="col-md-6 col-lg-4 col-xl-3">
                                                <label>Invigilator {{$index}}</label>
                                                <div class="invigilator_greyed">
                                                    Available slot
                                                </div>
                                            </div>
                                        @endif
                                    @endcan
                                    <?php $shown = 1; ?>
                                @else
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <label>Invigilator {{$index}}</label>
                                        <div class="invigilator_greyed">
                                            Available slot
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label>Lead Invigilator {{$index}}</label>
                                    <div class="@if(auth()->user()->id==$invigilators[$index-1]->user_id) invigilator_me @else invigilator_assigned @endif">
                                        <span title="@if($invigilators[$index-1]->author_id==$invigilators[$index-1]->user_id)
                                            This user signed up to this exam
                                            @else
                                            This user was manually assigned by the admin
                                            @endif
                                            " data-toggle="tooltip" data-placement="bottom" rel="tooltip">
                                            @if($invigilators[$index-1]->author_id==$invigilators[$index-1]->user_id)
                                                <i class="fa fa-user"></i>
                                            @endif

                                            @if($exam->hide_names==1&&auth()->user()->id!==$invigilators[$index-1]->user_id&&auth()->user()->cannot('EXAMS-edit'))
                                                Anonymous</span>
                                        @else
                                        {{App\User::find($invigilators[$index-1]->user_id)->full_name}}</span>
                                        <a href="{{ route('users.show', [$invigilators[$index-1]->user_id]) }}" class="text-white">
                                            <i class="fas fa-info-circle text-white" style="cursor:pointer" title="View {{App\User::find($invigilators[$index-1]->user_id)->firstname}}'s dashboard" data-toggle="tooltip" data-placement="bottom" rel="tooltip"></i>
                                        </a>
                                        @endif

                                        </span>

                                        <form action="{{ route('participations.destroy', [$invigilators[$index-1]]) }}" method="POST" class="no-form-style d-none" id="delete-participation-form-{{ $invigilators[$index-1]->user_id }}">
                                            @METHOD('DELETE')
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                            @csrf
                                        </form>
                                        @if(auth()->user()->id==$invigilators[$index-1]->user_id||auth()->user()->can('EXAMS-assign'))
                                            <button class="btn btn-sm btn-danger confirm-delete text-whit float-right" style="width: 24px;padding: 2px;" title="Delete" data-toggle="tooltip" data-placement="bottom" rel="tooltip" data-form-id="delete-participation-form-{{ $invigilators[$index-1]->user_id }}" data-title="Are you sure?" data-body="This will remove @if(auth()->user()->id==$invigilators[$index-1]->user_id) you @else this invigilator @endif from this exam.">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <?php
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

    @can('EXAMS-edit')
        <div class="float-right">
            <a href="{{ route('exams.edit', [$exam]) }}">
                <button class="btn btn-sm btn-success text-white" style="width:33px" title="Edit" data-toggle="tooltip" data-placement="bottom" rel="tooltip"><i class="fas fa-edit"></i></button>
            </a>
            <form action="{{ route('exams.destroy', [$exam]) }}" method="POST" class="no-form-style d-none" id="delete-form-{{ $exam->id }}">
                @METHOD('DELETE')
                <button class="btn btn-danger" type="submit">Delete</button>
                @csrf
            </form>
            <button class="btn btn-sm btn-danger confirm-delete text-white" style="width:33px" title="Delete" data-toggle="tooltip" data-placement="bottom" rel="tooltip" data-form-id="delete-form-{{ $exam->id }}" data-title="Are you sure?" data-body="This will permanently delete this session and all participations. Be careful!">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endcan
</div>
@endsection

@push('scripting')

    @include('inc.modals.confirm')
    <script>
        <?php foreach(range(1,$exam->invigilators_lead_req) as $index) { ?>
            $('#invigilators_lead_req_{{$index}}').select2({
                placeholder: 'Select an option'
            });
            $('#invigilators_lead_req_{{$index}}').on('change', function() {
                this.form.submit();
            });
        <?php } ?>

        <?php foreach(range(1,$exam->invigilators_req) as $index) { ?>
            $('#invigilators_req_{{$index}}').select2({
                placeholder: 'Select an option'
            });
            $('#invigilators_req_{{$index}}').on('change', function() {
                this.form.submit();
            });
        <?php } ?>

        var otable = $('#participations').DataTable({
            responsive: true,
            stateSave: false,
            "lengthMenu": [[10, 25, 50, 100, 200 - 1], [10, 25, 50, 100, 200, "All"]],
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [-1]},
            ],
            'processing': true,
            "pageLength": 25,
            'language': {
                'loadingRecords': '&nbsp;',
                'processing': 'Loading...'
            },
            'columnDefs': [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 1, targets: -1},
                {orderable: false, "targets": [-1]},
            ],

            'oLanguage': {
                sLengthMenu: "Show _MENU_",
            },
            "initComplete": function () {
                $('#loader').hide(1, function () {
                    $('#participations').show(1);
                });

            }
        });


    </script>
@endpush


