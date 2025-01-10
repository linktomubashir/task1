@extends('layouts.auth')

@section('title', 'Verify Email | Task 1')

@section('content')
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <a href="{{ url('/') }}"
                    class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"><b>Task 1</b></h1>
                </a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Verify your email</p>

                @if (session('message'))
                    <p class="text-success">{{ session('message') }}</p>
                @endif

                <form id="verificationForm" action="#">
                    @csrf
                    @if (session('verification_token'))
                        <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
                            <div>
                                We have sent an email to <strong>{{ session('email') }}</strong>. Please check your inbox
                                for the verification code.
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                        @endif
                        <div>
                            <div class="input-group">
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Enter your email" value="{{ old('email', session('email')) }}" required>
                                <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                        </div>

                        <div class="mt-4">
                            <div class="d-grid gap-2">
                                <button type="submit" id="sendCode" class="btn btn-primary">Send Verification
                                    Code</button>
                            </div>
                        </div>
                    </form>
             
                    {{-- <form method="POST" action="#">
                        @csrf
                        
                        <div>
                            <div class="input-group">
                                <input type="text" id="code" name="code" class="form-control"
                                    placeholder="Enter verification code" maxlength="6" required>
                                <div class="input-group-text"> <span class="bi bi-key"></span> </div>
                            </div>
                            <x-input-error :messages="$errors->get('code')" class="mt-2 text-danger" />
                        </div>

                        <div class="row my-4">
                            <div class="col-5"> <button type="button" class="btn btn-link" id="resendBtn" disabled>Resend
                                    <span id="timer"></span></button> </div>
                            <div class="ms-auto col-6">
                                <button type="submit" id="verifyBtn" class="btn btn-primary" disabled>Verify Email</button>
                            </div>
                        </div>
                    </form> --}}
                
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#verificationForm').on('submit', function(e) {
                e.preventDefault();
                let btn = $('#sendCode');
                btn.text('Sending...').prop('disabled', true);
                let email = $('#email').val();
                sendCode(email);
            });

            $('#resendBtn').on('click', function() {
                $(this).prop('disabled', true);
                startTimer();
                let email = "{{ session('email') }}";
                sendCode(email);
            });

            function sendCode(email, button = null) {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('register.send.code') }}",
                    data: {
                        email: email
                    },
                    success: function(response) {
                        console.log('Success:', response);
                        if (button) {
                            button.text('Send Verification Code').prop('disabled', false);
                        }
                        location.reload()
                    },
                    error: function(error) {
                        console.log('Error:', error);
                        if (button) {
                            button.text('Send Verification Code').prop('disabled', false);
                        }
                    }
                });
            }
            $('#code').on('input', function() {
                $('#verifyBtn').prop('disabled', $(this).val().length !== 6);
            });

            function startTimer() {
                let timeLeft = 60;
                const timerInterval = setInterval(function() {
                    timeLeft--;
                    $('#timer').text(timeLeft + 's');
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        $('#resendBtn').prop('disabled', false);
                        $('#timer').text('');
                    }
                }, 1000);
            }
            startTimer();
        });
    </script>
@endpush
