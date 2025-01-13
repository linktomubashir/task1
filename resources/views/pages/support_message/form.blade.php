{{ Form::open(['url' => $action, 'method' => $method]) }}
<div class="modal-body">
    <div class="form-group">
        {{ Form::label('message', 'Brand Message', ['class' => 'col-form-label']) }}
        {{ Form::textarea('message',null, ['class' => 'form-control', 'placeholder' => 'Enter Brand Message', 'required' => 'required']) }}
    </div>
</div>
<div class="modal-footer">
    {{ Form::button('Cancel', ['class' => 'btn btn-light', 'data-bs-dismiss' => 'modal']) }}
    {{ Form::submit(__('Send'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}

