<div class="row">
    <div class="form-group col-md-12">
        <label for="date">Description</label>
        <input type="text" maxlength="255" required class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') ?? $exam->description ?? '' }}">
        <div class="text-danger">{{ $errors->first('description') }}</div>
    </div>

    <div class="form-group col-md-6 col-lg-4 col-xl-3">
        <label for="se">Location</label>
        <select class="form-control @error('exam_location_id') is-invalid @enderror" id="exam_location_id" name="exam_location_id">
            @if(!$locations->isEmpty())
                @foreach($locations as $location)
                    <option value="{{ $location->id }}"
                            @if(@$location->id == @$exam->location_id||@$location->id == old('exam_location_id'))
                            selected
                        @endif
                    >
                        {{ $location->name }}
                    </option>
                @endforeach
            @else
                <option>No locations available</option>
            @endif
        </select>
        <div class="text-danger">@if($errors->first('exam_location_id')) Please choose a location @endif</div>
    </div>

    <div class="form-group col-md-6 col-lg-4 col-xl-3">
        <label for="date">Date &amp; Time</label>
        <input type="datetime-local" max="{{ config('sitevars.seasons')[session('season')->name]['date_end'] }}T23:59" min="{{ config('sitevars.seasons')[session('season')->name]['date_start'] }}T00:00" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') ?? Carbon\Carbon::parse($exam->date)->format('Y-m-d\TH:i') ?? '' }}">
        <div class="text-danger">{{ $errors->first('date') }}</div>
    </div>

    <div class="form-group col-md-6 col-lg-4 col-xl-3">
        <label for="date">Duration</label>
        <input type="text" readonly id="duration" class="form-control @error('duration') is-invalid @enderror" name="duration" value="{{ old('duration') ?? @Carbon\Carbon::parse($exam->duration)->format('H:i') ?? '' }}">
        <div class="text-danger">{{ $errors->first('duration') }}</div>
    </div>

    <div class="form-group col-md-6 col-lg-4 col-xl-3">
        <label for="date">Number of Students</label>
        <input type="number" max="20000" class="form-control @error('students') is-invalid @enderror" name="students" value="{{ old('students') ?? $exam->students ?? '' }}">
        <div class="text-danger">{{ $errors->first('students') }}</div>
    </div>

    <div class="form-group col-md-6 col-lg-4 col-xl-3">
        <label for="date">Lead Invigilators Required</label>
        <input type="number" max="100" class="form-control @error('invigilators_lead_req') is-invalid @enderror" name="invigilators_lead_req" value="{{ old('invigilators_lead_req') ?? $exam->invigilators_lead_req ?? '' }}">
        <div class="text-danger">{{ $errors->first('invigilators_lead_req') }}</div>
    </div>

    <div class="form-group col-md-6 col-lg-4 col-xl-3">
        <label for="date">Extra Invigilators Required</label>
        <input type="number" max="100" class="form-control @error('invigilators_req') is-invalid @enderror" name="invigilators_req" value="{{ old('invigilators_req') ?? $exam->invigilators_req ?? '' }}">
        <div class="text-danger">{{ $errors->first('invigilators_req') }}</div>
    </div>

    @role('Super Admin')
        <div class="form-group col-md-6 col-lg-4 col-xl-3">
            <label for="centre">Centre</label>
            <select class="form-control" id="centre_id" name="centre_id">
                @if(!$centres->isEmpty())
                    @foreach($centres as $centre)
                        <option
                            @if($centre->id==$exam->centre_id)
                            selected
                            @endif
                            value="{{ $centre->id }}">
                            {{ $centre->name }}
                        </option>
                    @endforeach
                @else
                    <option>No centres available</option>
                @endif
            </select>
        </div>
    @endrole
    {{ $errors->first('centre_id') }}

    <div class="form-group col-md-6 col-lg-4 col-xl-3">
        <label for="hide_names">Hide names from invigilators?</label>
        <select class="form-control @error('hide_names') is-invalid @enderror" id="hide_names" name="hide_names">
            <option value="1" @if(@$exam->hide_names == 1) selected @endif>Yes</option>
            <option value="0" @if(@$exam->hide_names == 0) selected @endif>No</option>
        </select>
    </div>

    <div class="form-group col-md-6 col-lg-4 col-xl-3">
        <label for="date">Notes</label>
        <input type="input" class="form-control @error('notes') is-invalid @enderror" name="notes" value="{{ old('Notes') ?? $exam->notes ?? '' }}">
        <div class="text-danger">{{ $errors->first('notes') }}</div>
    </div>

    <div class="form-group col-md-6 col-lg-4 col-xl-3">
        <label for="state">State</label>
        <select class="form-control @error('state') is-invalid @enderror" id="state" name="state">
            <option value="0" @if(@$exam->state == 0) selected @endif>Draft (Invisible to invigilators)</option>
            <option value="1" @if(@$exam->state == 1) selected @endif>Live (Visible to invigilators)</option>
        </select>
    </div>

</div>

<br>
<div class="form-check mb-4 email-checkbox">
    <input class="form-check-input" type="checkbox" value="1" id="email" name="email">
    <label class="form-check-label" for="email">
        @if(@$type=="Edit") Notify invigilators about these updates by email  @else Notify invigilators about this new exam by email @endif
    </label>
</div>

<button type="submit" class="btn btn-primary">Submit</button>
@csrf


@push('scripting')
<script>

    if($('#state').val() === '1'){
        $('.email-checkbox').show();
    }else{
        $('.email-checkbox').hide();
    }
    $('#state').change(function(){
        console.log($('#state').val() );
        if($(this).val() === '1'){
            $('.email-checkbox').show();
        }else{
            $('.email-checkbox').hide();
        }
    });

    $('#exam_location_id').select2({
        placeholder: 'Select an option'
    });
    $('#duration').timepicker({
        timeFormat: 'HH:mm',
        interval: 15,
        minTime: '00:30',
        maxTime: '23:00',
        defaultTime: '{{old('duration') ?? $exam->duration ?? '01:00'}}',
        startTime: '00:30',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
</script>
@endpush
