@extends('layouts.auth')

@section('title', 'Login | Task 1')

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
                <p class="login-box-msg">Sign in to start your session</p>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <div class="input-group">
                            <input type="email" id="email" class="form-control" placeholder="Email" name="email"
                                :value="old('email')" required autofocus autocomplete="username">
                            <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <div class="input-group"> 
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Password" required autocomplete="current-password">
                            <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>

                    <!-- Remember Me -->
                    <div class="row mt-4">
                        <div class="block col-8 d-inline-flex align-items-center">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                    name="remember">
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                            </label>
                        </div>
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Sign In</button>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
                <!-- Register Link -->
                <div class="text-center mt-4">
                    <p>Don't have an account? <a href="{{ route('register') }}" class="text-primary">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
