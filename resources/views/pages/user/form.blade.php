{{ Form::open(['url' => $action, 'method' => $method]) }}
<div class="modal-body">
    <div class="row">
        @if(!isset($row))   
    <div class="form-group col-6">
        @else
    <div class="form-group col-12">
        @endif
        {{ Form::label('name', 'User Name', ['class' => 'col-form-label']) }}
        {{ Form::text('name', old('name', $row->name ?? ''), ['class' => 'form-control', 'placeholder' => 'Enter User name', 'required' => 'required']) }}
    </div>
@if(!isset($row))
    <div class="form-group col-6">
        {{ Form::label('email', 'Email Address', ['class' => 'col-form-label']) }}
        {{ Form::email('email', old('email', $row->email ?? ''), ['class' => 'form-control', 'placeholder' => 'Email', 'required' => 'required', 'autocomplete' => 'username']) }}
    </div>
    @endif
    <div class="form-group ">
        {{ Form::label('role', 'Role', ['class' => 'col-form-label']) }}
        {{ Form::select('role', $roles->pluck('name', 'name')->toArray(), old('role', $row->role ?? ''), ['class' => 'form-control', 'required' => 'required']) }}
    </div>
    @if(!isset($row))
    <div class="form-group col-6">
        {{ Form::label('password', 'Password', ['class' => 'col-form-label']) }}
        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'required' => 'required', 'autocomplete' => 'new-password']) }}
    </div>

    <div class="form-group col-6">
        {{ Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-form-label']) }}
        {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm Password', 'required' => 'required', 'autocomplete' => 'new-password']) }}
    </div>
@endif
    
</div>
</div>

<div class="modal-footer">
    {{ Form::button('Cancel', ['class' => 'btn btn-light', 'data-bs-dismiss' => 'modal']) }}
    {{ Form::submit(isset($row) ? __('Update') : __('Register'), ['class' => 'btn btn-primary']) }}
</div>

{{ Form::close() }}
