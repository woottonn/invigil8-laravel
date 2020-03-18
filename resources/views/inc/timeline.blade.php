@if(@$timelines)
    <div class="container">
        <div class="row">
            <div class="main-timeline">
                <?php $count = 0; ?>
                    @foreach($timelines as $key=>$entry)
                        <div class="timeline">
                            <div class="timeline-icon"></div>
                            <div class="timeline-content">
                                <span class="date" style="padding-left:0px;">{{ $entry->time_since }}...</span>

                                <p class="description">
                                    {!! $entry->message !!}
                                </p>
                                <span class="date-small">{{ $entry->pretty_date }}</span>
                            </div>
                        </div>
                        <?php $count++; ?>
                    @endforeach
                    @if($count < 1)
                        @push('scripting')
                            <script>
                                $('.main-timeline').remove();
                            </script>
                        @endpush
                    @endif
            </div>
        </div>
    </div>
@endif
