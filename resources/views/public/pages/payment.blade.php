@extends('layouts.public')
@section('title', isset($plan) ? 'Payment - ' . $plan->name : 'Payment')
@section('content')

<style>
    body {
        background-color: #121212 !important;
        color: white !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    .payment-container {
        max-width: 800px;
        margin: 60px auto;
        background-color: #1f1f1f;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .payment-title {
        text-align: center;
        font-size: 2rem;
        color: #ff0000;
        margin-bottom: 30px;
    }

    .payment-form label {
        font-weight: bold;
        margin-top: 15px;
        color: #ccc;
    }    

    .payment-form input {
        background-color: #2a2a2a;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px;
        width: 100%;
        margin-top: 5px;
        margin-bottom: 15px;
    }

    .btn-submit-payment {
        width: 100%;
        background-color: #ff0000;
        border: none;
        padding: 14px;
        font-size: 1.1rem;
        border-radius: 10px;
        color: white;
        transition: background-color 0.3s;
    }    .btn-submit-payment:hover {
        background-color: #cc0000;
    }
    
    .btn-submit-payment:disabled {
        background-color: #555;
        cursor: not-allowed;
    }
</style>

<div class="payment-container">
    <h2 class="payment-title">Complete Your Subscription</h2>
    
    @if(session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif
      @if(isset($plan) && isset($club))
        <div style="background-color: #2a2a2a; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
            <h4 style="color: #ff0000; margin-bottom: 15px;">Subscription Summary</h4>
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
            <div style="display: flex; justify-content: space-between; padding: 15px 0; margin-top: 10px; border-top: 2px solid #555; font-size: 1.2rem;">
                <span>Total:</span>
                <span><strong>{{ number_format($plan->price, 2) }} JOD</strong></span>
            </div>
        </div>
        
        <!-- User Information Panel -->
        <div style="background-color: #2a2a2a; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
            <h4 style="color: #ff0000; margin-bottom: 15px;">Your Information</h4>
            <div class="row">
                <div class="col-md-6">
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold; color: #ccc;">Name:</label>
                        <div style="padding: 5px 0;">{{ $userInfo['name'] }}</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold; color: #ccc;">Email:</label>
                        <div style="padding: 5px 0;">{{ $userInfo['email'] }}</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold; color: #ccc;">Phone:</label>
                        <div style="padding: 5px 0;">{{ $userInfo['phone_number'] }}</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold; color: #ccc;">Goal:</label>
                        <div style="padding: 5px 0;">{{ $userInfo['goal'] }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold; color: #ccc;">Gender:</label>
                        <div style="padding: 5px 0;">{{ ucfirst($userInfo['gender']) }}</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold; color: #ccc;">Height:</label>
                        <div style="padding: 5px 0;">{{ $userInfo['height_cm'] != 'Not specified' ? $userInfo['height_cm'] . ' cm' : 'Not specified' }}</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold; color: #ccc;">Current Weight:</label>
                        <div style="padding: 5px 0;">{{ $userInfo['weight_kg'] != 'Not specified' ? $userInfo['weight_kg'] . ' kg' : 'Not specified' }}</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold; color: #ccc;">Target Weight:</label>
                        <div style="padding: 5px 0;">{{ $userInfo['target_weight_kg'] != 'Not specified' ? $userInfo['target_weight_kg'] . ' kg' : 'Not specified' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        @php
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();
        @endphp
        
        @if($club->status !== 'active')
            <div class="alert alert-danger" style="margin-bottom: 25px;">
                <strong>Sorry!</strong> This club is not currently accepting new subscriptions. Please check back later.
                <div class="mt-3">
                    <a href="{{ route('all_clubs') }}" class="btn btn-outline-danger">View Other Clubs</a>
                </div>
            </div>
        @elseif(!$plan->is_active)
            <div class="alert alert-danger" style="margin-bottom: 25px;">
                <strong>Sorry!</strong> This subscription plan is no longer available for purchase. Please select an active plan.
                <div class="mt-3">
                    <a href="{{ route('club_details', $club->getEncodedId()) }}" class="btn btn-outline-danger">View Available Plans</a>
                </div>
            </div>
        @elseif($activeSubscription)
            <div class="alert {{ $canSubscribe ? 'alert-info' : 'alert-warning' }}" style="margin-bottom: 25px;">
                <strong>Current Subscription:</strong><br>
                Your current subscription at {{ $activeSubscription->club->name }} ends on {{ $activeSubscription->end_date->format('F j, Y') }}.
                
                @if($canSubscribe)
                    <hr>
                    <strong>New Subscription:</strong><br>
                    @if(!$startDate->isToday())
                        This new subscription will start on {{ $startDate->format('F j, Y') }} and will be valid until {{ $endDate->format('F j, Y') }}.
                    @else
                        This new subscription will start today and will be valid until {{ $endDate->format('F j, Y') }}.
                    @endif
                @else
                    <hr>
                    <strong class="text-danger">{{ $message }}</strong>
                @endif
            </div>
        @endif

        <form class="payment-form" action="{{ route('process.payment') }}" method="POST">
            @csrf
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
            <input type="hidden" name="club_id" value="{{ $club->getEncodedId() }}">
    
            <div style="display: flex; gap: 20px; margin-bottom: 10px;">
                <div style="flex: 1;">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', Auth::user()->first_name ?? '') }}" required>
                    @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div style="flex: 1;">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', Auth::user()->last_name ?? '') }}" required>
                    @error('last_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
    
            <div style="display: flex; gap: 20px; margin-bottom: 10px;">
                <div style="flex: 1;">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div style="flex: 1;">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                    @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>            <hr style="border-color: #444; margin: 25px 0;">
            <h4 style="color: #ff0000; margin-bottom: 20px;">Select Your Coach</h4>
            
            <div style="margin-bottom: 25px;">
                <label for="coach_id" style="font-weight: bold; color: #ccc;">Choose a Coach (Optional)</label>
                <select id="coach_id" name="coach_id" style="background-color: #2a2a2a; color: white; border: none; border-radius: 8px; padding: 12px; width: 100%; margin-top: 5px;">                    <option value="">-- Select a Coach (Optional) --</option>
                    @foreach($coaches as $coach)
                    <option value="{{ $coach->id }}">{{ $coach->name }} - {{ is_array($coach->specializations) ? implode(', ', $coach->specializations) : ($coach->specializations ?: 'General Fitness') }}</option>
                    @endforeach
                </select>
                <div style="margin-top: 10px; font-size: 0.9rem; color: #aaa;">
                    Selecting a coach will provide you with personalized training and guidance throughout your fitness journey.
                </div>
                
                <!-- Coach Details Container - Will be populated via JavaScript -->
                <div id="coach-details" style="margin-top: 15px; display: none; background-color: #333; padding: 15px; border-radius: 8px;">
                    <h5 id="coach-name" style="color: #ff0000;"></h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div style="margin-bottom: 10px;">
                                <label style="font-weight: bold; color: #ccc;">Experience:</label>
                                <div id="coach-experience" style="padding: 5px 0;"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="margin-bottom: 10px;">
                                <label style="font-weight: bold; color: #ccc;">Gender:</label>
                                <div id="coach-gender" style="padding: 5px 0;"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="margin-bottom: 10px;">
                                <label style="font-weight: bold; color: #ccc;">Type:</label>
                                <div id="coach-type" style="padding: 5px 0;"></div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight: bold; color: #ccc;">Specializations:</label>
                        <div id="coach-specializations" style="padding: 5px 0;"></div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight: bold; color: #ccc;">Bio:</label>
                        <div id="coach-bio" style="padding: 5px 0;"></div>
                    </div>
                </div>
            </div>

            <hr style="border-color: #444; margin: 25px 0;">
            <h4 style="color: #ff0000; margin-bottom: 20px;">Payment Details</h4>
            
            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <img src="https://cdn.jsdelivr.net/gh/hosuaby/payment-card-icons@master/images/visa.svg" alt="Visa" style="height: 30px; opacity: 0.7;">
                <img src="https://cdn.jsdelivr.net/gh/hosuaby/payment-card-icons@master/images/mastercard.svg" alt="MasterCard" style="height: 30px; opacity: 0.7;">
                <img src="https://cdn.jsdelivr.net/gh/hosuaby/payment-card-icons@master/images/amex.svg" alt="American Express" style="height: 30px; opacity: 0.7;">
            </div>
    
            <label for="card_number">Card Number</label>
            <input type="text" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX" required>
            @error('card_number')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    
            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                    @error('expiry_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div style="flex: 1;">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="XXX" required>
                    @error('cvv')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>            </div>
    
            <button type="submit" class="btn-submit-payment" {{ $club->status !== 'active' || !$plan->is_active || (isset($canSubscribe) && !$canSubscribe) ? 'disabled' : '' }}>Complete Payment - {{ number_format($plan->price, 2) }} JOD</button>
        </form>
    @else
        <div class="alert alert-danger">
            No subscription plan selected. Please go back and select a subscription plan.
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('all_clubs') }}" class="btn btn-danger">View All Clubs</a>
        </div>
    @endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format card number with spaces
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

        // Format expiry date (MM/YY)
        const expiryDateInput = document.getElementById('expiry_date');
        if (expiryDateInput) {
            expiryDateInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length > 0) {
                    // Month cannot be > 12
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

        // Limit CVV to 3-4 digits
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
        
        // Coach selection handling
        const coachSelect = document.getElementById('coach_id');
        const coachDetails = document.getElementById('coach-details');
        
        if (coachSelect) {
            // Store coach data for quick access
            const coachData = {
                @foreach($coaches as $coach)
                {{ $coach->id }}: {                    name: "{{ $coach->name }}",
                    gender: "{{ ucfirst($coach->gender ?? 'Not specified') }}",
                    experience: "{{ $coach->experience_years ? $coach->experience_years . ' years' : 'Not specified' }}",
                    type: "{{ ucfirst($coach->employment_type ?? 'Not specified') }}",
                    specializations: "{{ is_array($coach->specializations) ? implode(', ', $coach->specializations) : ($coach->specializations ?: 'General Fitness') }}",
                    bio: "{{ $coach->bio ? str_replace('"', '\"', $coach->bio) : 'No biography available.' }}"
                },
                @endforeach
            };
            
            coachSelect.addEventListener('change', function() {
                const selectedCoachId = this.value;
                
                if (selectedCoachId) {
                    // Display coach details
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
