<div class="modal-body p-4">
    <div class="text-center">
        <h5 class="fw-bold">Subscription Details</h5>
        <p class="text-muted">Review your subscription plan and its details below.</p>
    </div>

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    <i class="bi bi-box-seam text-primary fs-3"></i>
                </div>
                <div>
                    <h6 class="mb-0 fs-5 fw-semibold text-dark">{{ $product->name }}</h6>
                    <small class="text-muted">{{ $product->description }}</small>
                </div>
            </div>

            <div class="mb-3">
                <h6 class="text-success fs-5">
                    <i class="bi bi-currency-dollar"></i> 
                    {{ $price->unit_amount  }} {{ strtoupper($price->currency) }}
                </h6>
                <small class="text-muted">Billed per {{ ucfirst($price->interval) }}</small>
            </div>

            <div class="mb-3">
                <p class="text-muted mb-1">
                    <strong>Status:</strong> 
                    @if($data['subscription']->stripe_status == 'active')
                        <span class="text-success">Active</span>
                    @elseif($data['subscription']->stripe_status == 'paused')
                        <span class="text-warning">Paused</span>
                    @else
                        <span class="text-danger">Inactive</span>
                    @endif
                </p>
            </div>

            <div class="mb-3">
                <p class="text-muted mb-1">
                    <strong>End Date:</strong> {{$data['endDate']}}
                </p>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{-- <button class="btn btn-warning me-3 rounded-3 action-btn" data-action="pause">
            <i class="bi bi-pause-circle me-2"></i> Pause 
        </button>
    
        <button  class="btn btn-success me-3 rounded-3 action-btn" data-action="resume">
            <i class="bi bi-play-circle me-2"></i> Resume 
        </button>
    
        <button  class="btn btn-danger me-3 rounded-3 action-btn" data-action="delete">
            <i class="bi bi-trash me-2"></i> Delete 
        </button>
     --}}
        <a href="{{route('subscribe.plan.show')}}"  class="btn btn-info rounded-3">
            <i class="fas fa-arrows-alt-v me-2"></i> Upgrade/Downgrade
        </a>
    </div>
    
<script>
    $(document).ready(function() {
    $('.action-btn').on('click', function() {
        var action = $(this).data('action'); 
     
        $.ajax({
            url: '{{route('subscribe.update')}}',
            type: 'POST',  
            data: {
                _token: '{{ csrf_token() }}', 
                action: action 
            },
            success: function(response) {
                toastr.success('Action ' + action + ' completed successfully!');
                // location.reload();
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error('Something went wrong!');
            }
        });
    });
});

</script>