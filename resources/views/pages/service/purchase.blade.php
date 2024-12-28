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
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('email', 'Email Address', ['class' => 'col-form-label']) }}
                                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required','placeholder' => 'example@gmail.com']) }}
                            </div>
                            {{ form::hidden('amount', $item->amount) }}
                            {{ form::hidden('desc', $item->name) }}
                            <!-- Stripe Card Number -->
                            <div class="form-group mt-3">
                                <label for="card-number" class="form-label">Card Number</label>
                                <div id="card-number" class="form-control"></div>
                                <div id="card-number-errors" role="alert" class="text-danger mt-2"></div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="expiry-date" class="form-label">Expiration Date</label>
                                <div id="expiry-date" class="form-control"></div>
                                <div id="expiry-date-errors" role="alert" class="text-danger mt-2"></div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="cvc" class="form-label">CVC</label>
                                <div id="cvc" class="form-control"></div>
                                <div id="cvc-errors" role="alert" class="text-danger mt-2"></div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    {{ Form::submit('Pay $' . number_format($item->amount, 2), ['class' => 'btn btn-primary', 'id' => 'submit-button']) }}
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
        });
    </script>
@endpush
