@if(@$timelines)
    <div class="container">
        <div class="row">
            <div class="main-timeline">
                <?php $count = 0; ?>
                    @foreach($timelines as $key=>$entry)
                        <div class="timeline">
                            <div class="timeline-icon"></div>
                            <div class="timeline-content">
                                <span class="date">{{ \Carbon\Carbon::parse($entry->updated_at)->format('l jS F Y (h:m:s)') }}</span>
                                <p class="description">
                                    {!! $entry->message !!}
                                </p>
                            </div>
                        </div>
                    @endforeach
                    @if($count < 1)
                        No timeline entries to show
                    @endif



            </div>
        </div>
    </div>
@endif
