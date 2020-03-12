<div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" required value="{{ old('name') ?? $permission->name }}">
    <div class="text-danger">{{ $errors->first('name') }}</div>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
@csrf
