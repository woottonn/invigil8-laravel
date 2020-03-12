<h4>Profile Information</h4>
<hr>
<div class="row">

    <div class="form-group col-md-6 col-lg-4">
        <label for="firstname">First Name</label>
        <input type="text" autocomplete="off" class="form-control @error('firstname') is-invalid @enderror" name="firstname" required value="{{ old('firstname') ?? $user->firstname }}">
        <div class="text-danger">{{ $errors->first('firstname') }}</div>
    </div>
    <div class="form-group col-md-6 col-lg-4">
        <label for="lastname">Last Name</label>
        <input type="text" autocomplete="off" class="form-control @error('lastname') is-invalid @enderror" name="lastname" required value="{{ old('lastname') ?? $user->lastname }}">
        <div class="text-danger">{{ $errors->first('lastname') }}</div>
    </div>
    <div class="form-group col-md-6 col-lg-4">
        <label for="email">Email</label>
        <input type="email" autocomplete="nah" class="form-control @error('email') is-invalid @enderror" name="email" title="user@email.com"  required value="{{ old('email') ?? $user->email }}">
        <div class="text-danger">{{ $errors->first('email') }}</div>
    </div>
    <div class="form-group col-md-6 col-lg-4">
        <label for="password">Password</label>
        <input type="password" autocomplete="off" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}">
        <div class="text-danger">{{ $errors->first('password') }}</div>
    </div>
    @if($type!=="edit")
    <div class="form-group col-md-6 col-lg-4">
        <label for="password-conf">Confirm Password</label>
        <input id="password-confirm" autocomplete="off" type="password" class="form-control" name="password_confirmation" value="{{ old('password-confirm') }}">
    </div>
    @endif
    @role('Super Admin')
        <div class="form-group col-md-6 col-lg-4">
            <label for="centre">Centre</label>
            <select class="form-control" id="centre_id" name="centre_id">
                @if(!$centres->isEmpty())
                    @foreach($centres as $centre)
                        <option
                            @if($centre->id==$user->centre_id)
                            selected="selected"
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
    <div class="form-group col-md-12">
        Roles
        <hr>
        @if(!$roles->isEmpty())
            @foreach($roles as $role)
                <div class="form-check d-inline-block mr-3">
                    <input class="form-check-input"
                           @if(@in_array($role->id,$user_roles))
                           checked
                           @endif
                           type="checkbox" value="{{ $role->id }}" id="role_{{ $role->id }}" name="roles[]">
                    <label class="form-check-label" for="role_{{ $role->id }}">
                        {{ $role->name }}
                    </label>
                </div>
            @endforeach
        @else
            <p>There are no roles in the system.</p>
        @endif
    </div>
</div>
<hr>
<button type="submit" class="btn btn-primary">Submit</button>
@csrf

@push('scripting')
    <script>
        $(document).ready(function() {
            $('input').removeAttr('autocomplete');
        });
    </script>
@endpush
