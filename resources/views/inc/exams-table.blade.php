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
                        <div class="col-md-6 d-none d-sm-none d-md-none d-md-block d-lg-block d-xl-block">
                                <a href="{{ route('exams.create') }}">
                                    <button class="btn btn-primary float-right">Create Exam</button>
                                </a>
                        </div>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @if(!$exams->isEmpty())
                            <div id="loader" class="loader"><img class="pt-5 pb-5" src="{{asset('img/loader.gif')}}"></div>
                            <table class="table table-bordered table-hover" id="exams" style="width:100%;display:none">
                                <thead>
                                <tr>
                                    @if(@$tableheaders['date'])
                                        <th @if(@$filter['date'])class="select-filter"@endif>Date</th>
                                    @endif
                                    @if(@$tableheaders['description'])
                                        <th @if(@$filter['description'])class="select-filter"@endif>Description</th>
                                    @endif
                                    @if(@$tableheaders['start_finish'])
                                        <th @if(@$filter['start_finish'])class="select-filter"@endif>Start &amp; Finish</th>
                                    @endif
                                    @if(@$tableheaders['location'])
                                        <th class="d-none d-sm-none d-md-none d-lg-table-cell d-xl-table-cell @if(@$filter['location']) select-filter @endif ">Location</th>
                                    @endif
                                    @if(@$tableheaders['invigilators_lead_req'])
                                        <th @if(@$filter['invigilators_lead_req'])class="select-filter"@endif>Lead Invigilators</th>
                                    @endif
                                    @if(@$tableheaders['invigilators_req'])
                                        <th @if(@$filter['invigilators_req'])class="select-filter"@endif>Extra Invigilators</th>
                                    @endif
                                    @if(@$tableheaders['duration'])
                                        <th @if(@$filter['duration'])class="select-filter"@endif>Duration</th>
                                    @endif
                                    @if(@$tableheaders['students'])
                                        <th @if(@$filter['students'])class="select-filter"@endif>Students</th>
                                    @endif
                                    @if(@$tableheaders['notes'])
                                        <th @if(@$filter['notes'])class="select-filter"@endif>Notes</th>
                                    @endif
                                    @if(@$tableheaders['actions'])
                                        <th width="120" style="text-align:center">Actions</th>
                                    @endif
                                </tr>
                                </thead>
                                @foreach($exams as $exam)
                                    <tr @if(@$config['active_pulsate']&&$exam->active()) class="active-pulsate" @endif>

                                        @if(@$tableheaders['date'])
                                            <td data-sort="{{$exam->date}}">
                                                @can('EXAMS-view')
                                                    <a href="{{route('exams.show', [$exam])}}" data-toggle="tooltip" data-placement="bottom" rel="tooltip" title="Open the exam to view more details">
                                                        {{ $exam->pretty_date_short }}
                                                    </a>
                                                @else
                                                    <strong>{{ $exam->pretty_date_short }}</strong>
                                                @endcan
                                                <span class="d-block d-sm-block d-md-block d-lg-none d-xl-none">
                                                    <strong>{{ $exam->location->name }}</strong><br>
                                                </span>
                                                @if($exam->state==1&&auth()->user()->can('EXAMS-edit')) <br><strong><span class='text-green'>[Live]</span></strong> @elseif($exam->state==0&&auth()->user()->can('EXAMS-edit')) <br><strong><span class='text-danger'>[Draft]</span></strong> @endif
                                            </td>
                                        @endif

                                        @if(@$tableheaders['description'])
                                            <td>
                                                {{$exam->description}}
                                            </td>
                                        @endif

                                        @if(@$tableheaders['start_finish'])
                                            <td>
                                                {{$exam->pretty_time}} - {{$exam->pretty_finish_time}}
                                            </td>
                                        @endif

                                        @if(@$tableheaders['location'])
                                            <td class="d-none d-sm-none d-md-none d-lg-table-cell d-xl-table-cell" onclick="location.href='{{route('exams.show', [$exam])}}'" style="cursor:pointer">
                                                {{ $exam->location->name }}
                                            </td>
                                        @endif

                                        @if(@$tableheaders['invigilators_lead_req'])
                                            <td>
                                                @if($exam->lead_full)
                                                    <span class="badge" style="font-size:13px;background-color:#006802;color:#fff">
                                                        Full ({{$exam->participations_lead()->count()}}/{{$exam->invigilators_lead_req ?? '0'}})
                                                    </span>
                                                @else
                                                    <span class="badge" style="font-size:13px;background-color:#d68300;color:#fff">
                                                        Required ({{$exam->participations_lead()->count()}}/{{$exam->invigilators_lead_req ?? '0'}})
                                                    </span>
                                                @endif
                                            </td>
                                        @endif

                                        @if(@$tableheaders['invigilators_req'])
                                            <td>
                                                @if($exam->extra_full)
                                                    <span class="badge" style="font-size:13px;background-color:#006802;color:#fff">
                                                        Full ({{$exam->participations_extra()->count()}}/{{$exam->invigilators_req ?? '0'}})
                                                    </span>
                                                @else
                                                    <span class="badge" style="font-size:13px;background-color:#d68300;color:#fff">
                                                        Required ({{$exam->participations_extra()->count()}}/{{$exam->invigilators_req ?? '0'}})
                                                    </span>
                                                @endif
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
                                                        <a href="{{ route('exams.edit', [$exam]) }}" title="Edit" data-toggle="tooltip" data-placement="bottom" rel="tooltip">
                                                            <button class="btn btn-sm btn-success text-white" style="width:33px"><i class="fas fa-edit"></i></button>
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
                                                        <div class="invigilator_me" style="height:auto !important;margin-bottom:0">
                                                            Registered
                                                        </div>
                                                    @else
                                                        <a href="{{route('exams.show', [$exam])}}" class="invigilator_signup" style="height:auto !important;color:#fff;margin-bottom:0">
                                                            Go&nbsp;to&nbsp;sign-up&nbsp;page
                                                        </a>
                                                    @endif
                                                @endcan
                                            </td>
                                        @endif
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
                "order": [[ 0, "desc" ]],
                dom: 'Bflrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],
                responsive: true,
                stateSave: false,
                "lengthMenu": [[10, 25, 50, 100, 200, - 1], [10, 25, 50, 100, 200, "All"]],
                "aoColumnDefs": [
                    {'bSortable': false, 'aTargets': [-1]},
                    { "visible": false, "targets": [ 0 ] }
                ],
                'processing': true,
                "pageLength": 25,
                'language': {
                    'loadingRecords': '&nbsp;',
                    'processing': 'Loading...'
                },
                'columnDefs': [
                    {responsivePriority: 1, targets: 0},
                    {responsivePriority: 2, targets: -1},
                    {orderable: false, "targets": [-1]},
                ],

                'oLanguage': {
                    sLengthMenu: "Show _MENU_",
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

            //Style buttons
            $('.dt-button').addClass('btn btn-sm btn-primary');

        });

    </script>
@endpush
