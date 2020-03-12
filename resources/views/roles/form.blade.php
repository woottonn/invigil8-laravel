<div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" required value="{{ old('name') ?? $role->name }}">
    <div class="text-danger">{{ $errors->first('name') }}</div>
</div>
<h4 class="mt-4">Permissions</h4>
<hr>
        @if(! $permissions->isEmpty())
            @foreach($permissions as $permission)
                <div class="form-check d-block mr-3">
                    <input class="form-check-input"
                        @if(@in_array($permission->id,$role_permissions))
                            checked
                        @endif
                           type="checkbox" value="{{ $permission->id }}" id="permission_{{ $permission->id }}" name="permissions[]">
                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                        {{ $permission->name }}
                    </label>
                </div>
            @endforeach
        @else
            <p>There are no permissions in the system.</p>
        @endif
<hr>
<button type="submit" class="btn btn-primary">Submit</button>
@csrf
