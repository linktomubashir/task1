{{ Form::open(['url' => $action, 'method' => $method]) }}
<div class="modal-body">
    <div class="row">
        
    <div class="form-group col-12">
        {{ Form::label('name', 'Product Name', ['class' => 'form-label']) }}
        {{ Form::text('name', $product->name ?? '', ['class' => 'form-control', 'required' => 'required','placeholder' => 'Enter product name']) }}
    </div>

    <div class="form-group col-12">
        {{ Form::label('description', 'Description', ['class' => 'form-label']) }}
        {{ Form::textarea('description', $product->description ?? '', ['class' => 'form-control','placeholder' => 'Enter product Description','rows'=>'3']) }}
    </div>

    <div class="form-group col-6">
        {{ Form::label('currency', 'Currency', ['class' => 'form-label']) }}
        {{ Form::select('currency', [
            'usd' => 'USD - United States Dollar',
            'eur' => 'EUR - Euro',
            'gbp' => 'GBP - British Pound Sterling',
            'jpy' => 'JPY - Japanese Yen',
            'pkr' => 'PKR - Pakistani Rupee'
        ], $price->currency ?? '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select a Currency']) }}
    </div>
    
    <div class="form-group col-6">
        {{ Form::label('price', 'Price', ['class' => 'form-label']) }}
        {{ Form::number('price', $price->unit_amount ?? '', ['class' => 'form-control', 'step' => '0.01', 'required' => 'required','placeholder' => 'Enter price']) }}
    </div>

    <div class="form-group col-12">
        {{ Form::label('interval', 'Billing Interval', ['class' => 'form-label']) }}
        {{ Form::select('interval', ['day' => 'Daily', 'week' => 'Weekly', 'month' => 'Monthly', 'year' => 'Yearly', 'lifetime' => 'Lifetime'], $price->interval ?? '', ['class' => 'form-control', 'required' => 'required','placeholder' => 'Select a Interval']) }}
    </div>
</div>

<div class="modal-footer">
    {{ Form::button('Cancel', ['class' => 'btn btn-light', 'data-bs-dismiss' => 'modal']) }}
    {{ Form::submit(isset($product) ? 'Update Product' : 'Create Product', ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}
