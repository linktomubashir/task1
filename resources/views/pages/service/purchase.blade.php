@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment Details</h5>
                </div>
                {{ Form::open(['url' => $action, 'method' => $method, 'id' => 'payment-form']) }}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('name', __(' Full Name'), ['class' => 'col-form-label']) }}
                                {{ Form::text('name', $row->name ?? null, ['class' => 'form-control', 'placeholder' => __('Enter your full name')]) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('email', 'Email Address', ['class' => 'col-form-label']) }}
                                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'example@gmail.com']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('quantity', 'Quantity', ['class' => 'col-form-label']) }}
                                {{ Form::number('quantity', 1, ['class' => 'form-control', 'id' => 'quantity', 'min' => 1, 'required' => true]) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('total', 'Total', ['class' => 'col-form-label']) }}
                                {{ Form::Text('total', null, ['class' => 'form-control', 'id' => 'total', 'min' => 1, 'disabled' => 'disabled']) }}
                            </div>
                        </div>
                        {{ form::hidden('id', $item->id) }}

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="card-number" class="form-label">Card Number</label>
                                <div id="card-number" class="form-control"></div>
                                <div id="card-number-errors" role="alert" class="text-danger mt-2"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expiry-date" class="form-label">Expiration Date</label>
                                <div id="expiry-date" class="form-control"></div>
                                <div id="expiry-date-errors" role="alert" class="text-danger mt-2"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cvc" class="form-label">CVC</label>
                                <div id="cvc" class="form-control"></div>
                                <div id="cvc-errors" role="alert" class="text-danger mt-2"></div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    {{ Form::submit('Pay', ['class' => 'btn btn-primary', 'id' => 'submit-button']) }}
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $(document).ready(function() {
            var stripe = Stripe('{{ env('STRIPE_KEY') }}');
            var elements = stripe.elements();

            var cardNumber = elements.create('cardNumber');
            var expiryDate = elements.create('cardExpiry');
            var cvc = elements.create('cardCvc');

            cardNumber.mount('#card-number');
            expiryDate.mount('#expiry-date');
            cvc.mount('#cvc');

            $('#payment-form').on('submit', function(event) {
                event.preventDefault();

                stripe.createToken(cardNumber).then(function(result) {
                    if (result.error) {
                        $('#card-number-errors').text(result.error.message);
                    } else {
                        var token = result.token;
                        var hiddenInput = $('<input>', {
                            type: 'hidden',
                            name: 'stripeToken',
                            value: token.id
                        });
                        $('#payment-form').append(hiddenInput);

                        $('#payment-form').off('submit').submit();
                    }
                });
            });

            var itemAmount = {{ $item->amount ?? 0 }};
            $('#quantity').on('input', function() {
                let total = ($(this).val() * itemAmount).toFixed(2);
                $('#total').val('$' + total);
                $('#submit-button').val('Pay $' + total); 
            }).trigger('input');

        });
    </script>
@endpush
