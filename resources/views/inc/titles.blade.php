<h1>
    <i class="fa fa-calendar-alt float-right text-primary d-none d-sm-none d-md-none d-md-block d-lg-block d-xl-block" id="calendar_show" style="font-size:30px;cursor:pointer"
       data-toggle="tooltip" data-placement="bottom" rel="tooltip" title="Show exam calendar"></i>
    {!! $title !!}
    @if(@$date) -
        <span class="text-primary">{{ \Carbon\Carbon::parse($date)->format('l jS F Y') }}</span>
    @endif
</h1>
<div class="row mb-2">
    <div class="col-md-12">
        <p>{{$subtitle}}</p>
    </div>
</div>
