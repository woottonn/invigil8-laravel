@extends('layouts.app')
@section('content')
    <div class="container">
        @include('inc.titles')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>
                                    @if(!session('centres')->isEmpty())
                                        {{session('centre')->name}}
                                    @endif
                                </h4>
                                A list of all users
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('users.create') }}">
                                    <button class="btn btn-primary float-right">Create User</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if(!$users->isEmpty())
                                    <div id="loader" class="loader"><img class="pt-5 pb-5" src="{{asset('img/loader.gif')}}"></div>
                                     <table id="users" class="table table-bordered table-hover" style="width:100%;display:none">
                                        <thead>
                                            <tr>
                                                <th class="select-filter">Name</th>
                                                <th>Email</th>
                                                <th class="select-filter">Roles</th>
                                                @can('USERS-edit')
                                                    <th width="90" style="text-align:center">Actions</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('users.show', $user->id) }}">
                                                        {{ $user->full_name }}
                                                    </a>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if(count($user->roles))
                                                        @foreach($user->roles as $role)
                                                            <div class="d-inline-block">
                                                                &bull;&nbsp;{{ $role->name }} &nbsp
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        None
                                                    @endif
                                                </td>
                                                @can('USERS-edit')
                                                    <td align="center">
                                                        @can('USERS-edit')
                                                            <a href="{{ route('users.edit', [$user]) }}">
                                                                <button class="btn btn-sm btn-success text-white" style="width:33px"  title="Edit" data-toggle="tooltip" data-placement="bottom" rel="tooltip"><i class="fas fa-edit"></i></button>
                                                            </a>                                                        @endcan
                                                        @can('USERS-delete')
                                                        <form action="{{ route('users.destroy', [$user]) }}" method="POST" class="no-form-style d-none" id="delete-form-{{$user->id}}">
                                                            @METHOD('DELETE')
                                                            <button class="btn btn-danger" type="submit">Delete</button>
                                                            @csrf
                                                        </form>
                                                        <button class="btn btn-sm btn-danger confirm-delete text-white" style="width:33px" title="Delete" data-toggle="tooltip" data-placement="bottom" rel="tooltip" data-form-id="delete-form-{{ $user->id }}" data-title="Are you sure?" data-body="This will permanently delete this user and all participations. Be careful!">
                                                            <i class="fas fa-times"></i>
                                                        </button>                                                        @endcan
                                                    </td>
                                                @endcan
                                            </tr>
                                        @endforeach
                                    </table>
                                @else
                                    <p>There are no users in the system.</p>
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
    <script>
        $(document).ready(function(){
            var otable = $('#users').DataTable( {
                responsive: true,
                stateSave: false,
                "lengthMenu": [ [10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"] ],
                "aoColumnDefs": [
                    { 'bSortable': false, 'aTargets': [ -1 ] }
                ],
                'processing': true,
                "pageLength": 25,
                'language': {
                    'loadingRecords': '&nbsp;',
                    'processing': 'Loading...'
                },
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 1, targets: -1 },
                    { "orderable": false, "targets": [ -1 ] },
                ],

                oLanguage: {
                    sLengthMenu: "Show _MENU_",
                },
                "initComplete": function() {
                    $('#loader').hide(1,function(){ $('#users').fadeIn(1); });
                    $.fn.DataTable.ext.pager.numbers_length = 3;
                }
            });


            // Setup - add a text input to each header cell
            $('.select-filter').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control filter" data-state-save="true" placeholder="' + title + '" />');
            });

            // Apply the search
            otable.columns().every(function () {
                var that = this;
                $('input', this.header()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });

            //Stop clicking on input field causing filtering
            $('.filter').on('click', function (e) {
                e.stopPropagation();
            });

        });

    </script>
@endpush
