<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subscriptionModalLabel">Choose Your Subscription Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="subscription-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                @foreach ($products as $product)
                                    @foreach ($product->price as $price)
                                        <div class="col-md-4 mb-3">
                                            <div class="card text-center plan-card shadow-lg">
                                                <div
                                                    class="card-header 
                                                        @if ($price->interval === 'month') bg-primary @elseif($price->interval === 'year') bg-success @else bg-danger @endif
                                                    ">
                                                    <h6>{{ $product->name }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <h4 class="card-title text-center mb-3">{{ $price->unit_amount }}
                                                        /{{ ucfirst($price->interval) }}</h4><br>
                                                    <ul class="list-unstyled">
                                                        <li>{{ $product->description }}</li>
                                                    </ul>
                                                    <input type="radio" name="plan" value="{{ $price->stripe_id }}"
                                                        required class="plan-radio plan"
                                                        @if (auth()->user()->subscription('default') &&
                                                                auth()->user()->subscription('default')->stripe_price == $price->stripe_id) checked @endif>
                                                    <label for="plan_{{ $price->stripe_id }}">Select Plan</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-primary" id="continue-button">
                            Continue <span id="spinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Enter Your Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('stripe_products.store') }}" method="POST" id="payment-form">
                    @csrf
                    <input type="hidden" name="plan_id" id="plan_id">
                    <div class="row">
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
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary" id="payment-button">
                            Pay Now <span id="payment-spinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    $(document).ready(function() {

        $('#subscriptionModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#subscriptionModal').modal('show');

        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var cardNumber = elements.create('cardNumber');
        var expiryDate = elements.create('cardExpiry');
        var cvc = elements.create('cardCvc');

        cardNumber.mount('#card-number');
        expiryDate.mount('#expiry-date');
        cvc.mount('#cvc');
        var selectedPlanId = null;

        $('.plan').on('change', function() {
            selectedPlanId = $(this).val();
        });

        $('#continue-button').on('click', function() {
            if (selectedPlanId) {
                $('#plan_id').val(selectedPlanId);
                $('#subscriptionModal').modal('hide');
                $('#paymentModal').modal('show');
            } else {
                alert("Please select a plan before continuing.");
            }
        });

        $('#payment-form').on('submit', function(event) {
            event.preventDefault();
            $('#payment-spinner').removeClass('d-none');

            stripe.createPaymentMethod('card', cardNumber).then(function(result) {
                if (result.error) {
                    $('#card-errors').text(result.error.message);
                    $('#payment-spinner').addClass('d-none');
                } else {
                    $('<input>', {
                        type: 'hidden',
                        name: 'payment_method',
                        value: result.paymentMethod.id
                    }).appendTo('#payment-form');
                    $('#payment-form')[0].submit();
                }
            });
        });
    });
</script>
