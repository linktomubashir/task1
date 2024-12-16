{{ Form::open(['url' => $action, 'method' => $method]) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <!-- Model Name -->
            <div class="form-group">
                {{ Form::label('name', 'Brand Name', ['class' => 'col-form-label']) }}
                {{ Form::text('name', old('name', $row->name ?? ''), ['class' => 'form-control','placeholder' => 'Enter Brand name', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <!-- Cancel Button -->
    {{ Form::button('Cancel', ['class' => 'btn btn-light', 'data-bs-dismiss' => 'modal']) }}
    {{ Form::submit(isset($row) ? __('Update') : __('Create'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}
