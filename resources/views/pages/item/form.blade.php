{{ Form::open(['url' => $action, 'method' => $method]) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
                {{ Form::text('name', $row->name ?? null, ['class' => 'form-control', 'placeholder' => __('Enter item name')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'col-form-label']) }}
                {{ Form::number('amount', $row->amount ?? null, ['class' => 'form-control', 'placeholder' => __('Enter Amount')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mt-3">
                {{ Form::label('brand_id', __('Brand Name'), ['class' => 'col-form-label']) }}
                {{ Form::select('brand_id', $brands->pluck('name', 'id')->toArray(), old('brand_id', $row->brand_id ?? null), ['class' => 'form-control','id' => 'brand', 'placeholder' => 'Select Brand', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('model_id', __('Model Name')) }}
                {{ Form::select('model_id', ['' => '-- Select Brand First --'], '', ['class' => 'form-control', 'id' => 'model']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ isset($row) ? __('Update') : __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}


<script>
    $(document).ready(function() {
        if ($('#brand').val()) {
            loadModels($('#brand').val());
        }
        $('#brand').change(function() {
            var brandId = $(this).val(); 
            if (brandId) {
                loadModels(brandId);  
            } else {
                $('#model').html('<option value="">-- Select Brand First --</option>'); // Reset model dropdown
            }
        });

        // Function to load models based on selected brand
        function loadModels(brandId) {
            $('#model').html('<option value="">Searching...</option>');
            $.ajax({
                url: "{{ route('item.search_model') }}", // Correct AJAX URL
                type: "GET", 
                data: {
                    _token: "{{ csrf_token() }}", // CSRF token for security
                    brand_id: brandId
                },
                success: function(result) {
                    console.log('AJAX request successful', result);
                    $('#model').html('<option value="">Select model</option>'); // Reset the model dropdown
                    $.each(result.models, function(index, model) {
                        $('#model').append('<option value="' + model.id + '" ' + (model.id == "{{ old('model', $row->model_id ?? '') }}" ? 'selected' : '') + '>' + model.name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    $('#model').html('<option value="">Error Not Found</option>');
                    console.error('Error loading models:', status, error);
                }
            });
        }
    });
</script>

