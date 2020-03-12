@if(@$timelines->count() > 0)
    <div class="container">
        <div class="row">
            <div class="main-timeline">

                    @foreach($timelines as $key=>$entry)
                        <div class="timeline">
                            <div class="timeline-icon"></div>
                            <div class="timeline-content">
                                <span class="date">{{ \Carbon\Carbon::parse($entry->date)->format('l jS F Y') }}</span>
                                <p class="description">
                                    {!! $entry->message !!}
                                </p>
                            </div>
                        </div>
                    @endforeach



            </div>
        </div>
    </div>
@endif
