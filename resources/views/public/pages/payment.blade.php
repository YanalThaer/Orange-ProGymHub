@extends('layouts.public')
@section('title', isset($plan) ? 'Payment - ' . $plan->name : 'Payment')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@section('content')
<style>
    body {
        background-color: #121212 !important;
        color: white !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        font-size: medium !important;
        line-height: 1.6;
    }
    .payment-container {
        max-width: 800px;
        margin: auto;
        padding: 30px;
    }
    .payment-title {
        margin-bottom: 20px;
    }
    h2,
    h3,
    h4,
    h5 {
        font-size: calc(1.5rem + 0.5vw);
        line-height: 1.3;
    }
    .payment-form input,
    .payment-form select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        background-color: #1c1c1c;
        border: 1px solid #444;
        color: white;
        border-radius: 5px;
    }
    .payment-form input:focus,
    .payment-form select:focus {
        outline: none;
        border-color: #ff0000;
    }
    .btn-submit-payment {
        background-color: #ff0000;
        color: white;
        border: none;
        padding: 15px 20px;
        border-radius: 5px;
        margin-top: 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
    }
    .btn-submit-payment:hover {
        background-color: #e60000;
    }
    .alert {
        padding: 15px;
        border-radius: 5px;
    }
</style>
<div class="payment-container container mt-5">
    <h2 class="payment-title mt-5 text-white text-center">Complete Your Subscription</h2>
    @if(session('error'))
    <div class="alert alert-danger mb-4">
        {{ session('error') }}
    </div>
    @endif
    @if(isset($plan) && isset($club))
    <div style="background-color: #2a2a2a; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
        <h4 style="color: #ff0000; margin-bottom: 15px; font-size: 1.25rem;">Subscription Summary</h4>
        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #444;">
            <span>Club:</span>
            <span><strong>{{ $club->name }}</strong></span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #444;">
            <span>Plan:</span>
            <span><strong>{{ $plan->name }}</strong></span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #444;">
            <span>Type:</span>
            <span><strong>{{ ucfirst($plan->type) }}</strong></span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #444;">
            <span>Duration:</span>
            <span><strong>{{ $plan->duration_days }} days</strong></span>
        </div>
        <div
            style="display: flex; justify-content: space-between; padding: 15px 0; margin-top: 10px; border-top: 2px solid #555; font-size: 1.1rem;">
            <span>Total:</span>
            <span><strong>{{ number_format($plan->price, 2) }} JOD</strong></span>
        </div>
    </div>
    <div style="background-color: #2a2a2a; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
        <h4 style="color: #ff0000; margin-bottom: 15px; font-size: 1.25rem;">Your Information</h4>
        <div class="row">
            <div class="col-md-6">
                @foreach(['name', 'email', 'phone_number' => 'phone number', 'goal'] as $field => $label)
                @php $key = is_int($field) ? $label : $field; @endphp
                <div style="margin-bottom: 15px;">
                    <label class="fw-bold text-secondary">{{ ucfirst(is_int($field) ? $label : $field) }}:</label>
                    <div style="padding: 5px 0;">{{ $userInfo[$key] }}</div>
                </div>
                @endforeach
            </div>
            <div class="col-md-6">
                <div style="margin-bottom: 15px;">
                    <label class="fw-bold text-secondary">Gender:</label>
                    <div style="padding: 5px 0;">{{ ucfirst($userInfo['gender']) }}</div>
                </div>
                @foreach(['height_cm' => 'Height', 'weight_kg' => 'Current Weight', 'target_weight_kg' => 'Target Weight'] as $key => $label)
                <div style="margin-bottom: 15px;">
                    <label class="fw-bold text-secondary">{{ $label }}:</label>
                    <div style="padding: 5px 0;">
                        {{ $userInfo[$key] != 'Not specified' ? $userInfo[$key] . ($key == 'height_cm' ? ' cm' : ' kg') : 'Not specified' }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @php
    $user = Auth::user();
    $activeSubscription = $user->getActiveSubscription();
    @endphp
    @if($club->status !== 'active')
    <div class="alert alert-danger mb-4">
        <strong>Sorry!</strong> This club is not currently accepting new subscriptions.
        <div class="mt-3">
            <a href="{{ route('all_clubs') }}" class="btn btn-outline-danger">View Other Clubs</a>
        </div>
    </div>
    @elseif(!$plan->is_active)
    <div class="alert alert-danger mb-4">
        <strong>Sorry!</strong> This subscription plan is no longer available.
        <div class="mt-3">
            <a href="{{ route('club_details', $club->getEncodedId()) }}" class="btn btn-outline-danger">View Available
                Plans</a>
        </div>
    </div>
    @elseif($activeSubscription)
    <div class="alert {{ $canSubscribe ? 'alert-info' : 'alert-warning' }} mb-4">
        <strong>Current Subscription:</strong><br>
        Your current subscription at {{ $activeSubscription->club->name }} ends on
        {{ $activeSubscription->end_date->format('F j, Y') }}.
        @if($canSubscribe)
        <hr>
        <strong>New Subscription:</strong><br>
        @if(!$startDate->isToday())
        Starts on {{ $startDate->format('F j, Y') }} and valid until {{ $endDate->format('F j, Y') }}.
        @else
        Starts today and valid until {{ $endDate->format('F j, Y') }}.
        @endif
        @else
        <hr>
        <strong class="text-danger">{{ $message }}</strong>
        @endif
    </div>
    @endif
    <form class="payment-form" action="{{ route('process.payment') }}" method="POST" style="font-size: 1rem;">
        @csrf
        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
        <input type="hidden" name="club_id" value="{{ $club->getEncodedId() }}">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" id="first_name" name="first_name"
                    value="{{ old('first_name', Auth::user()->first_name ?? '') }}" class="form-control" required>
                @error('first_name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" id="last_name" name="last_name"
                    value="{{ old('last_name', Auth::user()->last_name ?? '') }}" class="form-control" required>
                @error('last_name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}"
                    class="form-control" required>
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}"
                    class="form-control" required>
                @error('phone')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <hr class="my-4 border-secondary">
        <h4 class="text-danger mb-3" style="font-size: 1.25rem;">Select Your Coach</h4>
        <div class="mb-3 bg-dark p-3 rounded">
            <label for="coach_id" class="form-label fw-bold text-secondary">Choose a Coach (Optional)</label>
            <select id="coach_id" name="coach_id" class="form-select bg-dark text-white" style="background-color: #2a2a2a; color: white;">
                <option value="">-- Select a Coach (Optional) --</option>
                @foreach($coaches as $coach)
                <option class="bg-dark text-white" value="{{ $coach->id }}">{{ $coach->name }} -
                    {{ is_array($coach->specializations) ? implode(', ', $coach->specializations) : ($coach->specializations ?: 'General Fitness') }}
                </option>
                @endforeach
            </select>
            <div class="mt-2 text-muted small">
                Selecting a coach provides personalized training and guidance.
            </div>
            <div id="coach-details" class="mt-3 p-3 rounded" style="display: none; background-color: #333;">
                <h5 id="coach-name" class="text-danger"></h5>
                <div class="row">
                    <div class="col-md-4">
                        <label class="fw-bold text-secondary">Experience:</label>
                        <div id="coach-experience" class="small"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold text-secondary">Gender:</label>
                        <div id="coach-gender" class="small"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold text-secondary">Type:</label>
                        <div id="coach-type" class="small"></div>
                    </div>
                </div>
                <div class="mt-2">
                    <label class="fw-bold text-secondary">Specializations:</label>
                    <div id="coach-specializations" class="small"></div>
                </div>
                <div class="mt-2">
                    <label class="fw-bold text-secondary">Bio:</label>
                    <div id="coach-bio" class="small"></div>
                </div>
            </div>
        </div>
        <hr class="my-4 border-secondary">
        <h4 class="text-danger mb-3" style="font-size: 1.25rem;">Payment Details</h4>
        <div class="d-flex gap-2 mb-3">
            <img src="{{ asset('assets/img/visa.jpg') }}" width="100" alt="Visa"
                style="height: 100px; opacity: 0.7;">
        </div>
        <div class="mb-3">
            <label for="card_number" class="form-label">Card Number</label>
            <input type="text" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX"
                class="form-control" required>
            @error('card_number')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="expiry_date" class="form-label">Expiry Date</label>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" class="form-control"
                    required>
                @error('expiry_date')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="XXX" class="form-control" required>
                @error('cvv')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <button type="button" id="confirm-payment-btn" class="btn btn-danger w-100" 
            {{ ($club->status !== 'active' || !$plan->is_active || (isset($canSubscribe) && !$canSubscribe)) ? 'disabled' : '' }}>
            Complete Payment - {{ number_format($plan->price, 2) }} JOD
        </button>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="confirmSubscriptionModal" tabindex="-1" aria-labelledby="confirmSubscriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-danger fw-bold" id="confirmSubscriptionModalLabel">Confirm Subscription</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="bg-dark p-3 rounded mb-3">
                        <p class="mb-2 text-white">Are you sure you want to subscribe to <strong class="text-danger">{{ $plan->name }}</strong> at <strong>{{ $club->name }}</strong>?</p>
                        <p class="mb-0 text-white">You will be charged <strong class="text-danger">{{ number_format($plan->price, 2) }} JOD</strong>.</p>
                    </div>
                    @if(isset($startDate) && isset($endDate))
                    <div class="bg-dark p-3 rounded">
                        <p class="mb-0 text-white">
                            @if(!$startDate->isToday())
                            Your subscription will start on <strong>{{ $startDate->format('F j, Y') }}</strong> and will be valid until <strong>{{ $endDate->format('F j, Y') }}</strong>.
                            @else
                            Your subscription will start today and will be valid until <strong>{{ $endDate->format('F j, Y') }}</strong>.
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer border-secondary justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-subscription">Confirm Subscription</button>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-danger">No subscription plan selected. Please go back and select a plan.</div>
    <div class="text-center mt-4">
        <a href="{{ route('all_clubs') }}" class="btn btn-danger">View All Clubs</a>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing scripts...');
    
    // Check if Bootstrap is loaded
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded. Modal functionality may not work.');
        return;
    }
    
    console.log('Bootstrap is loaded successfully');
    
    // Get form elements
    const confirmPaymentBtn = document.getElementById('confirm-payment-btn');
    const confirmSubscriptionBtn = document.getElementById('confirm-subscription');
    const paymentForm = document.querySelector('.payment-form');
    const modalElement = document.getElementById('confirmSubscriptionModal');
    
    // Log elements to check if they exist
    console.log('Elements found:', {
        confirmPaymentBtn: !!confirmPaymentBtn,
        confirmSubscriptionBtn: !!confirmSubscriptionBtn,
        paymentForm: !!paymentForm,
        modalElement: !!modalElement
    });
    
    if (!confirmPaymentBtn || !confirmSubscriptionBtn || !paymentForm || !modalElement) {
        console.error('Required elements not found');
        return;
    }
    
    // Initialize modal
    const modal = new bootstrap.Modal(modalElement);
    console.log('Modal initialized:', modal);
    
    // Payment button click handler
    confirmPaymentBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Payment button clicked');
        
        // Check if button is disabled
        if (this.disabled) {
            console.log('Button is disabled, not proceeding');
            return;
        }
        
        // Validate form
        if (!paymentForm.checkValidity()) {
            console.log('Form is not valid, showing validation errors');
            paymentForm.reportValidity();
            return;
        }
        
        console.log('Form is valid, showing modal');
        
        // Show modal
        try {
            modal.show();
            console.log('Modal show() called successfully');
        } catch (error) {
            console.error('Error showing modal:', error);
        }
    });
    
    // Confirm subscription button click handler
    confirmSubscriptionBtn.addEventListener('click', function() {
        console.log('Confirm subscription clicked');
        
        // Add confirmed input to form
        const confirmedInput = document.createElement('input');
        confirmedInput.type = 'hidden';
        confirmedInput.name = 'confirmed';
        confirmedInput.value = '1';
        paymentForm.appendChild(confirmedInput);
        
        // Hide modal
        modal.hide();
        
        // Submit form
        paymentForm.submit();
    });
    
    // Card number formatting
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value.charAt(i);
            }
            if (formattedValue.length > 19) {
                formattedValue = formattedValue.substring(0, 19);
            }
            e.target.value = formattedValue;
        });
    }
    
    // Expiry date formatting
    const expiryDateInput = document.getElementById('expiry_date');
    if (expiryDateInput) {
        expiryDateInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length > 0 && parseInt(value.substring(0, 2)) > 12) {
                    value = '12' + value.substring(2);
                }
                if (value.length > 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
            }
            e.target.value = value;
        });
    }
    
    // CVV formatting
    const cvvInput = document.getElementById('cvv');
    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 3) {
                value = value.substring(0, 3);
            }
            e.target.value = value;
        });
    }
    
    // Coach selection
    const coachSelect = document.getElementById('coach_id');
    const coachDetails = document.getElementById('coach-details');
    if (coachSelect) {
        const coachData = {
            @foreach($coaches as $coach)
            {{ $coach->id }}: {
                name: "{{ $coach->name }}",
                gender: "{{ ucfirst($coach->gender ?? 'Not specified') }}",
                experience: "{{ $coach->experience_years ? $coach->experience_years . ' years' : 'Not specified' }}",
                type: "{{ ucfirst($coach->employment_type ?? 'Not specified') }}",
                specializations: "{{ is_array($coach->specializations) ? implode(', ', $coach->specializations) : ($coach->specializations ?: 'General Fitness') }}",
                bio: "{{ $coach->bio ? str_replace(['"', '\n'], ['\"', '\\n'], $coach->bio) : 'No biography available.' }}"
            },
            @endforeach
        };
        
        coachSelect.addEventListener('change', function() {
            const selectedCoachId = this.value;
            if (selectedCoachId && coachData[selectedCoachId]) {
                const coach = coachData[selectedCoachId];
                document.getElementById('coach-name').textContent = coach.name;
                document.getElementById('coach-gender').textContent = coach.gender;
                document.getElementById('coach-experience').textContent = coach.experience;
                document.getElementById('coach-type').textContent = coach.type;
                document.getElementById('coach-specializations').textContent = coach.specializations;
                document.getElementById('coach-bio').textContent = coach.bio;
                coachDetails.style.display = 'block';
            } else {
                coachDetails.style.display = 'none';
            }
        });
    }
});
</script>
@endsection