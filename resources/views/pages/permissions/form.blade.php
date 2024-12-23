{{ Form::open(['url' => $action,'method' => $method,]) }}
<div class="modal-body">
    <div class="form-group">
        {{ Form::label('name', __('Permission Name'), ['class' => 'col-form-label']) }}
        <div class="form-icon-user">
            {{ Form::text('name', old('name', $permission->name ?? ''), ['class' => 'form-control','required' => 'required','placeholder' => __('Enter Permission Name'),]) }}
        </div>
    </div>

    <div class="form-group">
        @if (!$roles->isEmpty())
            <h6>{{ __('Assign Permission to Roles') }}</h6>
            <div class="roles-list">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="checkall">
                    <label class="form-check-label" for="checkall">{{ __('Select All') }}</label>
                </div>
                <hr>
                <div class="row">
                    @foreach ($roles as $role)
                        <div class="col-md-2 mb-2">
                            <div class="form-check">
                                {{ Form::checkbox('roles[]', $role->id, in_array($role->id, old('roles', $assignedRoles ?? [])), [
                                    'class' => 'form-check-input role-checkbox',
                                    'id' => 'role'.$role->id
                                ]) }}
                                {{ Form::label('role'.$role->id, ucfirst($role->name), ['class' => 'form-check-label']) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-muted">{{ __('No roles available') }}</p>
        @endif
    </div>
</div>

<div class="modal-footer">
    {{ Form::button(__('Cancel'), ['class' => 'btn btn-light', 'data-bs-dismiss' => 'modal']) }}
    {{ Form::submit(isset($permission) ? __('Update Permission') : __('Create Permission'), ['class' => 'btn btn-primary']) }}
</div>

{{ Form::close() }}

<script>
    $(document).ready(function() {
        $("#checkall").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });
</script>
