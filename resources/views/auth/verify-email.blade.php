@extends('layouts.app')
@section('title', 'ProGymHub - Verify Email Address')
@section('content')
<style>
    body {
        background-color: #121212;
        height: 100%;
        margin: 0;
        overflow: hidden;
    }

    .verification-card {
        background-color: #1f1f1f;
        border: none;
        padding: 2rem;
        border-radius: 10px;
        color: white;
        width: 100%;
        max-width: 500px;
    }

    .verification-code-container {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        justify-content: center;
    }

    .verification-digit {
        width: 50px;
        height: 60px;
        background-color: #2b2b2b;
        color: white;
        border: 2px solid #444;
        border-radius: 8px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        transition: all 0.2s;
    }

    .verification-digit:focus {
        border-color: #ff0000;
        box-shadow: 0 0 0 0.25rem rgba(255, 0, 0, 0.25);
        outline: none;
        background-color: #2b2b2b;
        color: white;
    }

    .verification-digit.filled {
        background-color: #3a3a3a;
        border-color: #ff0000;
    }

    .form-label {
        color: white;
        font-weight: 600;
    }

    .btn-primary {
        background-color: #ff0000;
        border-color: #ff0000;
        font-weight: bold;
        padding: 12px;
    }

    .btn-primary:hover {
        background-color: #cc0000;
        border-color: #cc0000;
    }

    .btn-primary:disabled {
        background-color: #666;
        border-color: #666;
    }

    a {
        text-decoration: none;
        color: #ff0000;
        font-weight: bold;
    }

    a:hover {
        color: #cc0000;
    }

    .alert-success {
        background-color: #1a4a3a;
        border-color: #28a745;
        color: #28a745;
    }

    .invalid-feedback {
        color: #ff4444;
    }

    .text-muted {
        color: #aaa !important;
    }

    .countdown-text {
        color: #ff0000;
        font-weight: bold;
    }

    .modal-content {
        background-color: #1f1f1f;
        color: white;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #444;
    }

    .modal-footer {
        border-top: 1px solid #444;
    }

    .btn-close {
        filter: invert(1);
    }

    .text-warning {
        color: #ffc107 !important;
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

        .verification-card {
            padding: 1.5rem;
            margin: 1rem;
        }
    }
</style>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="verification-card shadow">
        <div class="text-center mb-4">
            <div class="mb-3">
                <img src="{{ asset('img/image.png') }}" alt="Logo" style="width: 300px; height: 100px;" class="me-2">
                <i class="bi bi-envelope-check fs-1" style="color: #ff0000;"></i>
            </div>
            <h3 class="fw-bold">{{ __('Verify Your Email Address') }}</h3>
            <p class="text-muted">
                {{ __('We\'ve sent a verification code to your email address. Please enter it below to verify your account.') }}
            </p>
        </div>

        @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form id="verify-form" method="POST" action="{{ route('verify.email.code') }}" class="mb-4">
            @csrf
            <div class="mb-4">
                <label for="code" class="form-label mb-3">{{ __('Verification Code') }}</label>
                <div class="verification-code-container">
                    <input type="text" id="digit-1" class="verification-digit" maxlength="1" autofocus>
                    <input type="text" id="digit-2" class="verification-digit" maxlength="1">
                    <input type="text" id="digit-3" class="verification-digit" maxlength="1">
                    <input type="text" id="digit-4" class="verification-digit" maxlength="1">
                    <input type="text" id="digit-5" class="verification-digit" maxlength="1">
                    <input type="text" id="digit-6" class="verification-digit" maxlength="1">
                    <input type="hidden" id="code" name="code" class="@error('code') is-invalid @enderror">
                </div>

                @error('code')
                <span class="invalid-feedback d-block mt-1">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" id="submit-btn" class="btn btn-primary btn-lg">
                    {{ __('Verify Email') }}
                </button>
            </div>
        </form>

        <div class="text-center">
            <div class="d-flex justify-content-center align-items-center mb-3">
                <i class="bi bi-clock me-2 text-muted"></i>
                <p class="mb-0 text-muted">
                    {{ __('Code expires in:') }}
                    <span id="countdown" class="countdown-text">05:00</span>
                </p>
            </div>
            <p class="text-muted mb-0">
                {{ __('Didn\'t receive the code?') }}
                <a href="{{ route('register') }}">{{ __('Resend Code') }}</a>
            </p>
        </div>
    </div>
</div>

<div id="expiredModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">{{ __('Code Expired') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-circle text-warning fs-1"></i>
                </div>
                <p>{{ __('The verification code has expired. Please register again to receive a new one.') }}</p>
            </div>
            <div class="modal-footer border-0">
                <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Register Again') }}</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('verify-form');
        const digits = Array.from({
            length: 6
        }, (_, i) => document.getElementById(`digit-${i+1}`));
        const codeInput = document.getElementById('code');

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

        digits.forEach((digit, index) => {
            digit.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                this.classList.toggle('filled', this.value !== '');

                if (this.value !== '' && index < digits.length - 1) {
                    digits[index + 1].focus();
                }
            });

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

                if (e.key === 'ArrowLeft' && index > 0) {
                    e.preventDefault();
                    digits[index - 1].focus();
                }

                if (e.key === 'ArrowRight' && index < digits.length - 1) {
                    e.preventDefault();
                    digits[index + 1].focus();
                }
            });

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

                    if (pasteDigits.length < digits.length) {
                        digits[pasteDigits.length].focus();
                    }
                }
            });
        });

        let duration = 5 * 60;
        let countdown = document.getElementById('countdown');
        let submitBtn = document.getElementById('submit-btn');
        let modal = new bootstrap.Modal(document.getElementById('expiredModal'));

        const timer = setInterval(function() {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;

            countdown.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            if (--duration < 0) {
                clearInterval(timer);
                countdown.textContent = "00:00";
                submitBtn.disabled = true;
                modal.show();
            }
        }, 1000);
    });
</script>
@endpush
@endsection