@extends('layouts.auth')

@section('title', 'Reset Password | Task 1')

@section('content')
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <a href="{{ url('/') }}" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"><b>Task 1</b></h1>
                </a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Reset your password</p>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div>
                        <div class="input-group">
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="new-password">
                            <div class="input-group-text"><span class="bi bi-lock"></span></div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <div class="input-group">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required autocomplete="new-password">
                            <div class="input-group-text"><span class="bi bi-lock"></span></div>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                    </div>

                    <div class="mt-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
