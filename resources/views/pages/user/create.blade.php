{{ Form::open(['url' => route('register'), 'method' => 'POST']) }}
@csrf

<!-- Name -->
<div class="form-group">
    {{ Form::label('name', 'Name', ['class' => 'col-form-label']) }}
    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Name', 'required' => 'required', 'autofocus' => 'autofocus']) }}
    <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
</div>

<!-- Email Address -->
<div class="form-group mt-4">
    {{ Form::label('email', 'Email Address', ['class' => 'col-form-label']) }}
    {{ Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email', 'required' => 'required', 'autocomplete' => 'username']) }}
    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
</div>

<!-- Password -->
<div class="form-group mt-4">
    {{ Form::label('password', 'Password', ['class' => 'col-form-label']) }}
    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'required' => 'required', 'autocomplete' => 'new-password']) }}
    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
</div>

<!-- Confirm Password -->
<div class="form-group mt-4">
    {{ Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-form-label']) }}
    {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm Password', 'required' => 'required', 'autocomplete' => 'new-password']) }}
    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
</div>

<!-- Register Button -->
<div class="modal-footer mt-4">
    {{ Form::button('Cancel', ['class' => 'btn btn-light', 'data-bs-dismiss' => 'modal']) }}
    {{ Form::submit(__('Register'), ['class' => 'btn btn-primary']) }}
</div>

{{ Form::close() }}
