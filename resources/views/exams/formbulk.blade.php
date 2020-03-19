<div>

    <table class="table table-bordered table-hover bg-white" style="font-size:12px !important;">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Description</th>
            <th scope="col">Location</th>
            <th scope="col">Date&nbsp;&amp;&nbsp;Time</th>
            <th scope="col">Duration</th>
            <th scope="col">Students</th>
            <th scope="col">Lead&nbsp;Inv</th>
            <th scope="col">Extra&nbsp;Inv</th>
            <th scope="col">Hide&nbsp;Names</th>
            <th scope="col">Notes</th>
            <th scope="col">State</th>
            <th scope="col">Notify</th>
        </tr>
        </thead>
        <tbody>

        @foreach(range(1,20) as $index)
            <?php $i = $index; ?>
            <tr>
                <th scope="row">
                    <div class="form-check include{{ $index }}" style="width: 100%;text-align: center;">
                        <input class="form{{$index}} form-check-input include" data-include="{{ $index }}" type="checkbox" value="1" id="include{{ $index }}" name="include[{{ $index }}]" checked>
                    </div>
                </th>
                <td>
                    <div class="form-group">
                        <input placeholder="Description" type="text" maxlength="255" class="form{{ $index }} form-control @error('description.'.$i) is-invalid  error-pulse @enderror" name="description[{{ $index }}]" value="{{ old('description.'.$i) ?? '' }}">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select class="form{{ $index }} form-control @error('exam_location_id.'.$i) is-invalid  error-pulse @enderror" id="exam_location_id{{ $index }}" name="exam_location_id[{{ $index }}]">
                            @if(!$locations->isEmpty())
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}"
                                            @if(@$location->id == old('exam_location_id.'.$i))
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
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="datetime-local" max="{{ config('sitevars.seasons')[session('season')->name]['date_end'] }}T23:59" min="{{ config('sitevars.seasons')[session('season')->name]['date_start'] }}T00:00" class="form{{ $index }} form-control @error('date.'.$i) is-invalid  error-pulse  @enderror" name="date[{{ $index }}]" value="{{ old('date.'.$i) ?? '' }}">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" style="width:65px;" readonly id="duration{{ $index }}" class="form{{ $index }} form-control @error('duration.'.$i) is-invalid  error-pulse @enderror" name="duration[{{ $index }}]" value="{{ old('duration.'.$i) ?? '' }}">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input placeholder="Students #" type="number" max="20000" class="form{{ $index }} form-control @error('students.'.$i) is-invalid  error-pulse @enderror" name="students[{{ $index }}]" value="{{ old('students.'.$i) ?? '' }}">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input placeholder="Lead #" type="number" max="100" class="form{{ $index }} form-control @error('invigilators_lead_req.'.$i) is-invalid  error-pulse @enderror" name="invigilators_lead_req[{{ $index }}]" value="{{ old('invigilators_lead_req.'.$i) ?? '' }}">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input placeholder="Extra #" type="number" max="100" class="form{{ $index }} form-control @error('invigilators_req.'.$i) is-invalid  error-pulse @enderror" name="invigilators_req[{{ $index }}]" value="{{ old('invigilators_req.'.$i) ?? '' }}">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select class="form{{ $index }} form-control @error('hide_names.'.$i) is-invalid  error-pulse @enderror" id="hide_names{{$index}}" name="hide_names[{{ $index }}]">
                            <option value="1" @if(@old('hide_names.'.$i)==1) selected @endif>Yes</option>
                            <option value="0" @if(@old('hide_names.'.$i)==0) selected @endif>No</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input placeholder="Notes" type="input" class="form{{ $index }} form-control @error('notes.'.$i) is-invalid error-pulse @enderror" name="notes[{{ $index }}]" value="{{ old('notes.'.$i) ?? '' }}">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select class="form{{ $index }} form-control @error('state.'.$i)) is-invalid error-pulse @enderror" id="state{{ $index }}" name="state[{{ $index }}]">
                            <option value="0" @if(@old('state.'.$i)==0) selected @endif>Draft (Invisible to invigilators)</option>
                            <option value="1" @if(@old('state.'.$i)==1) selected @endif>Live (Visible to invigilators)</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-check email-checkbox{{ $index }}" style="width: 100%;text-align: center;">
                        <input class="form-check-input mr-0" type="checkbox" value="1" id="email{{ $index }}" name="email[{{ $index }}]" @if(old('email.'.$i)) checked @endif>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

