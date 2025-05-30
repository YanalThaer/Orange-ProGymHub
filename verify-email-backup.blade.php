@extends('layouts.dashboard')
@section('title', 'Verify User Email')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header bg-primary text-white p-4" style="background-color: #0d6efd !important;">
                    <h4 class="mb-0 fw-bold">{{ __('Verify User Email Address') }}</h4>
                </div>

                <div class="card-body p-4">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-user-check fs-1" style="color: #0d6efd;"></i>
                        </div>
                        <h5 class="text-dark fw-bold">{{ __('Verify User Account') }}</h5>
                        <p class="text-muted">
                            {{ __('We\'ve sent a verification code to the user\'s email address.') }}
                            {{ __('Please enter the 6-digit code below to complete the user registration.') }}
                        </p>

                        <div class="mt-2 p-3 bg-light rounded-3">
                            <p class="mb-0"><strong>{{ __('Email:') }}</strong> {{ $userData['email'] }}</p>
                            <p class="mb-0"><strong>{{ __('User:') }}</strong> {{ $userData['name'] }}</p>
                        </div>
                    </div>
                    <form action="{{ route('club.users.verify.email') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-4">
                            <label for="verification_code" class="form-label fw-semibold mb-2">{{ __('Verification Code') }}</label>
                            <div class="verification-code-container">
                                <input type="text" id="digit-1" class="verification-digit" maxlength="1" autofocus>
                                <input type="text" id="digit-2" class="verification-digit" maxlength="1">
                                <input type="text" id="digit-3" class="verification-digit" maxlength="1">
                                <input type="text" id="digit-4" class="verification-digit" maxlength="1">
                                <input type="text" id="digit-5" class="verification-digit" maxlength="1">
                                <input type="text" id="digit-6" class="verification-digit" maxlength="1">
                                <input type="hidden" id="verification_code" name="verification_code" class="@error('verification_code') is-invalid @enderror" required>
                            </div>

                            @error('verification_code')
                            <span class="invalid-feedback d-block mt-1">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold" style="background-color: #0d6efd;">
                                {{ __('Verify Email & Complete Registration') }}
                            </button>
                        </div>
                    </form>
                    <div class="text-center border-top pt-4">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <i class="fas fa-clock me-2 text-muted"></i>
                            <p class="mb-0 text-muted">{{ __('Code expires in:') }} <span id="countdown" class="fw-bold">30:00</span></p>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-3 mb-2">
                            <i class="fas fa-question-circle me-2 text-muted"></i>
                            <p class="mb-0 text-muted">{{ __('Didn\'t receive the code?') }}</p>
                        </div>
                        <a href="{{ route('club.users.resend.verification') }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>{{ __('Resend Verification Code') }}
                        </a>
                        <a href="{{ route('club.users.create') }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-times me-2"></i>{{ __('Cancel Registration') }}
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light p-3">
                    <div class="text-center">
                        <p class="mb-0 text-muted">
                            <small><i class="fas fa-info-circle me-1"></i>{{ __('Note: User will only be registered after successful verification.') }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
<style>
    .verification-code-container {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        justify-content: center;
    }

    .verification-digit {
        width: 50px;
        height: 60px;
        border: 2px solid #ddd;
        border-radius: 8px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        transition: all 0.2s;
    }

    .verification-digit:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: none;
    }

    .verification-digit.filled {
        background-color: #f8f9fa;
    }

    @media (max-width: 576px) {
        .verification-code-container {
            gap: 6px;
        }

        .verification-digit {
            width: 40px;
            height: 50px;
            font-size: 20px;
        }
    }
</style>
@endpush