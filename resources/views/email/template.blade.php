<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Email Verification' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa; padding: 20px;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="card shadow-sm">

                    <div class="card-header bg-primary text-white text-center py-3">
                        <h1 class="h4">{{ config('app.name') ?? 'Task 1' }}</h1>
                        <p class="lead">{{ $emailTitle ?? 'Email Verification' }}</p>
                    </div>

                    <div class="card-body">
                        <p class="fs-5">{{ $greeting ?? 'Assalam-o-Alikum !' }}</p>
                        <p class="mb-4">
                            Thank you for registering with us! Please verify your email address by clicking the button below:
                        </p>
                        <div class="text-center">
                            <a href="{{ $verificationUrl }}" class="btn btn-success btn-lg">Verify Email</a>
                        </div>
                        <p class="mt-4 text-muted">
                            If you did not register for an account, please ignore this email.
                        </p>
                    </div>

                    <div class="card-footer bg-light text-center py-3">
                        <p class="mb-1">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        <p class="mb-0">
                            Need help? <a href="{{ $supportUrl ?? url('/support') }}" class="text-primary">Contact Support</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
