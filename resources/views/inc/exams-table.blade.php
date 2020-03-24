<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        @if(@$headers['title'])
                            <h4>
                                @if(!session('centre'))
                                    All Centres
                                @elseif(@$headers['title'])
                                    {{$headers['title']}}
                                @else
                                    {{session('centre')->name}}
                                @endif
                            </h4>
                        @endif

                        @if(@$date) A list of exams on {{ \Carbon\Carbon::parse($date)->format('l jS F Y') }} -
                            <a href="{{route('exams.index')}}">(Show all)</a>
                        @else
                            @if(@$headers['subtitle']) {{ @$headers['subtitle'] }} @endif
                        @endif
                    </div>
                    @can('EXAMS-create')
                        @if(@$headers['create'])
                            <div class="col-md-6 d-none d-sm-none d-md-none d-md-block d-lg-block d-xl-block">
                                    <a href="{{ route('exams.create') }}">
                                        <button class="btn btn-primary float-right">Create Exam</button>
                                    </a>
                            </div>
                        @endif
                    @endcan
                </div>
                @if(@$config['date_range'])
                    <div class="mt-2 d-none d-md-block">
                        <input
                            type="date"
                            id="min-date"
                            class="date-range-filter form-control d-inline-block w-auto "
                            value="{{ @$_GET['date']  }}"
                            placeholder="From">
                        to
                        <input
                            type="date"
                            id="max-date"
                            class="date-range-filter form-control d-inline-block w-auto"
                            value="{{ @$_GET['date']  }}"
                            placeholder="To">
                    </div>
                @endif
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @if(!$exams->isEmpty())
                            <div id="loader" class="loader"><img class="pt-5 pb-5" src="{{asset('img/loader.gif')}}"></div>

                            <table class="table table-bordered table-hover" id="exams" style="width:100%;display:none">
                                <thead>
                                <tr>
                                    @role('Super Admin')
                                        <th class="select-filter" data-type="Centre">Centre</th>
                                    @endrole
                                    @if(@$tableheaders['description'])
                                        <th @if(@$filter['description'])class="select-filter"@endif data-type="Description">Description</th>
                                    @endif
                                    @if(@$tableheaders['date'])
                                        <th @if(@$filter['date'])class="select-filter"@endif data-type="Date">Date</th>
                                    @endif
                                    @if(@$tableheaders['location'])
                                        <th class="d-none d-sm-none d-md-none d-lg-table-cell d-xl-table-cell @if(@$filter['location']) select-filter @endif " data-type="Location">Location</th>
                                    @endif
                                    @if(@$tableheaders['state']&&auth()->user()->can('EXAMS-edit'))
                                        <th @if(@$filter['state'])class="select-filter"@endif data-type="State">State</th>
                                    @endif
                                    @if(@$tableheaders['start_finish'])
                                        <th @if(@$filter['start_finish'])class="select-filter"@endif data-type="Start &amp; Finish">Start &amp; Finish</th>
                                    @endif
                                    @if(@$tableheaders['invigilators_lead_req'])
                                        <th @if(@$filter['invigilators_lead_req'])class="select-filter"@endif data-type="Lead">Lead</th>
                                    @endif
                                    @if(@$tableheaders['invigilators_req'])
                                        <th @if(@$filter['invigilators_req'])class="select-filter"@endif data-type="Extra">Extra</th>
                                    @endif
                                    @if(@$tableheaders['duration'])
                                        <th @if(@$filter['duration'])class="select-filter"@endif data-type="Duration">Duration</th>
                                    @endif
                                    @if(@$tableheaders['students'])
                                        <th @if(@$filter['students'])class="select-filter"@endif data-type="Students">Students</th>
                                    @endif
                                    @if(@$tableheaders['notes'])
                                        <th @if(@$filter['notes'])class="select-filter"@endif data-type="Notes">Notes</th>
                                    @endif
                                    @if(@$tableheaders['actions'])
                                        <th width="120" style="text-align:center" data-type="Actions">Actions</th>
                                    @endif
                                        <th class="ignore d-none"></th>
                                </tr>
                                </thead>
                                @foreach($exams as $exam)
                                    <tr @if(@$config['active_pulsate']&&$exam->active()) class="active-pulsate" @endif>

                                        @role('Super Admin')
                                        <td>{{$exam->centre['name']}}</td>
                                        @endrole

                                        @if(@$tableheaders['description'])
                                            <td style="min-width: 110px;">
                                                @can('EXAMS-view')
                                                    <a href="{{route('exams.show', [$exam])}}" data-toggle="tooltip" data-placement="bottom" rel="tooltip" title="View exam &amp invigilators">{{ $exam->description }}</a> @else <strong>{{ $exam->description }}</strong> @endcan <span class="d-block d-sm-block d-md-block d-lg-none d-xl-none">
                                                    <strong>{{ $exam->location->name }}</strong><br>
                                                </span>
                                            </td>
                                        @endif

                                        @if(@$tableheaders['date'])
                                            <td data-sort="{{$exam->date}}">
                                                {{$exam->pretty_date_short}} - {{ $exam->pretty_time }}
                                            </td>
                                        @endif

                                        @if(@$tableheaders['location'])
                                            <td class="d-none d-sm-none d-md-none d-lg-table-cell d-xl-table-cell">
                                                <a href="{{route('exams.index', ['filter_location' => urlencode($exam->location->name)])}}" title="View exams in {{$exam->location->name}}" data-toggle="tooltip" data-placement="bottom" rel="tooltip">
                                                    {{ $exam->location->name }}
                                                </a>
                                            </td>
                                        @endif

                                        @if(@$tableheaders['state']&&auth()->user()->can('EXAMS-edit'))
                                        <td>
                                            @if($exam->state==1)
                                                <span class="badge" style="font-size:13px;background-color:#006802;color:#fff;font-weight:normal">
                                                    <span class="pulse"></span>
                                                    Live
                                                </span>
                                            @else
                                                <span class="badge" style="font-size:13px;background-color:#ccc;color:#555;font-weight:normal">
                                                    <span class="draft"></span>
                                                    Draft
                                                </span>
                                            @endif
                                        </td>
                                        @endif

                                        @if(@$tableheaders['start_finish'])
                                            <td>
                                                {{$exam->pretty_time}} - {{$exam->pretty_finish_time}}
                                            </td>
                                        @endif

                                        @if(@$tableheaders['invigilators_lead_req'])
                                            <td>
                                                @if($exam->lead_full)
                                                    <span class="badge" style="font-size:13px;background-color:#006802;color:#fff;font-weight:normal;cursor:help"
                                                          @if(($exam->hide_names!==1||auth()->user()->can('EXAMS-edit'))&&$exam->participations_lead()->count() > 0)
                                                          data-toggle="tooltip" data-html="true" data-placement="bottom" rel="tooltip" title="
                                                                    @foreach($exam->participations_lead() as $participation)
                                                                        &bull;&nbsp;{{ App\User::withTrashed()->where('id', $participation->user_id)->first()->full_name }}
                                                                    @endforeach
                                                        "
                                                          @endif
                                                    > {{$exam->participations_lead()->count()}}/{{$exam->invigilators_lead_req ?? '0'}}</span>@else<span class="badge" style="font-size:13px;background-color:#d68300;color:#fff;font-weight:normal;cursor:help"
                                                          @if(($exam->hide_names!==1||auth()->user()->can('EXAMS-edit'))&&$exam->participations_lead()->count() > 0)
                                                              data-toggle="tooltip" data-html="true" data-placement="bottom" rel="tooltip" title="
                                                                    @foreach($exam->participations_lead() as $participation)
                                                                        &bull;&nbsp;{{ App\User::withTrashed()->where('id', $participation->user_id)->first()->full_name }}
                                                                    @endforeach
                                                            "
                                                          @endif
                                                    > {{$exam->participations_lead()->count()}}/{{$exam->invigilators_lead_req ?? '0'}}</span> @endif<div class="hide_names">@foreach($exam->participations_lead() as $participation)&nbsp;&bull;&nbsp;{{App\User::withTrashed()->where('id', $participation->user_id)->first()->full_name}}&nbsp; @endforeach </div>
                                            </td>
                                        @endif

                                        @if(@$tableheaders['invigilators_req'])
                                            <td>
                                                @if($exam->extra_full)
                                                    <span class="badge" style="font-size:13px;background-color:#006802;color:#fff;font-weight:normal;cursor:help"
                                                          @if(($exam->hide_names!==1||auth()->user()->can('EXAMS-edit'))&&$exam->participations_extra()->count() > 0)
                                                          data-toggle="tooltip" data-html="true" data-placement="bottom" rel="tooltip" title="
                                                                    @foreach($exam->participations_extra() as $participation)
                                                                        &bull;&nbsp;{{ App\User::withTrashed()->where('id', $participation->user_id)->first()->full_name }}
                                                                    @endforeach
                                                        "
                                                          @endif
                                                    >{{$exam->participations_extra()->count()}}/{{$exam->invigilators_req ?? '0'}}
                                                    </span>@else<span class="badge" style="font-size:13px;background-color:#d68300;color:#fff;font-weight:normal;cursor:help"
                                                          @if(($exam->hide_names!==1||auth()->user()->can('EXAMS-edit'))&&$exam->participations_extra()->count() > 0)
                                                          data-toggle="tooltip" data-html="true" data-placement="bottom" rel="tooltip" title="
                                                                    @foreach($exam->participations_extra() as $participation)
                                                                        &bull;&nbsp;{{App\User::withTrashed()->where('id', $participation->user_id)->first()->full_name}}
                                                                    @endforeach
                                                        "
                                                          @endif
                                                    >{{$exam->participations_extra()->count()}}/{{$exam->invigilators_req ?? '0'}}</span> @endif<div class="hide_names">@foreach($exam->participations_extra() as $participation)&bull;&nbsp;{{App\User::withTrashed()->where('id', $participation->user_id)->first()->full_name}}&nbsp; @endforeach </div>

                                            </td>
                                        @endif

                                        @if(@$tableheaders['duration'])
                                            <td>{{ $exam->pretty_duration }}</td>
                                        @endif

                                        @if(@$tableheaders['students'])
                                            <td>{{ $exam->students }}</td>
                                        @endif

                                        @if(@$tableheaders['notes'])
                                            <td data-toggle="tooltip" data-placement="bottom" rel="tooltip" title="{{$exam->notes}}">{{ strlen($exam->notes) > 50 ? substr($exam->notes,0,50)."..." : $exam->notes }}</td>
                                        @endif

                                        @if(@$tableheaders['actions'])
                                            <td class="text-center">
                                                @can('EXAMS-edit')
                                                    <div class="d-inline-block" style="width:115px">
                                                        <a href="{{ route('exams.show', [$exam]) }}" title="View exam &amp invigilators" data-toggle="tooltip" data-placement="bottom" rel="tooltip">
                                                            <button class="btn btn-sm btn-success bg-primary text-white" style="width:33px"><i class="fas fa-eye"></i></button>
                                                        </a>

                                                        <a href="{{ route('exams.edit', [$exam]) }}" title="Edit" data-toggle="tooltip" data-placement="bottom" rel="tooltip">
                                                            <button class="btn btn-sm btn-success bg text-white" style="width:33px"><i class="fas fa-edit"></i></button>
                                                        </a>
                                                        @can('EXAMS-edit')
                                                            <form action="{{ route('exams.destroy', [$exam]) }}" method="POST" class="no-form-style d-none" id="delete-form-{{ $exam->id }}">
                                                            @METHOD('DELETE')
                                                            <button class="btn btn-danger" type="submit">Delete</button>
                                                            @csrf
                                                            </form>
                                                            <button class="btn btn-sm btn-danger confirm-delete text-white" style="width:33px" title="Delete" data-toggle="tooltip" data-placement="bottom" rel="tooltip" data-form-id="delete-form-{{ $exam->id }}" data-title="Are you sure?" data-body="This will permanently delete this exam and all participations. Be careful!">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                @else
                                                    @if($exam->invigilator_check(auth()->user()->id))
                                                        <div class="invigilator_me" style="height:auto !important;margin-bottom:0;cursor:pointer">
                                                            Registered
                                                        </div>
                                                    @else
                                                        <a href="{{route('exams.show', [$exam])}}" class="invigilator_signup" style="height:auto !important;color:#fff;margin-bottom:0">
                                                            Sign-up
                                                        </a>
                                                    @endif
                                                @endcan
                                            </td>
                                        @endif
                                        <td class="d-none">{{ Carbon\Carbon::parse($exam->date)->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <p>No exams to show.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripting')

    @include('inc.modals.confirm')
    <script>

        /* Plugin API method to determine is a column is sortable */
        $.fn.dataTable.Api.register( 'column().searchable()', function () {
            var ctx = this.context[0];
            return ctx.aoColumns[ this[0] ].bSearchable;
        } );


        $(document).ready(function() {
            var otable = $('#exams').DataTable({
                "order": [[@role('Super Admin') 2 @else 1 @endrole, "desc"]],
                dom: 'Bfrti',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],
                responsive: true,
                stateSave: false,
                "aoColumnDefs": [
                    {'bSortable': false, 'aTargets': [-1]},
                    {"visible": false, "targets": [0]}
                ],
                'processing': true,
                "pageLength": 9999999,
                'language': {
                    'loadingRecords': '&nbsp;',
                    'processing': 'Loading...'
                },
                'columnDefs': [
                    {responsivePriority: 1, targets: 0},
                    {responsivePriority: 2, targets: -2},
                    {orderable: false, "targets": [-1]},
                ],

                'oLanguage': {
                    sLengthMenu: "Show _MENU_",
                    "infoFiltered": ""
                },
                "initComplete": function () {
                    $('#loader').hide(1, function () {
                        $('#exams').show(1);
                    });


                },
            });

            // Setup - add a text input to each header cell
            $('.select-filter').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control filter" data-state-save="true" preset="' + title + '" placeholder="' + title + '" /><div class="hide_names">'+title+'</div>');
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

            //Style buttons
            $('.dt-button').addClass('btn btn-sm btn-primary');

            // Show message about hidden cols
            function hiddenCells() {
                var hidden = '';
                var colCount = 0;
                $('th').each(function () {
                    colCount++;
                });
                var hiddenCount = 0;
                $('th:hidden:not(".ignore")').each(function () {
                    hidden = hidden + " &bull; " + $(this).data('type');
                    hiddenCount++;
                });
                if (hiddenCount > 0) {
                    $(".hidden_message").remove();
                    $("#exams_wrapper").prepend('' +
                        '<div style="float: left;font-size:12px;color:#990000" class="hidden_message d-none d-md-block d-lg-block d-xl-block">'
                        + hidden +
                        ' <span style="color:#555">are hidden due to your screen width</span></div>');
                } else {
                    $(".hidden_message").remove();
                }
            }

            hiddenCells();

            $( window ).resize(function() {
                hiddenCells()
            });


            //Daterange
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = $('#min-date').val();
                    var max = $('#max-date').val();
                    var createdAt = data[
                        @role('Super Admin') 11 @endrole
                        @role('Centre Admin') 10 @endrole
                        @role('Invigilator') 9 @endrole
                    ]; // Our date column in the table

                    console.log(min);

                    if (
                        (min == "" || max == "") ||
                        (moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max))
                    ) {
                        return true;
                    }
                    return false;
                }
            );

            // Re-draw the table when the a date range filter changes
            $('.date-range-filter').change(function() {
                otable.draw();
            });

            //Add search criteria from GET
            @if(@$_GET['filter_location'])
            $('input[preset=Location]').val('{!! urldecode($_GET['filter_location']) !!}').trigger('keyup');
            @endif

            //Add search criteria from GET data
            @if(@$_GET['date'])
            $('#min-date').trigger('change', function(){
                $('#max-date').trigger('change');
            });
            @endif


        });

    </script>
@endpush
