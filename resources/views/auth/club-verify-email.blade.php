@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden">                <div class="card-header bg-danger text-white p-4" style="background-color: #e60000 !important;">
                    <h4 class="mb-0 fw-bold">{{ __('Verify Club Email Address') }}</h4>
                </div>

                <div class="card-body p-4">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
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
                        <div class="mb-3">                            <i class="bi bi-building-check fs-1" style="color: #e60000;"></i>
                        </div>
                        <h5 class="text-dark fw-bold">{{ __('Verify Your Club Account') }}</h5>
                        <p class="text-muted">
                            {{ __('We\'ve sent a verification code to your club email address.') }}
                            {{ __('Please enter the 6-digit code below to verify your account.') }}
                        </p>
                        
                        <div class="mt-2 p-3 bg-light rounded-3">
                            <p class="mb-0"><strong>{{ __('Email:') }}</strong> {{ $club->email }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('club.verify.email', $club->encoded_id) }}" class="mb-4">
                        @csrf                        <div class="mb-4">
                            <label for="verification_code" class="form-label fw-semibold mb-2">{{ __('Verification Code') }}</label>
                            <div class="verification-code-container">
                                <input type="text" id="digit-1" class="verification-digit" maxlength="1" autofocus>
                                <input type="text" id="digit-2" class="verification-digit" maxlength="1">
                                <input type="text" id="digit-3" class="verification-digit" maxlength="1">
                                <input type="text" id="digit-4" class="verification-digit" maxlength="1">
                                <input type="text" id="digit-5" class="verification-digit" maxlength="1">
                                <input type="text" id="digit-6" class="verification-digit" maxlength="1">
                                <input type="hidden" id="verification_code" name="verification_code" class="@error('verification_code') is-invalid @enderror">
                            </div>
                            
                            @error('verification_code')
                                <span class="invalid-feedback d-block mt-1">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg py-3 fw-bold" style="background-color: #e60000;">
                                {{ __('Verify Email') }}
                            </button>
                        </div>
                    </form>                    <div class="text-center border-top pt-4">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <i class="bi bi-clock me-2 text-muted"></i>
                            <p class="mb-0 text-muted">{{ __('Code expires in:') }} <span id="countdown" class="fw-bold">30:00</span></p>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-3 mb-2">
                            <i class="bi bi-question-circle me-2 text-muted"></i>
                            <p class="mb-0 text-muted">{{ __('Didn\'t receive the code?') }}</p>
                        </div>
                        <a href="{{ route('club.resend.verification.code', $club->encoded_id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-envelope me-2"></i>{{ __('Resend Verification Code') }}
                        </a>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle digit verification inputs
        const form = document.querySelector('form');
        const digits = Array.from({ length: 6 }, (_, i) => document.getElementById(`digit-${i+1}`));
        const codeInput = document.getElementById('verification_code');
        
        // Update hidden field on form submit
        form.addEventListener('submit', function(e) {
            let code = '';
            digits.forEach(digit => {
                code += digit.value;
            });
            codeInput.value = code;
            
            if (code.length !== 6) {
                e.preventDefault();
                alert('Please enter all 6 digits of the verification code');
            }
        });
        
        // Handle digit input navigation
        digits.forEach((digit, index) => {
            // Only allow numbers
            digit.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                this.classList.toggle('filled', this.value !== '');
                
                // Auto focus next input
                if (this.value !== '' && index < digits.length - 1) {
                    digits[index + 1].focus();
                }
            });
            
            // Handle backspace
            digit.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace') {
                    if (this.value === '' && index > 0) {
                        digits[index - 1].focus();
                        digits[index - 1].value = '';
                        digits[index - 1].classList.remove('filled');
                        e.preventDefault();
                    } else {
                        this.classList.remove('filled');
                    }
                }
                
                // Allow arrow keys for navigation
                if (e.key === 'ArrowLeft' && index > 0) {
                    e.preventDefault();
                    digits[index - 1].focus();
                }
                
                if (e.key === 'ArrowRight' && index < digits.length - 1) {
                    e.preventDefault();
                    digits[index + 1].focus();
                }
            });
            
            // Handle paste event
            digit.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').trim();
                
                if (/^\d+$/.test(pasteData)) {
                    const pasteDigits = pasteData.split('').slice(0, 6);
                    
                    pasteDigits.forEach((value, i) => {
                        if (i < digits.length) {
                            digits[i].value = value;
                            digits[i].classList.add('filled');
                        }
                    });
                    
                    // Focus the next empty digit
                    if (pasteDigits.length < digits.length) {
                        digits[pasteDigits.length].focus();
                    }
                }
            });
        });
        
        // Initialize countdown timer
        let duration = 30*60; // 30 minutes
        let countdown = document.getElementById('countdown');
        let timer = null;
        
        if (countdown) {
            timer = setInterval(function () {
                let minutes = Math.floor(duration/60);
                let seconds = duration % 60;

                countdown.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                if (--duration < 0) {
                    clearInterval(timer);
                    countdown.textContent = "00:00";
                    countdown.classList.add('text-danger');
                    
                    // Show expired message
                    let expiredMessage = document.createElement('div');
                    expiredMessage.className = 'alert alert-warning mt-3';
                    expiredMessage.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>' + 
                        '{{ __("The verification code has expired. Please request a new one.") }}';
                    
                    let container = countdown.closest('.text-center');
                    container.insertBefore(expiredMessage, container.firstChild);
                }
            }, 1000);
        }
    });
</script>
@endpush
@endsection
