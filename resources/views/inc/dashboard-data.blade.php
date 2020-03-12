<div class="row d-flex d-sm-flex d-md-none d-md-none d-lg-none d-xl-none mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Data overview</div>
            <div class="card-body p-0 border-0">
                <ul class="list-group">
                    @if(@$centre_count)
                        <li class="list-group-item border-0" style="border-bottom: 1px solid #ccc !important;font-size:14px;">
                            <div class="d-inline-block" style="width:25px;font-size:16px;"><i class="fas fa-building text-purple"></i></div>
                            Centres
                            <span class="badge badge-light text-dark float-right mt-1" style="font-size:12px">{{ $centre_count }}</span>
                        </li>
                    @endif
                    @if(@$activities_count)
                        <li class="list-group-item border-0" style="border-bottom: 1px solid #ccc !important;font-size:14px;">
                            <div class="d-inline-block" style="width:25px;font-size:16px;"><i class="fas fa-book text-green"></i></div>
                            Activities
                            <span class="badge badge-light text-dark float-right mt-1" style="font-size:12px">{{ $activities_count }}</span>
                        </li>
                    @endif
                    @if(@$exams_count)
                        <li class="list-group-item border-0" style="border-bottom: 1px solid #ccc !important;font-size:14px;">
                            <div class="d-inline-block" style="width:25px;font-size:16px;"><i class="fas fa-chalkboard-teacher text-red"></i></div>
                            Sessions
                            <span class="badge badge-light text-dark float-right mt-1" style="font-size:12px">{{ $exams_count }}</span>
                        </li>
                    @endif
                    @if(@$users_count)
                        <li class="list-group-item border-0" style="border-bottom: 1px solid #ccc !important;font-size:14px;">
                            <div class="d-inline-block" style="width:25px;font-size:16px;"><i class="fas fa-users text-orange"></i></div>
                            Users
                            <span class="badge badge-light text-dark float-right mt-1" style="font-size:12px">{{ $users_count }}</span>
                        </li>
                    @endif
                    @if(@$participations_count)
                        <li class="list-group-item border-0" style="border-bottom: 1px solid #ccc !important;font-size:14px;">
                            <div class="d-inline-block" style="width:25px;font-size:16px;"><i class="fas fa-check-circle text-dark-blue"></i></div>
                            Attendances
                            <span class="badge badge-light text-dark float-right mt-1" style="font-size:12px">{{ $participations_count }}</span>
                        </li>
                    @endif
                    @if(@$total_time)
                        <li class="list-group-item border-0" style="font-size:14px;">
                            <div class="d-inline-block" style="width:25px;font-size:16px;"><i class="fas fa-clock text-blue"></i></div>
                            Duration
                            <span class="badge badge-light text-dark float-right mt-1" style="font-size:12px">{{ $total_time }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if($dashboard_size==1){ $cols = "col-lg-6"; }elseif($dashboard_size==2){ $cols = "col-lg-3"; }else{ $cols = "col-lg-2"; } ?>

<div class="row d-none d-sm-none d-md-none d-md-flex d-lg-flex d-xl-flex mb-4">
    @if(@$centre_count)
        <div class="{{$cols}} col-sm-6">
            <div class="circle-tile ">
                <a href="#"><div class="circle-tile-heading purple"><i class="fa fa-building fa-fw fa-3x"></i></div></a>
                <div class="circle-tile-content dark-blue">
                    <div class="circle-tile-description text-faded"> Centres</div>
                    <div class="circle-tile-number text-faded ">{{ $centre_count }}</div>
                </div>
            </div>
        </div>
    @endif

    @if(@$activities_count)
        <div class="{{$cols}} col-sm-6">
            <div class="circle-tile ">
                <a href="#"><div class="circle-tile-heading green"><i class="fa fa-book fa-fw fa-3x"></i></div></a>
                <div class="circle-tile-content dark-blue">
                    <div class="circle-tile-description text-faded"> Activities</div>
                    <div class="circle-tile-number text-faded ">{{ $activities_count }}</div>
                </div>
            </div>
        </div>
    @endif

    @if(@$exams_count)
        <div class="{{$cols}} col-sm-6">
            <div class="circle-tile ">
                <a href="#"><div class="circle-tile-heading red"><i class="fa fa-chalkboard-teacher fa-fw fa-3x"></i></div></a>
                <div class="circle-tile-content dark-blue">
                    <div class="circle-tile-description text-faded"> Sessions </div>
                    <div class="circle-tile-number text-faded ">{{ $exams_count }}</div>
                </div>
            </div>
        </div>
    @endif

    @if(@$users_count)
        <div class="{{$cols}} col-sm-6">
            <div class="circle-tile ">
                <a href="#"><div class="circle-tile-heading orange"><i class="fa fa-users fa-fw fa-3x"></i></div></a>
                <div class="circle-tile-content dark-blue">
                    <div class="circle-tile-description text-faded"> Users </div>
                    <div class="circle-tile-number text-faded ">{{ $users_count }}</div>
                </div>
            </div>
        </div>
    @endif

    @if(@$participations_count)
        <div class="{{$cols}} col-sm-6">
            <div class="circle-tile ">
                <a href="#"><div class="circle-tile-heading yellow"><i class="fa fa-check-circle fa-fw fa-3x"></i></div></a>
                <div class="circle-tile-content dark-blue">
                    <div class="circle-tile-description text-faded"> Attendances </div>
                    <div class="circle-tile-number text-faded ">{{ $participations_count }}</div>
                </div>
            </div>
        </div>
    @endif

    @if(@$total_time)
        <div class="{{$cols}} col-sm-6">
            <div class="circle-tile ">
                <a href="#"><div class="circle-tile-heading blue"><i class="fa fa-clock fa-fw fa-3x"></i></div></a>
                <div class="circle-tile-content dark-blue">
                    <div class="circle-tile-description text-faded"> Total Duration </div>
                    <div class="circle-tile-number text-faded ">{{ $total_time }}</div>
                </div>
            </div>
        </div>
    @endif
</div>
