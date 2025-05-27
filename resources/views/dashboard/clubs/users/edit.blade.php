@extends('layouts.dashboard')
@section('title', 'Club - Edit User')

@section('content')
<style>
    /* Dark Theme Custom Styles */
    .dark-theme {
        background-color: #0d1117;
        color: #f0f6fc;
        min-height: 100vh;
    }
    
    .dark-card {
        background-color: #161b22;
        border: 1px solid #30363d;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    
    .dark-card-header {
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        border-bottom: 1px solid #30363d;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.5rem;
        color: #f9fafb;
    }
    
    .dark-form-control {
        background-color: #21262d;
        border: 1px solid #30363d;
        color: #f0f6fc;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .dark-form-control:focus {
        background-color: #21262d;
        border-color: #58a6ff;
        color: #f0f6fc;
        box-shadow: 0 0 0 0.2rem rgba(88, 166, 255, 0.25);
    }
    
    .dark-form-control::placeholder {
        color: #8b949e;
    }
    
    .dark-alert {
        border-radius: 8px;
        border: none;
        padding: 1rem 1.25rem;
    }
    
    .dark-alert-primary {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: #dbeafe;
        border-left: 4px solid #60a5fa;
    }
    
    .dark-alert-info {
        background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
        color: #cffafe;
        border-left: 4px solid #67e8f9;
    }
    
    .dark-alert-warning {
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
        color: #fef3c7;
        border-left: 4px solid #fbbf24;
    }
    
    .dark-btn-primary {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .dark-btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
    }
    
    .dark-btn-success {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .dark-btn-success:hover {
        background: linear-gradient(135deg, #047857 0%, #059669 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }
    
    .dark-btn-secondary {
        background: linear-gradient(135deg, #4b5563 0%, #6b7280 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .dark-btn-secondary:hover {
        background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(75, 85, 99, 0.3);
    }
    
    .dark-section-title {
        color: #58a6ff;
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #30363d;
    }
    
    .form-label {
        color: #f0f6fc;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .text-required {
        color: #ff7b72;
    }
    
    .form-text {
        color: #8b949e;
        font-size: 0.875rem;
    }
    
    .bmi-status {
        padding: 0.75rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        margin-top: 1rem;
    }
    
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #30363d, transparent);
        margin: 2rem 0;
    }
    
    .icon-accent {
        color: #58a6ff;
    }
    
    .status-active {
        color: #7ee787;
    }
    
    .status-inactive {
        color: #ff7b72;
    }
</style>

<div class="dark-theme">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="dark-card">
                    <div class="dark-card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-user-edit icon-accent me-2"></i>
                                Edit User: {{ $user->name }}
                            </h4>
                        </div>
                        <a href="{{ route('club.users') }}" class="dark-btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                    </div>

                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger dark-alert mb-4">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success dark-alert mb-4">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="alert alert-danger dark-alert mb-4">
                                <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
                            </div>
                        @endif

                        <div class="dark-alert dark-alert-primary mb-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-bell icon-accent me-3 mt-1"></i>
                                <div>
                                    <strong>Notification System</strong>
                                    <p class="mb-2 mt-1">When you update user information, notifications will be automatically sent to:</p>
                                    <ul class="mb-0">
                                        <li>The user (via email and in-app notification)</li>
                                        <li>Your club (via email and in-app notification)</li>
                                        @if($user->coach_id)
                                        <li>The assigned coach (via in-app notification)</li>
                                        @endif
                                    </ul>
                                    <small class="text-muted mt-2 d-block">When you change a user's coach assignment, both the new and previous coach will be notified.</small>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('club.users.update', $user->getEncodedId()) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="dark-section-title">
                                        <i class="fas fa-user me-2"></i>Personal Information
                                    </h5>
                                    
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Full Name <span class="text-required">*</span></label>
                                        <input type="text" class="form-control dark-form-control @error('name') is-invalid @enderror" 
                                            id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email <span class="text-required">*</span></label>
                                        <input type="email" class="form-control dark-form-control @error('email') is-invalid @enderror" 
                                            id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="phone_number" class="form-label">Phone Number <span class="text-required">*</span></label>
                                        <input type="text" class="form-control dark-form-control @error('phone_number') is-invalid @enderror" 
                                            id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                                    </div>
                                    
                                    <div class="dark-alert dark-alert-info mb-3">
                                        <small><i class="fas fa-info-circle me-2"></i>When you update user information, an email notification will be sent to both the user and your club.</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-control dark-form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control dark-form-control @error('date_of_birth') is-invalid @enderror" 
                                            id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? (is_object($user->date_of_birth) ? $user->date_of_birth->format('Y-m-d') : $user->date_of_birth) : '') }}">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control dark-form-control @error('password') is-invalid @enderror" 
                                            id="password" name="password" placeholder="Leave empty to keep current password">
                                        <small class="form-text">Leave empty to keep current password</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control dark-form-control" 
                                            id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5 class="dark-section-title">
                                        <i class="fas fa-heartbeat me-2"></i>Fitness Information
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="height" class="form-label">Height (cm)</label>
                                                <input type="number" step="0.01" class="form-control dark-form-control @error('height') is-invalid @enderror" 
                                                    id="height" name="height" value="{{ old('height', $user->height_cm) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="weight" class="form-label">Weight (kg)</label>
                                                <input type="number" step="0.01" class="form-control dark-form-control @error('weight') is-invalid @enderror" 
                                                    id="weight" name="weight" value="{{ old('weight', $user->weight_kg) }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="body_fat_percentage" class="form-label">Body Fat (%)</label>
                                                <input type="number" step="0.01" class="form-control dark-form-control @error('body_fat_percentage') is-invalid @enderror" 
                                                    id="body_fat_percentage" name="body_fat_percentage" value="{{ old('body_fat_percentage', $user->body_fat_percentage) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="fitness_level" class="form-label">Fitness Level</label>
                                                <select class="form-control dark-form-control @error('fitness_level') is-invalid @enderror" id="fitness_level" name="fitness_level">
                                                    <option value="">Select Fitness Level</option>
                                                    <option value="beginner" {{ old('fitness_level', $user->fitness_level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                    <option value="intermediate" {{ old('fitness_level', $user->fitness_level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                    <option value="advanced" {{ old('fitness_level', $user->fitness_level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Hidden BMI field -->
                                    <input type="hidden" id="bmi" name="bmi" value="{{ old('bmi', $user->bmi) }}">
                                    <!-- BMI Status display -->
                                    <div id="bmi-status-display" class="bmi-status" style="display: none;"></div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="goal" class="form-label">Fitness Goal <span class="text-required">*</span></label>
                                        <input type="text" class="form-control dark-form-control @error('goal') is-invalid @enderror" 
                                            id="goal" name="goal" value="{{ old('goal', $user->goal) }}" placeholder="e.g., weight loss, muscle gain">
                                        <small class="form-text">Required. Example: weight loss, muscle gain, etc.</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="health_conditions" class="form-label">Health Conditions</label>
                                        <textarea class="form-control dark-form-control @error('health_conditions') is-invalid @enderror" 
                                            id="health_conditions" name="health_conditions" rows="2" placeholder="Any existing health conditions...">{{ old('health_conditions', $user->health_conditions) }}</textarea>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="injuries" class="form-label">Injuries</label>
                                        <textarea class="form-control dark-form-control @error('injuries') is-invalid @enderror" 
                                            id="injuries" name="injuries" rows="2" placeholder="Any current or past injuries...">{{ old('injuries', $user->injuries) }}</textarea>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="allergies" class="form-label">Allergies</label>
                                        <textarea class="form-control dark-form-control @error('allergies') is-invalid @enderror" 
                                            id="allergies" name="allergies" rows="2" placeholder="Any known allergies...">{{ old('allergies', $user->allergies) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="dark-section-title">
                                        <i class="fas fa-dumbbell me-2"></i>Training & Nutrition
                                    </h5>
                                    <div class="dark-alert dark-alert-info mb-4">
                                        <small><i class="fas fa-info-circle me-2"></i>These fields were set to default values when the user was created. You can customize them here if needed.</small>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="training_preferences" class="form-label">Training Preferences</label>
                                                <textarea class="form-control dark-form-control @error('training_preferences') is-invalid @enderror" 
                                                    id="training_preferences" name="training_preferences" rows="3" placeholder="Preferred training types and exercises...">{{ old('training_preferences', $user->exercise_preferences) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="training_availability" class="form-label">Training Schedule</label>
                                                <textarea class="form-control dark-form-control @error('training_availability') is-invalid @enderror" 
                                                    id="training_availability" name="training_availability" rows="3" placeholder="Available training times and schedule...">{{ old('training_availability', $user->preferred_training_time) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="diet_preferences" class="form-label">Diet Preferences</label>
                                                <textarea class="form-control dark-form-control @error('diet_preferences') is-invalid @enderror" 
                                                    id="diet_preferences" name="diet_preferences" rows="3" placeholder="Dietary preferences and restrictions...">{{ old('diet_preferences', $user->diet_preference) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="food_restrictions" class="form-label">Food Restrictions</label>
                                                <textarea class="form-control dark-form-control @error('food_restrictions') is-invalid @enderror" 
                                                    id="food_restrictions" name="food_restrictions" rows="3" placeholder="Foods to avoid or restrictions...">{{ old('food_restrictions', $user->food_dislikes) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="dark-section-title">
                                        <i class="fas fa-crown me-2"></i>Subscription & Coach Assignment
                                    </h5>
                                    <div class="dark-alert dark-alert-warning mb-4">
                                        <small><i class="fas fa-exclamation-triangle me-2"></i>Changing the subscription plan will trigger notifications to the user about their updated membership.</small>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="plan_id" class="form-label">Subscription Plan</label>
                                                <select class="form-control dark-form-control @error('plan_id') is-invalid @enderror" id="plan_id" name="plan_id">
                                                    <option value="">No Subscription</option>
                                                    @foreach($subscriptionPlans as $plan)
                                                        <option value="{{ $plan->id }}" {{ old('plan_id', $userSubscription && $userSubscription->plan_id == $plan->id ? $plan->id : '') == $plan->id ? 'selected' : '' }}>
                                                            {{ $plan->name }} - {{ number_format($plan->price, 2) }} JOD ({{ $plan->duration_days }} days)
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text">
                                                    Current subscription: 
                                                    @if($userSubscription && $userSubscription->end_date >= now())
                                                        <span class="status-active">{{ $userSubscription->plan->name }} (until {{ is_string($userSubscription->end_date) ? $userSubscription->end_date : \Carbon\Carbon::parse($userSubscription->end_date)->format('Y-m-d') }})</span>
                                                    @else
                                                        <span class="status-inactive">No active subscription</span>
                                                    @endif
                                                    <br>Selecting a new plan will create a new subscription starting today.
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="coach_id" class="form-label">Assign Coach</label>
                                                <select class="form-control dark-form-control @error('coach_id') is-invalid @enderror" id="coach_id" name="coach_id">
                                                    <option value="">No Coach Assigned</option>
                                                    @foreach($coaches as $coach)
                                                        <option value="{{ $coach->id }}" {{ old('coach_id', $user->coach_id) == $coach->id ? 'selected' : '' }}>
                                                            {{ $coach->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text">
                                                    @if($user->coach_id)
                                                        Currently assigned coach: 
                                                        @php
                                                            $currentCoach = $coaches->where('id', $user->coach_id)->first();
                                                        @endphp
                                                        <span class="status-active">{{ $currentCoach ? $currentCoach->name : 'Unknown coach' }}</span>
                                                    @else
                                                        <span class="status-inactive">No coach currently assigned</span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="form-group text-center">
                                <a href="{{ route('club.users') }}" class="dark-btn-secondary me-3">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="dark-btn-success">
                                    <i class="fas fa-save me-2"></i>Update User & Send Notifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const heightInput = document.getElementById('height');
        const weightInput = document.getElementById('weight');
        const bmiInput = document.getElementById('bmi');
        const bmiDisplay = document.getElementById('bmi-status-display');
        
        function calculateBMI() {
            const height = parseFloat(heightInput.value) / 100;
            const weight = parseFloat(weightInput.value);
            
            if (height > 0 && weight > 0) {
                const bmi = weight / (height * height);
                bmiInput.value = bmi.toFixed(2);
                
                let status = '';
                let bgColor = '';
                
                if (bmi < 18.5) {
                    status = 'BMI: ' + bmi.toFixed(1) + ' - Underweight';
                    bgColor = 'linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%)';
                } else if (bmi >= 18.5 && bmi < 25) {
                    status = 'BMI: ' + bmi.toFixed(1) + ' - Normal Weight';
                    bgColor = 'linear-gradient(135deg, #10b981 0%, #34d399 100%)';
                } else if (bmi >= 25 && bmi < 30) {
                    status = 'BMI: ' + bmi.toFixed(1) + ' - Overweight';
                    bgColor = 'linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%)';
                } else if (bmi >= 30) {
                    status = 'BMI: ' + bmi.toFixed(1) + ' - Obese';
                    bgColor = 'linear-gradient(135deg, #ef4444 0%, #f87171 100%)';
                }
                
                bmiDisplay.textContent = status;
                bmiDisplay.style.background = bgColor;
                bmiDisplay.style.color = 'white';
                bmiDisplay.style.display = 'block';
            } else {
                bmiDisplay.style.display = 'none';
            }
        }
        
        heightInput.addEventListener('input', calculateBMI);
        weightInput.addEventListener('input', calculateBMI);
        
        if (heightInput.value && weightInput.value) {
            calculateBMI();
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone_number').value.trim();
            const goal = document.getElementById('goal').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            let isValid = true;
            let errorMessage = '';
            
            if (!name) {
                errorMessage += 'Full Name is required.\n';
                isValid = false;
            }
            
            if (!email) {
                errorMessage += 'Email is required.\n';
                isValid = false;
            } else if (!isValidEmail(email)) {
                errorMessage += 'Please enter a valid email address.\n';
                isValid = false;
            }
            
            if (!phone) {
                errorMessage += 'Phone Number is required.\n';
                isValid = false;
            }
            
            if (!goal) {
                errorMessage += 'Fitness Goal is required.\n';
                isValid = false;
            }
            
            if (password) {
                if (password.length < 8) {
                    errorMessage += 'Password must be at least 8 characters long.\n';
                    isValid = false;
                }
                
                if (password !== passwordConfirm) {
                    errorMessage += 'Password and Confirm Password do not match.\n';
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please correct the following errors:\n\n' + errorMessage);
            }
        });
        
        function isValidEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    });
</script>
@endsection