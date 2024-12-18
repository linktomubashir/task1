@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <p class="mb-0">Send Email</p>
                        <a class="btn btn-sm btn-primary ms-auto" title="History" data-url="{{ route('email.create') }}"
                        data-size="small" data-ajax-popup="true" data-title="{{ __('History') }}"
                        data-bs-toggle="tooltip">View History </a>
                </div>
                <div class="card-body">
                    {{ Form::open(['url' => route('email.store'), 'method' => 'post', 'id' => 'emailForm']) }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('to', __('Recipient Email'), ['class' => 'col-form-label']) }}
                                {{ Form::email('to', null, ['class' => 'form-control', 'placeholder' => __('Enter recipient email address')]) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label']) }}
                                {{ Form::text('subject', null, ['class' => 'form-control', 'placeholder' => __('Enter email subject')]) }}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('message', __('Message'), ['class' => 'col-form-label']) }}
                                {{ Form::textarea('message', null, ['class' => 'form-control', 'placeholder' => __('Enter your message'), 'rows' => 4]) }}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('attachments', __('Attachments'), ['class' => 'col-form-label']) }}
                                {{ Form::file('attachments', ['class' => 'form-control', 'multiple' => 'multiple']) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 d-flex justify-content-end">
                        {!! Form::submit('Send Email', ['class' => 'btn btn-primary mt-3', 'id' => 'submitBtn']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#emailForm').on('submit', function(e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    let submitButton = $('#submitBtn');
                    submitButton.prop('disabled', true);
                    submitButton.html('Sending...');

                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                // $('#emailForm')[0].reset();
                            } else {
                                toastr.error('Something went wrong!');
                                console.log(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr
                                .responseJSON.message : 'An error occurred. Please try again.';
                            toastr.error(errorMessage);
                        },
                        complete: function() {
                            submitButton.prop('disabled', false);
                            submitButton.html('Send Email');
                        }
                    });
                });
            });
        </script>
    @endpush
