@extends('layouts.dashboard')
@section('title', 'Verify Club Email Address')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden" style="background-color: #b30000;">
                <div class="card-header bg-danger text-white p-3" style="background-color: #e60000 !important;">
                    <h5 class="mb-0 fw-bold" style="font-size: 1.25rem;">{{ __('Verify Club Email Address') }}</h5>
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
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 0.8rem;">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="text-center mb-3">
                        <div class="mb-2">
                            <i class="bi bi-shield-check fs-2" style="color: #fff;"></i>
                        </div>
                        <h6 class="text-white fw-bold">{{ __('Complete Club Registration') }}</h6>
                        <p class="text-white" style="font-size: 0.9rem;">
                            {{ __('To finalize the club registration, please verify the email address associated with your club.') }}<br>
                            {{ __('A verification code has been sent to the club email address.') }}<br>
                            {{ __('Please enter the 6-digit code to complete the registration process.') }}
                        </p>
                <div class="mt-2 p-2 bg-black rounded-3">
                            <p class="mb-0 textwhite" style="font-weight: 600;"><strong>{{ __('Club Email:') }}</strong> {{ session('club_data')['email'] ?? 'Unknown' }}</p>
                            <p class="mb-0 mt-1 textwhite" style="font-weight: 600;"><strong>{{ __('Club Name:') }}</strong> {{ session('club_data')['name'] ?? 'Unknown' }}</p>
                            @if(session()->has('verification_code'))
                                <p class="mb-0 mt-1 textwhite" style="font-weight: 600;"><strong>{{ __('Verification Code Available:') }}</strong> Yes 
                                
                                </p>
                            @else
                                <p class="mb-0 mt-1 text-warning" style="font-weight: 600;"><strong>{{ __('Verification Code Available:') }}</strong> No</p>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.club.verify.email') }}" class="mb-3">
                        @csrf                        <div class="mb-3">
                            <label for="verification_code" class="form-label fw-semibold mb-1 text-white" style="font-size: 0.9rem;">{{ __('Verification Code') }}</label>
                            <div class="verification-code-container">
                                <input type="text" id="digit-1" class="verification-digit" maxlength="1" autofocus autocomplete="one-time-code" inputmode="numeric" pattern="\d*" oninput="updateVerificationCode()">
                                <input type="text" id="digit-2" class="verification-digit" maxlength="1" autocomplete="one-time-code" inputmode="numeric" pattern="\d*" oninput="updateVerificationCode()">
                                <input type="text" id="digit-3" class="verification-digit" maxlength="1" autocomplete="one-time-code" inputmode="numeric" pattern="\d*" oninput="updateVerificationCode()">
                                <input type="text" id="digit-4" class="verification-digit" maxlength="1" autocomplete="one-time-code" inputmode="numeric" pattern="\d*" oninput="updateVerificationCode()">
                                <input type="text" id="digit-5" class="verification-digit" maxlength="1" autocomplete="one-time-code" inputmode="numeric" pattern="\d*" oninput="updateVerificationCode()">
                                <input type="text" id="digit-6" class="verification-digit" maxlength="1" autocomplete="one-time-code" inputmode="numeric" pattern="\d*" oninput="updateVerificationCode()">
                                <input type="hidden" id="verification_code" name="verification_code" value="" class="@error('verification_code') is-invalid @enderror">
                            </div>
                            
                            @error('verification_code')
                                <span class="invalid-feedback d-block mt-1 text-white bg-danger p-1 rounded" style="font-size: 0.9rem;">
                                    <i class="bi bi-exclamation-triangle me-1"></i><strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div id="code-status" class="d-none text-center mt-2 small"></div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg py-2 fw-bold" style="background-color: #e60000; font-size: 1rem;">
                                <i class="bi bi-check-circle me-2"></i>{{ __('Verify Email & Create Club') }}
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
                            <a href="{{ route('admin.club.resend.verification.code') }}" class="btn btn-outline-light w-100" style="border-color: #fff; color: #fff;">
                                <i class="bi bi-envelope me-2"></i>{{ __('Resend Code') }}
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-x-circle me-2 text-white"></i>
                                <p class="mb-0" style="font-size: 0.85rem;">{{ __('Want to cancel?') }}</p>
                            </div>
                            <a href="{{ route('clubs.index') }}" class="btn btn-outline-light w-100" style="border-color: #fff; color: #fff;">
                                <i class="bi bi-arrow-left me-2"></i>{{ __('Cancel Creation') }}
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
    // Function to update the hidden verification code field whenever any digit is entered
    function updateVerificationCode() {
        const digits = Array.from({ length: 6 }, (_, i) => document.getElementById(`digit-${i+1}`));
        const codeInput = document.getElementById('verification_code');
        const codeStatus = document.getElementById('code-status');
        
        let code = '';
        digits.forEach(digit => {
            code += digit.value || '';
        });
        
        // Update the hidden input with current value
        codeInput.value = code;
        
        // Update status message
        if (code.length === 6 && /^\d{6}$/.test(code)) {
            codeStatus.textContent = 'Valid verification code entered';
            codeStatus.className = 'd-block text-center mt-2 small text-success';
        } else if (code.length > 0) {
            codeStatus.textContent = `Please enter all 6 digits (${code.length}/6 entered)`;
            codeStatus.className = 'd-block text-center mt-2 small text-warning';
        } else {
            codeStatus.className = 'd-none';
        }
        
        console.log('Verification code updated:', code);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const digits = Array.from({ length: 6 }, (_, i) => document.getElementById(`digit-${i+1}`));
        const codeInput = document.getElementById('verification_code');
        
        // Initial update in case there are prefilled values
        updateVerificationCode();
        
        form.addEventListener('submit', function(e) {
            let code = codeInput.value;
            
            if (code.length !== 6 || !/^\d{6}$/.test(code)) {
                e.preventDefault();
                const codeStatus = document.getElementById('code-status');
                codeStatus.textContent = 'Please enter all 6 digits of the verification code';
                codeStatus.className = 'd-block text-center mt-2 small text-white bg-danger p-1 rounded';
                return false;
            }
            console.log('Submitting verification code:', code);
        });
          digits.forEach((digit, index) => {
            digit.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                this.classList.toggle('filled', this.value !== '');
                
                // Always update the hidden field when a digit changes
                updateVerificationCode();
                
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
                        // Update verification code after removing a digit
                        setTimeout(updateVerificationCode, 0);
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
                    
                    // Always update the verification code after paste
                    updateVerificationCode();
                    
                    if (pasteDigits.length < digits.length) {
                        digits[pasteDigits.length].focus();
                    } else {
                        // Focus on the submit button if we have all 6 digits
                        form.querySelector('button[type="submit"]').focus();
                    }
                }
            });
        });
        
        let duration = 30 * 60; // 30 minutes
        let countdown = document.getElementById('countdown');
        let timer = null;
        
        if (countdown) {
            timer = setInterval(function () {
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
