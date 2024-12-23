{{ Form::open([
    'url' => $action,
    'method' => $method,
]) }}
<div class="modal-body">
    <div class="form-group">
        {{ Form::label('name', __('Role Name'), ['class' => 'form-label']) }}
        <div class="form-icon-user">
            {{ Form::text('name', $role->name ?? '', [
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => __('Enter Role Name'),
            ]) }}
        </div>
    </div>

    <div class="form-group">
        @if ($permissions->isNotEmpty())
            <h6 class="my-3">{{ __('Assign Permissions to Roles') }}</h6>
            <div class="permissions-list">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="checkall">
                    <label class="form-check-label" for="checkall">{{ __('Select All') }}</label>
                </div>
                <hr>
                <div class="row">
                    @foreach ($permissions as $key => $permission)
                        <div class="col-md-2 mb-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input permission-checkbox" name="permissions[]"
                                    value="{{ $permission->id }}" id="permission{{ $permission->id }}"
                                    @if (in_array($permission->id, $assignedPermissions ?? [])) checked @endif>
                                <label class="form-check-label" for="permission{{ $permission->id }}">
                                    {{ ucfirst($permission->name) }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-muted">{{ __('No permissions available') }}</p>
        @endif
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(isset($role) ? __('Update Role') : __('Create Role'), ['class' => 'btn btn-primary']) }}
</div>

{{ Form::close() }}

<script>
    $(document).ready(function() {
        $("#checkall").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });
</script>
