@extends('layouts.auth')
@section('title', 'Forgot Password | Task 1')

@section('content')
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <a href="{{ url('/') }}" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"><b>Task 1</b></h1>
                </a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>

                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div>
                        <div class="input-group">
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                    </div>

                    <div class="mt-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Email Password Reset Link</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection