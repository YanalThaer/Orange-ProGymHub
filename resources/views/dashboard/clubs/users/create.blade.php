@extends('layouts.dashboard')
@section('title', 'Add New User')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Add New User</span>
                    <a href="{{ route('club.users') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>                    @endif
                    
                    <div class="alert alert-primary">
                        <i class="fas fa-bell"></i> <strong>Notification System:</strong> When you create a new user, after email verification:
                        <ul class="mb-0 mt-1">
                            <li>The user will receive a welcome email and in-app notification</li>
                            <li>Your club will receive an in-app notification</li>
                            <li>All system admins will be notified</li>
                            <li>If a coach is assigned, they will also be notified</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Note: Basic training and nutrition preferences will be set to default values. These can be updated later in the user's profile.
                    </div><form action="{{ route('club.users.store') }}" method="POST" id="create-user-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Personal Information</h5>
                                
                                <div class="form-group mb-3">
                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                        id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                        id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                        id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                                </div>
                                  <div class="form-group mb-3">
                                    <label for="gender">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                        id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                        id="password" name="password" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                        id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="mb-3">Fitness Information</h5>
                                  <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="height">Height (cm)</label>
                                            <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror" 
                                                id="height" name="height" value="{{ old('height') }}">
                                        </div>
                                    </div>                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="weight">Weight (kg)</label>
                                            <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" 
                                                id="weight" name="weight" value="{{ old('weight') }}">
                                        </div>
                                    </div>
                                </div>                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="target_weight_kg">Target Weight (kg)</label>
                                            <input type="number" step="0.01" class="form-control @error('target_weight_kg') is-invalid @enderror" 
                                                id="target_weight_kg" name="target_weight_kg" value="{{ old('target_weight_kg') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="body_fat_percentage">Body Fat (%)</label>
                                            <input type="number" step="0.01" class="form-control @error('body_fat_percentage') is-invalid @enderror" 
                                                id="body_fat_percentage" name="body_fat_percentage" value="{{ old('body_fat_percentage') }}">
                                        </div>
                                    </div>
                                    <!-- Hidden BMI field -->
                                    <input type="hidden" id="bmi" name="bmi" value="{{ old('bmi') }}">
                                    <!-- BMI Status display -->
                                    <div id="bmi-status-display" class="mt-2 text-center" style="font-weight: bold;"></div>
                                </div>
                                
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="fitness_level">Fitness Level</label>
                                            <select class="form-control @error('fitness_level') is-invalid @enderror" id="fitness_level" name="fitness_level">
                                                <option value="beginner" {{ old('fitness_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                <option value="intermediate" {{ old('fitness_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                <option value="advanced" {{ old('fitness_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>                                <div class="form-group mb-3">
                                    <label for="goal">Fitness Goal <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('goal') is-invalid @enderror" 
                                        id="goal" name="goal" value="{{ old('goal', 'General fitness') }}" placeholder="Weight loss, muscle gain, general fitness, etc.">
                                    <small class="form-text text-muted">Required. Example: weight loss, muscle gain, etc.</small>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="health_conditions">Health Conditions</label>
                                    <textarea class="form-control @error('health_conditions') is-invalid @enderror" 
                                        id="health_conditions" name="health_conditions" rows="2">{{ old('health_conditions') }}</textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="injuries">Injuries</label>
                                    <textarea class="form-control @error('injuries') is-invalid @enderror" 
                                        id="injuries" name="injuries" rows="2">{{ old('injuries') }}</textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="allergies">Allergies</label>
                                    <textarea class="form-control @error('allergies') is-invalid @enderror" 
                                        id="allergies" name="allergies" rows="2">{{ old('allergies') }}</textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="medications">Medications</label>
                                    <textarea class="form-control @error('medications') is-invalid @enderror" 
                                        id="medications" name="medications" rows="2">{{ old('medications') }}</textarea>
                                </div>
                            </div>
                        </div>
                          <div class="row mt-4">
                            <div class="col-md-12">
                                <h5 class="mb-3">Training & Nutrition Preferences</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="training_days_per_week">Training Days Per Week</label>
                                            <select class="form-control @error('training_days_per_week') is-invalid @enderror" id="training_days_per_week" name="training_days_per_week">
                                                @for($i = 1; $i <= 7; $i++)
                                                    <option value="{{ $i }}" {{ old('training_days_per_week', 3) == $i ? 'selected' : '' }}>{{ $i }} day(s)</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="preferred_training_time">Preferred Training Time</label>
                                            <select class="form-control @error('preferred_training_time') is-invalid @enderror" id="preferred_training_time" name="preferred_training_time">
                                                <option value="morning" {{ old('preferred_training_time') == 'morning' ? 'selected' : '' }}>Morning</option>
                                                <option value="afternoon" {{ old('preferred_training_time') == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                                                <option value="evening" {{ old('preferred_training_time', 'evening') == 'evening' ? 'selected' : '' }}>Evening</option>
                                                <option value="night" {{ old('preferred_training_time') == 'night' ? 'selected' : '' }}>Night</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="preferred_workout_duration">Preferred Workout Duration (minutes)</label>
                                            <select class="form-control @error('preferred_workout_duration') is-invalid @enderror" id="preferred_workout_duration" name="preferred_workout_duration">
                                                <option value="30" {{ old('preferred_workout_duration') == '30' ? 'selected' : '' }}>30 minutes</option>
                                                <option value="45" {{ old('preferred_workout_duration') == '45' ? 'selected' : '' }}>45 minutes</option>
                                                <option value="60" {{ old('preferred_workout_duration', '60') == '60' ? 'selected' : '' }}>60 minutes</option>
                                                <option value="90" {{ old('preferred_workout_duration') == '90' ? 'selected' : '' }}>90 minutes</option>
                                                <option value="120" {{ old('preferred_workout_duration') == '120' ? 'selected' : '' }}>120 minutes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="diet_preference">Diet Preference</label>
                                            <select class="form-control @error('diet_preference') is-invalid @enderror" id="diet_preference" name="diet_preference">
                                                <option value="no_restriction" {{ old('diet_preference', 'no_restriction') == 'no_restriction' ? 'selected' : '' }}>No Restrictions</option>
                                                <option value="vegetarian" {{ old('diet_preference') == 'vegetarian' ? 'selected' : '' }}>Vegetarian</option>
                                                <option value="vegan" {{ old('diet_preference') == 'vegan' ? 'selected' : '' }}>Vegan</option>
                                                <option value="keto" {{ old('diet_preference') == 'keto' ? 'selected' : '' }}>Keto</option>
                                                <option value="paleo" {{ old('diet_preference') == 'paleo' ? 'selected' : '' }}>Paleo</option>
                                                <option value="low_carb" {{ old('diet_preference') == 'low_carb' ? 'selected' : '' }}>Low Carb</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="exercise_preferences">Exercise Preferences</label>
                                            <textarea class="form-control @error('exercise_preferences') is-invalid @enderror" 
                                                id="exercise_preferences" name="exercise_preferences" rows="2">{{ old('exercise_preferences', 'General fitness exercises') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="exercise_dislikes">Exercise Dislikes</label>
                                            <textarea class="form-control @error('exercise_dislikes') is-invalid @enderror" 
                                                id="exercise_dislikes" name="exercise_dislikes" rows="2">{{ old('exercise_dislikes') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="meals_per_day">Meals Per Day</label>
                                            <select class="form-control @error('meals_per_day') is-invalid @enderror" id="meals_per_day" name="meals_per_day">
                                                @for($i = 1; $i <= 8; $i++)
                                                    <option value="{{ $i }}" {{ old('meals_per_day', 3) == $i ? 'selected' : '' }}>{{ $i }} meal(s)</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="food_preferences">Food Preferences</label>
                                            <textarea class="form-control @error('food_preferences') is-invalid @enderror" 
                                                id="food_preferences" name="food_preferences" rows="2">{{ old('food_preferences') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="food_dislikes">Food Dislikes</label>
                                            <textarea class="form-control @error('food_dislikes') is-invalid @enderror" 
                                                id="food_dislikes" name="food_dislikes" rows="2">{{ old('food_dislikes') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                          <div class="row mt-4">                            <div class="col-md-12">
                                <h5 class="mb-3">Subscription & Coach</h5>
                                
                                <div class="alert alert-warning mb-3">
                                    <small><i class="fas fa-exclamation-triangle"></i> The user will be notified about their subscription plan and assigned coach after email verification.</small>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="coach_id">Assign Coach</label>
                                    <select class="form-control @error('coach_id') is-invalid @enderror" id="coach_id" name="coach_id">
                                        <option value="">No Coach Assigned</option>
                                        @foreach($coaches as $coach)
                                            <option value="{{ $coach->id }}" {{ old('coach_id') == $coach->id ? 'selected' : '' }}>
                                                {{ $coach->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        If selected, this coach will be assigned to the user.
                                    </small>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="plan_id">Subscription Plan</label>
                                    <select class="form-control @error('plan_id') is-invalid @enderror" id="plan_id" name="plan_id">
                                        <option value="">No Subscription</option>
                                        @foreach($subscriptionPlans as $plan)
                                            <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }} - {{ number_format($plan->price, 2) }} JOD ({{ $plan->duration_days }} days)
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        If selected, a subscription will be created for this user starting today.
                                    </small>
                                </div>
                            </div>
                        </div>
                          <div class="form-group text-center mt-4">
                            <a href="{{ route('club.users') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Create User & Send Verification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>    // Auto-calculate BMI when height and weight are entered
    document.addEventListener('DOMContentLoaded', function() {        const heightInput = document.getElementById('height');
        const weightInput = document.getElementById('weight');
        const bmiInput = document.getElementById('bmi');
        const bmiDisplay = document.getElementById('bmi-status-display');
        const form = document.getElementById('create-user-form');
        
        // Calculate BMI and update status text
        function calculateBMI() {
            const height = parseFloat(heightInput.value) / 100; // convert to meters
            const weight = parseFloat(weightInput.value);
            
            if (height > 0 && weight > 0) {
                const bmi = weight / (height * height);
                bmiInput.value = bmi.toFixed(2); // Store BMI value in hidden field
                
                // Update BMI status text and color based on calculated value
                let status = '';
                let color = '';
                
                if (bmi < 18.5) {
                    status = 'BMI Status: Underweight';
                    color = '#3498db'; // blue
                } else if (bmi >= 18.5 && bmi < 25) {
                    status = 'BMI Status: Normal weight';
                    color = '#2ecc71'; // green
                } else if (bmi >= 25 && bmi < 30) {
                    status = 'BMI Status: Overweight';
                    color = '#f39c12'; // orange
                } else if (bmi >= 30) {
                    status = 'BMI Status: Obese';
                    color = '#e74c3c'; // red
                }
                
                bmiDisplay.textContent = status;
                bmiDisplay.style.color = color;
            } else {
                // Clear BMI status if height or weight is missing
                bmiDisplay.textContent = '';
            }
        }
        
        heightInput.addEventListener('input', calculateBMI);
        weightInput.addEventListener('input', calculateBMI);
        
        // Initial calculation if values are present
        if (heightInput.value && weightInput.value) {
            calculateBMI();
        }
        
        // Validate form on submit
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone_number').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            const goal = document.getElementById('goal').value.trim();
            
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
            
            if (!password) {
                errorMessage += 'Password is required.\n';
                isValid = false;
            } else if (password.length < 8) {
                errorMessage += 'Password must be at least 8 characters long.\n';
                isValid = false;
            }
            
            if (password !== passwordConfirm) {
                errorMessage += 'Password and Confirm Password do not match.\n';
                isValid = false;
            }
            
            if (!goal) {
                errorMessage += 'Fitness Goal is required.\n';
                isValid = false;
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
        
        // Initial calculation if values are present
        if (heightInput.value && weightInput.value) {
            calculateBMI();
        }
        
        // Form validation
        form.addEventListener('submit', function(event) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone_number').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const goal = document.getElementById('goal').value.trim();
            
            let hasErrors = false;
            let errorMessages = [];
            
            if (!name) {
                errorMessages.push('Name is required');
                hasErrors = true;
            }
            
            if (!email) {
                errorMessages.push('Email is required');
                hasErrors = true;
            }
            
            if (!phone) {
                errorMessages.push('Phone number is required');
                hasErrors = true;
            }
            
            if (!password) {
                errorMessages.push('Password is required');
                hasErrors = true;
            }
            
            if (password !== confirmPassword) {
                errorMessages.push('Password and confirmation do not match');
                hasErrors = true;
            }
            
            if (!goal) {
                errorMessages.push('Fitness goal is required');
                hasErrors = true;
            }
            
            if (hasErrors) {
                event.preventDefault();
                alert('Please fix the following errors:\n' + errorMessages.join('\n'));
            }
        });
    });
</script>
@endsection
