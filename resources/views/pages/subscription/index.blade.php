@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 mt-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Choose Your Subscription Plan</h5>
                </div>
                {{ Form::open(['url' => 'route{{'subscribe.store'}}', 'method' => 'POST', 'id' => 'payment-form']) }}
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="plan" class="form-label">Select Plan</label>
                        <select name="plan" id="plan" class="form-control" required>
                            <option value="plan_basic_id">Basic - $10/month</option>
                            <option value="plan_pro_id">Pro - $20/month</option>
                            <option value="plan_enterprise_id">Enterprise - $30/month</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="card-number" class="form-label">Card Number</label>
                        <div id="card-number" class="form-control"></div>
                        <div id="card-number-errors" class="text-danger mt-2"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="expiry-date" class="form-label">Expiration Date</label>
                        <div id="expiry-date" class="form-control"></div>
                        <div id="expiry-date-errors" class="text-danger mt-2"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="cvc" class="form-label">CVC</label>
                        <div id="cvc" class="form-control"></div>
                        <div id="cvc-errors" class="text-danger mt-2"></div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    {{ Form::submit('Subscribe', ['class' => 'btn btn-primary', 'id' => 'submit-button']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $(document).ready(function () {
            var stripe = Stripe('{{ env('STRIPE_KEY') }}');
            var elements = stripe.elements();

            var cardNumber = elements.create('cardNumber');
            var expiryDate = elements.create('cardExpiry');
            var cvc = elements.create('cardCvc');

            cardNumber.mount('#card-number');
            expiryDate.mount('#expiry-date');
            cvc.mount('#cvc');

            $('#payment-form').on('submit', function (event) {
                event.preventDefault();

                stripe.createToken(cardNumber).then(function (result) {
                    if (result.error) {
                        $('#card-number-errors').text(result.error.message);
                    } else {
                        var token = result.token.id;

                        $('<input>', {
                            type: 'hidden',
                            name: 'stripeToken',
                            value: token
                        }).appendTo('#payment-form');

                        $('#payment-form').off('submit').submit();
                    }
                });
            });
        });
    </script>
@endpush
