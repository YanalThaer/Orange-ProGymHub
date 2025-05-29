@extends('layouts.dashboard')
@section('title', 'Verify Coach Email Address')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden" style="background-color: #b30000;">
                <div class="card-header bg-danger text-white p-3" style="background-color: #e60000 !important;">
                    <h5 class="mb-0 fw-bold" style="font-size: 1.25rem;">{{ __('Verify Coach Email Address') }}</h5>
                </div>

                <div class="card-body p-3 text-white" style="font-size: 0.85rem;">
                    @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 0.8rem;">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 0.8rem;">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 0.8rem;">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="text-center mb-3">
                        <div class="mb-2">
                            <i class="bi bi-person-check fs-2" style="color: #fff;"></i>
                        </div>
                        <h6 class="text-white fw-bold">{{ __('Complete Coach Registration') }}</h6>
                        <p class="text-white" style="font-size: 0.9rem;">
                            {{ __('To finalize the coach registration, please verify the email address associated with this coach.') }}<br>
                            {{ __('A verification code has been sent to the coach email address.') }}<br>
                            {{ __('Please enter the 6-digit code to complete the registration process.') }}
                        </p>

                        <div class="mt-2 p-2 bg-black rounded-3">
                            <p class="mb-0 text-white" style="font-weight: 600;"><strong>{{ __('Coach Email:') }}</strong> {{ $coachEmail }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('club.coach.verify', $tempId) }}" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="verification_code" class="form-label fw-semibold mb-1 text-white" style="font-size: 0.9rem;">{{ __('Verification Code') }}</label>
                            <div class="verification-code-container">
                                <input type="text" id="digit-1" class="verification-digit" maxlength="1" autofocus autocomplete="one-time-code" inputmode="numeric" pattern="\d*">
                                <input type="text" id="digit-2" class="verification-digit" maxlength="1" autocomplete="one-time-code" inputmode="numeric" pattern="\d*">
                                <input type="text" id="digit-3" class="verification-digit" maxlength="1" autocomplete="one-time-code" inputmode="numeric" pattern="\d*">
                                <input type="text" id="digit-4" class="verification-digit" maxlength="1" autocomplete="one-time-code" inputmode="numeric" pattern="\d*">
                                <input type="text" id="digit-5" class="verification-digit" maxlength="1" autocomplete="one-time-code" inputmode="numeric" pattern="\d*">
                                <input type="text" id="digit-6" class="verification-digit" maxlength="1" autocomplete="one-time-code" inputmode="numeric" pattern="\d*">
                                <input type="hidden" id="verification_code" name="verification_code" value="" class="@error('verification_code') is-invalid @enderror">
                            </div>
                            <div class="mt-3 text-center">
                                <p class="text-white mb-1" style="font-size: 0.8rem;">{{ __('Or enter the verification code directly:') }}</p>
                                <input type="text" id="direct_code" class="form-control text-center" maxlength="6" inputmode="numeric" pattern="\d{6}" placeholder="Enter 6-digit code" style="max-width: 200px; margin: 0 auto;">
                            </div>

                            @error('verification_code')
                            <span class="invalid-feedback d-block mt-1" style="font-size: 0.8rem;">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg py-2 fw-bold" style="background-color: #e60000; font-size: 1rem;">
                                <i class="bi bi-check-circle me-2"></i>{{ __('Verify Email & Register Coach') }}
                            </button>
                        </div>
                    </form>
                    <div class="row mt-3 pt-2 border-top border-light">
                        <div class="col-12 mb-2 text-center">
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <i class="bi bi-clock me-2 text-white"></i>
                                <p class="mb-0" style="color: #f8f9fa; font-size: 0.85rem;">{{ __('Code expires in:') }} <span id="countdown" class="fw-bold">30:00</span></p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2 mb-md-0">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-question-circle me-2 text-white"></i>
                                <p class="mb-0" style="font-size: 0.85rem;">{{ __('Didn\'t receive the code?') }}</p>
                            </div>
                            <a href="{{ route('club.coach.resend.code', $tempId) }}" class="btn btn-outline-light w-100" style="border-color: #fff; color: #fff;">
                                <i class="bi bi-envelope me-2"></i>{{ __('Resend Code') }}
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-x-circle me-2 text-white"></i>
                                <p class="mb-0" style="font-size: 0.85rem;">{{ __('Want to cancel?') }}</p>
                            </div>
                            <a href="{{ route('club.coaches') }}" class="btn btn-outline-light w-100" style="border-color: #fff; color: #fff;">
                                <i class="bi bi-arrow-left me-2"></i>{{ __('Cancel Registration') }}
                            </a>
                        </div>
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
        gap: 8px;
        margin-bottom: 12px;
        justify-content: center;
    }

    .verification-digit {
        width: 42px;
        height: 50px;
        border: 2px solid #fff;
        border-radius: 8px;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        background-color: transparent;
        color: white;
        transition: all 0.2s;
    }

    .verification-digit:focus {
        border-color: #ff4d4d;
        box-shadow: 0 0 0 0.25rem rgba(255, 77, 77, 0.5);
        outline: none;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .verification-digit.filled {
        background-color: rgba(255, 255, 255, 0.15);
    }

    @media (max-width: 576px) {
        .verification-code-container {
            gap: 5px;
        }

        .verification-digit {
            width: 36px;
            height: 44px;
            font-size: 18px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const digits = Array.from({
            length: 6
        }, (_, i) => document.getElementById(`digit-${i+1}`));
        const codeInput = document.getElementById('verification_code');
        const directInput = document.getElementById('direct_code');
        if (directInput) {
            directInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    digits.forEach(digit => {
                        digit.value = '';
                        digit.classList.remove('filled');
                    });
                }

                this.value = this.value.replace(/[^0-9]/g, '');

                codeInput.value = this.value;
            });
        } 
        form.addEventListener('submit', function(e) {
            if (directInput && directInput.value.length === 6) {
                codeInput.value = directInput.value;
                console.log('Submitting with direct input:', directInput.value);
                return true;
            }

            let code = '';
            digits.forEach(digit => {
                code += digit.value;
            });

            if (code.length === 6) {
                codeInput.value = code;
                console.log('Submitting with digit inputs:', code);
                return true;
            }

            e.preventDefault();
            alert('Please enter a valid 6-digit verification code');
            return false;
        });
        function updateHiddenInput() {
            let combinedValue = '';
            digits.forEach(d => {
                combinedValue += d.value || '';
            });
            codeInput.value = combinedValue;
            console.log('Updated hidden input:', codeInput.value);
        }

        digits.forEach((digit, index) => {
            digit.addEventListener('input', function() {
                if (directInput) {
                    directInput.value = '';
                }

                this.value = this.value.replace(/[^0-9]/g, '');
                this.classList.toggle('filled', this.value !== '');

                updateHiddenInput();

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
                    setTimeout(updateHiddenInput, 0);
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
                    updateHiddenInput();
                    if (pasteDigits.length < digits.length) {
                        digits[pasteDigits.length].focus();
                    }
                }
            });
        });

        let duration = 30 * 60; // 30 minutes
        let countdown = document.getElementById('countdown');
        let timer = null;

        if (countdown) {
            timer = setInterval(function() {
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;
                countdown.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                if (--duration < 0) {
                    clearInterval(timer);
                    countdown.textContent = "00:00";
                    countdown.classList.add('text-danger');
                    let expiredMessage = document.createElement('div');
                    expiredMessage.className = 'alert alert-warning mt-3';
                    expiredMessage.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>' +
                        '{{ __("The verification code has expired. Please request a new one or cancel and start over.") }}';
                    let container = countdown.closest('.col-12');
                    container.appendChild(expiredMessage);
                }
            }, 1000);
        }
    });
</script>
@endpush
@endsection