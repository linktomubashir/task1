@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Profile') }}
                </div>
                <div class="card-body">
                    <!-- Profile Update Form -->
                    {!! Form::open([
                        'url' => route('profile.update'),
                        'method' => 'PATCH', 'files' => true,
                    ]) !!}

                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('name', __('Name:')) !!}
                                {!! Form::text('name', old('name', $user->name), [
                                    'placeholder' => __('Enter your name'),
                                    'required' => 'required',
                                    'class' => 'form-control',
                                ]) !!}
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('email', __('Email:')) !!}
                                {!! Form::email('email', old('email', $user->email), [
                                    'placeholder' => __('Enter your email'),
                                    'required' => 'required',
                                    'class' => 'form-control',
                                ]) !!}
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div>
                                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                            {{ __('Your email address is unverified.') }}
                                            <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                                                {{ __('Click here to re-send the verification email.') }}
                                            </button>
                                        </p>
                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                                {{ __('A new verification link has been sent to your email address.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('image', __('Image:')) !!}
                                {!! Form::file('image', ['class' => 'form-control',isset($user) ? '' : 'required' => 'required',
                                ]) !!}
                                    <small class="form-text text-muted">Leave blank if you don't want to update the image.image.</small>
                                
                            </div>
                        </div>
                            @if (isset($user) && $user->image)
                                        
                                    <div class="col-md-6 mt-2">
                                        <img src="{{ asset('storage/' . $user->image) }}" width="100px" height="auto"
                                            alt="Slider Image">
                                    </div>
                                @endif
                       

                    </div>

                    <!-- Save Button -->
                    <div class="col-md-12 d-flex justify-content-end">
                        {!! Form::submit(__('Save'), ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Update Password') }}
                        </div>
                        <div class="card-body">
                            <!-- Password Update Form -->
                            {!! Form::open([
                                'url' => route('password.update'),
                                'method' => 'PUT',
                            ]) !!}
            
                            <div class="row">
                                <!-- Current Password Field -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('current_password', __('Current Password:')) !!}
                                        {!! Form::password('current_password', [
                                            'placeholder' => __('Enter your current password'),
                                            'class' => 'form-control',
                                            'autocomplete' => 'current-password',
                                            'required' => 'required'
                                        ]) !!}
                                        @error('current_password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
            
                                <!-- New Password Field -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('password', __('New Password:')) !!}
                                        {!! Form::password('password', [
                                            'placeholder' => __('Enter your new password'),
                                            'class' => 'form-control',
                                            'autocomplete' => 'new-password',
                                            'required' => 'required'
                                        ]) !!}
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
            
                                <!-- Confirm Password Field -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('password_confirmation', __('Confirm Password:')) !!}
                                        {!! Form::password('password_confirmation', [
                                            'placeholder' => __('Confirm your new password'),
                                            'class' => 'form-control',
                                            'autocomplete' => 'new-password',
                                            'required' => 'required'
                                        ]) !!}
                                        @error('password_confirmation')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
            
                            </div>
            
                            <!-- Save Button -->
                            <div class="col-md-12 d-flex justify-content-end">
                                {!! Form::submit(__('Save'), ['class' => 'btn btn-primary']) !!}
                            </div>
            
                            {!! Form::close() !!}
            
                            <!-- Success Message (Optional) -->
                            @if (session('status') === 'password-updated')
                                <p class="mt-3 font-medium text-sm text-green-600 dark:text-green-400">
                                    {{ __('Password updated successfully.') }}
                                </p>
                            @endif
            
                        </div>
                    </div>
                </div>
            </div>
            
            

            <!-- Delete Account Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Delete Account') }}
                        </div>
                        <div class="card-body">
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                            </p>
        
                            <!-- Delete Account Button -->
                            <button
                                type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#confirm-user-deletion"
                            >
                                {{ __('Delete Account') }}
                            </button>
        
                            <!-- Modal -->
                            <div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="confirm-user-deletion-label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirm-user-deletion-label">{{ __('Are you sure you want to delete your account?') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                                            </p>
        
                                            <!-- Form for Deleting Account -->
                                            {!! Form::open([
                                                'url' => route('profile.destroy'),
                                                'method' => 'DELETE',
                                            ]) !!}
        
                                            <div class="form-group">
                                                {!! Form::label('password', __('Password:')) !!}
                                                {!! Form::password('password', [
                                                    'class' => 'form-control',
                                                    'placeholder' => __('Enter your password'),
                                                    'required' => 'required',
                                                ]) !!}
        
                                                @error('password')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                            {!! Form::submit(__('Delete Account'), ['class' => 'btn btn-danger']) !!}
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
        
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    </div>

@endsection