<input type="hidden" value="1" name="total" id="total">

<button type="submit" class="btn btn-primary">Submit</button>
@csrf

@push('scripting')
<style>
    .form-group{
        margin-bottom: 0 !important;
        font-size:12px !important;
    }
     input, select{
        font-size: 12px !important;
    }
    .ui-corner-all{
        padding: 0px !important;
        padding-left: 2px !important;
        text-align: left;
    }
    .select2-dropdown{
        width: 200px !important;
    }
</style>
<script>

@foreach(range(1,21) as $index)

    @if($index > 1)
        $("#include{{$index}}").prop('checked',false);
        $(".form{{$index}}").attr('disabled', 'disabled').css('opacity', 0.5);
        $(".select2-selection:not(:first)").css('opacity', 0.5);
    @endif

    @if($index == 2)
        $("#include{{$index}}").removeAttr('disabled').css('opacity', 1.0);
    @endif


    if($('#state{{$index}}').val() === '1'){
        $('.email-checkbox{{$index}}').show();
    }else{
        $('.email-checkbox{{$index}}').hide();
    }
    $('#state{{$index}}').change(function(){
        if($(this).val() === '1'){
            $('.email-checkbox{{$index}}').show();
        }else{
            $('.email-checkbox{{$index}}').hide();
            $('#email{{$index}}').prop('checked',false);
        }
    });

    $('#exam_location_id{{$index}}').select2({
        placeholder: 'Select an option'
    });
    $('#duration{{$index}}').timepicker({
        timeFormat: 'HH:mm',
        interval: 15,
        minTime: '00:30',
        maxTime: '23:00',
        defaultTime: '{{old('duration'.$index) ?? $exam->duration ?? '01:00'}}',
        startTime: '00:30',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

@endforeach

@if(@old('total'))
    @foreach(range(1,old('total')) as $index)

        console.log('here');
        $next = {{$index + 1}};
        $prev = {{$index - 1}};
        $eq = {{$index - 1}};
        $this = {{$index}}

        $("#include" + $this).prop('checked',true);
        $(".form" + $this).removeAttr('disabled').css('opacity', 1.0);
        $('#include' + $next).attr("disabled","disabled").css('opacity', 1);
        $(".select2-selection:eq(" + $eq + ")").css('opacity', 1);

    @endforeach
    $('#include' + $next).removeAttr("disabled").css('opacity', 1);
@endif


    //Handle checkbox disables
    $('.include').click(function (e) {
        if($('#include1').val()==='1') {
            $('#total').val($('.include[type="checkbox"]:checked').length); //Count checkboxes
        }else{
            $('#total').val(0);
        }

        $this = parseInt($(this).attr('data-include'));
        $next = parseInt($(this).attr('data-include')) + 1;
        $prev = parseInt($(this).attr('data-include')) - 1;
        $eq = parseInt($(this).attr('data-include')) - 1;

        if($(this).is(':checked')) {
            $("#include" + $prev).attr('disabled','disabled');
            $('#include' + $next).removeAttr("disabled").css('opacity', 1);
            $('.form' + $this).removeAttr("disabled").css('opacity', 1);
            $(".select2-selection:eq(" + $eq + ")").css('opacity', 1);

        }else{
            $("#include" + $prev).removeAttr('disabled');
            $('.form' + $this).attr("disabled", "disabled").css('opacity', 0.5);
            $('#include' + $next).attr("disabled", "disabled").css('opacity', 0.5);
            $('#include' + $this).removeAttr("disabled").css('opacity', 1);
            $(".select2-selection:eq("+$eq+")").css('opacity', 0.5);

        }
    });

    @if(@$errors->all())
        $('html, body').animate({
            scrollTop: $('.is-invalid:visible:first').offset().top  - 250
        }, 1);
    @endif


</script>
@endpush
