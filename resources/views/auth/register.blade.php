@extends('layouts.auth')

@section('title', 'Register | Task 1')

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
                <p class="login-box-msg">Create your account</p>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <div class="input-group">
                            <input type="text" id="name" class="form-control" placeholder="Name" name="name" 
                                :value="old('name')" required autofocus autocomplete="name">
                            <div class="input-group-text"> <span class="bi bi-person-fill"></span> </div>
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <div class="input-group">
                            <input type="email" id="email" class="form-control" placeholder="Email" name="email"
                                :value="old('email')" required autocomplete="username">
                            <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" 
                                placeholder="Password" required autocomplete="new-password">
                            <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <div class="input-group">
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                class="form-control" placeholder="Confirm Password" required autocomplete="new-password">
                            <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                    </div>

                    <!-- Register Button -->
                    <div class="mt-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </div>

                    <!-- Already Registered Link -->
                    <div class="flex items-center justify-end mt-4">
                        <p>Already have an account? <a href="{{ route('login') }}" class="text-primary">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
