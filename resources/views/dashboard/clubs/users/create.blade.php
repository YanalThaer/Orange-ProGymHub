@extends('layouts.dashboard')
@section('title', 'Add New User')

@push('styles')
<style>
    .dark-theme {
        background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
        min-height: 100vh;
        color: #ffffff;
    }

    .dark-card {
        background: linear-gradient(145deg, #1e1e1e, #2a2a2a);
        border: 1px solid #333;
        border-radius: 20px;
        box-shadow:
            0 20px 40px rgba(0, 0, 0, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    .dark-card-header {
        background: linear-gradient(135deg, #2d2d2d, #3a3a3a);
        border-bottom: 1px solid #444;
        border-radius: 20px 20px 0 0;
        padding: 20px 30px;
    }

    .dark-card-body {
        padding: 30px;
    }

    .form-control-dark {
        background: rgba(30, 30, 30, 0.8);
        border: 2px solid #444;
        color: #fff;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }

    .form-control-dark:focus {
        background: rgba(40, 40, 40, 0.9);
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        color: #fff;
    }

    .form-control-dark::placeholder {
        color: #888;
    }

    .btn-primary-dark {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-primary-dark:hover {
        background: linear-gradient(135deg, #5855eb, #7c3aed);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    }

    .btn-secondary-dark {
        background: rgba(60, 60, 60, 0.8);
        border: 1px solid #555;
        color: #fff;
        border-radius: 12px;
        padding: 12px 30px;
        transition: all 0.3s ease;
    }

    .btn-secondary-dark:hover {
        background: rgba(80, 80, 80, 0.9);
        border-color: #666;
        color: #fff;
        transform: translateY(-1px);
    }

    .alert-dark {
        border-radius: 12px;
        border: none;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .alert-primary-dark {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.1));
        border-left: 4px solid #6366f1;
        color: #e0e7ff;
    }

    .alert-info-dark {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.2), rgba(6, 182, 212, 0.1));
        border-left: 4px solid #0ea5e9;
        color: #e0f2fe;
    }

    .alert-warning-dark {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(251, 191, 36, 0.1));
        border-left: 4px solid #f59e0b;
        color: #fef3c7;
    }

    .section-header {
        color: #fff;
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #444;
        position: relative;
    }

    .section-header::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
    }

    .form-label-dark {
        color: #e5e5e5;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .text-danger {
        color: #ef4444 !important;
    }

    .form-text {
        color: #999 !important;
    }

    .bmi-display {
        background: rgba(30, 30, 30, 0.6);
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        font-weight: bold;
        margin-top: 10px;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #1a1a1a;
    }

    ::-webkit-scrollbar-thumb {
        background: #444;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .fade-in {
        animation: fadeIn 0.6s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="dark-theme">
    <div class="container mt-5 fade-in">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="dark-card">
                    <div class="dark-card-header d-flex justify-content-between align-items-center">
                        <span class="h4 mb-0 text-white">
                            <i class="fas fa-user-plus me-2"></i>Add New User
                        </span>
                        <a href="{{ route('club.users') }}" class="btn btn-secondary-dark btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Users
                        </a>
                    </div>

                    <div class="dark-card-body">
                        @if($errors->any())
                        <div class="alert alert-danger alert-dark">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dark">
                            <i class="fas fa-times-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                        @endif

                        @if(session('success'))
                        <div class="alert alert-success alert-dark">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                        @endif

                        <div class="alert alert-primary-dark">
                            <i class="fas fa-bell me-2"></i>
                            <strong>Notification System:</strong> When you create a new user, after email verification:
                            <ul class="mb-0 mt-2">
                                <li>The user will receive a welcome email and in-app notification</li>
                                <li>Your club will receive an in-app notification</li>
                                <li>All system admins will be notified</li>
                                <li>If a coach is assigned, they will also be notified</li>
                            </ul>
                        </div>

                        <div class="alert alert-info-dark">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Basic training and nutrition preferences will be set to default values. These can be updated later in the user's profile.
                        </div>

                        <form action="{{ route('club.users.store') }}" method="POST" id="create-user-form">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="section-header">
                                        <i class="fas fa-user me-2"></i>Personal Information
                                    </h5>

                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label-dark">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-dark @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label-dark">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-dark @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="phone_number" class="form-label-dark">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-dark @error('phone_number') is-invalid @enderror"
                                            id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="gender" class="form-label-dark">Gender</label>
                                        <select class="form-control form-control-dark @error('gender') is-invalid @enderror" id="gender" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="date_of_birth" class="form-label-dark">Date of Birth</label>
                                        <input type="date" class="form-control form-control-dark @error('date_of_birth') is-invalid @enderror"
                                            id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label-dark">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control form-control-dark @error('password') is-invalid @enderror"
                                            id="password" name="password" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password_confirmation" class="form-label-dark">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control form-control-dark"
                                            id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="section-header">
                                        <i class="fas fa-dumbbell me-2"></i>Fitness Information
                                    </h5>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="height" class="form-label-dark">Height (cm)</label>
                                                <input type="number" step="0.01" class="form-control form-control-dark @error('height') is-invalid @enderror"
                                                    id="height" name="height" value="{{ old('height') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="weight" class="form-label-dark">Weight (kg)</label>
                                                <input type="number" step="0.01" class="form-control form-control-dark @error('weight') is-invalid @enderror"
                                                    id="weight" name="weight" value="{{ old('weight') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="target_weight_kg" class="form-label-dark">Target Weight (kg)</label>
                                                <input type="number" step="0.01" class="form-control form-control-dark @error('target_weight_kg') is-invalid @enderror"
                                                    id="target_weight_kg" name="target_weight_kg" value="{{ old('target_weight_kg') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="body_fat_percentage" class="form-label-dark">Body Fat (%)</label>
                                                <input type="number" step="0.01" class="form-control form-control-dark @error('body_fat_percentage') is-invalid @enderror"
                                                    id="body_fat_percentage" name="body_fat_percentage" value="{{ old('body_fat_percentage') }}">
                                            </div>
                                        </div>
                                        <input type="hidden" id="bmi" name="bmi" value="{{ old('bmi') }}">
                                        <div id="bmi-status-display" class="bmi-display" style="font-weight: bold;"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="fitness_level" class="form-label-dark">Fitness Level</label>
                                                <select class="form-control form-control-dark @error('fitness_level') is-invalid @enderror" id="fitness_level" name="fitness_level">
                                                    <option value="beginner" {{ old('fitness_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                    <option value="intermediate" {{ old('fitness_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                    <option value="advanced" {{ old('fitness_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="goal" class="form-label-dark">Fitness Goal <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-dark @error('goal') is-invalid @enderror"
                                            id="goal" name="goal" value="{{ old('goal', 'General fitness') }}" placeholder="Weight loss, muscle gain, general fitness, etc.">
                                        <small class="form-text">Required. Example: weight loss, muscle gain, etc.</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="health_conditions" class="form-label-dark">Health Conditions</label>
                                        <textarea class="form-control form-control-dark @error('health_conditions') is-invalid @enderror"
                                            id="health_conditions" name="health_conditions" rows="2">{{ old('health_conditions') }}</textarea>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="injuries" class="form-label-dark">Injuries</label>
                                        <textarea class="form-control form-control-dark @error('injuries') is-invalid @enderror"
                                            id="injuries" name="injuries" rows="2">{{ old('injuries') }}</textarea>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="allergies" class="form-label-dark">Allergies</label>
                                        <textarea class="form-control form-control-dark @error('allergies') is-invalid @enderror"
                                            id="allergies" name="allergies" rows="2">{{ old('allergies') }}</textarea>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="medications" class="form-label-dark">Medications</label>
                                        <textarea class="form-control form-control-dark @error('medications') is-invalid @enderror"
                                            id="medications" name="medications" rows="2">{{ old('medications') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="section-header">
                                        <i class="fas fa-calendar-alt me-2"></i>Training & Nutrition Preferences
                                    </h5>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="training_days_per_week" class="form-label-dark">Training Days Per Week</label>
                                                <select class="form-control form-control-dark @error('training_days_per_week') is-invalid @enderror" id="training_days_per_week" name="training_days_per_week">
                                                    @for($i = 1; $i <= 7; $i++)
                                                        <option value="{{ $i }}" {{ old('training_days_per_week', 3) == $i ? 'selected' : '' }}>{{ $i }} day(s)</option>
                                                        @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="preferred_training_time" class="form-label-dark">Preferred Training Time</label>
                                                <select class="form-control form-control-dark @error('preferred_training_time') is-invalid @enderror" id="preferred_training_time" name="preferred_training_time">
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
                                                <label for="preferred_workout_duration" class="form-label-dark">Preferred Workout Duration (minutes)</label>
                                                <select class="form-control form-control-dark @error('preferred_workout_duration') is-invalid @enderror" id="preferred_workout_duration" name="preferred_workout_duration">
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
                                                <label for="diet_preference" class="form-label-dark">Diet Preference</label>
                                                <select class="form-control form-control-dark @error('diet_preference') is-invalid @enderror" id="diet_preference" name="diet_preference">
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
                                                <label for="exercise_preferences" class="form-label-dark">Exercise Preferences</label>
                                                <textarea class="form-control form-control-dark @error('exercise_preferences') is-invalid @enderror"
                                                    id="exercise_preferences" name="exercise_preferences" rows="2">{{ old('exercise_preferences', 'General fitness exercises') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="exercise_dislikes" class="form-label-dark">Exercise Dislikes</label>
                                                <textarea class="form-control form-control-dark @error('exercise_dislikes') is-invalid @enderror"
                                                    id="exercise_dislikes" name="exercise_dislikes" rows="2">{{ old('exercise_dislikes') }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="meals_per_day" class="form-label-dark">Meals Per Day</label>
                                                <select class="form-control form-control-dark @error('meals_per_day') is-invalid @enderror" id="meals_per_day" name="meals_per_day">
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
                                                <label for="food_preferences" class="form-label-dark">Food Preferences</label>
                                                <textarea class="form-control form-control-dark @error('food_preferences') is-invalid @enderror"
                                                    id="food_preferences" name="food_preferences" rows="2">{{ old('food_preferences') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="food_dislikes" class="form-label-dark">Food Dislikes</label>
                                                <textarea class="form-control form-control-dark @error('food_dislikes') is-invalid @enderror"
                                                    id="food_dislikes" name="food_dislikes" rows="2">{{ old('food_dislikes') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="section-header">
                                        <i class="fas fa-crown me-2"></i>Subscription & Coach
                                    </h5>

                                    <div class="alert alert-warning-dark mb-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        The user will be notified about their subscription plan and assigned coach after email verification.
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="coach_id" class="form-label-dark">Assign Coach</label>
                                        <select class="form-control form-control-dark @error('coach_id') is-invalid @enderror" id="coach_id" name="coach_id">
                                            <option value="">No Coach Assigned</option>
                                            @foreach($coaches as $coach)
                                            <option value="{{ $coach->id }}" {{ old('coach_id') == $coach->id ? 'selected' : '' }}>
                                                {{ $coach->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text">
                                            If selected, this coach will be assigned to the user.
                                        </small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="plan_id" class="form-label-dark">Subscription Plan</label>
                                        <select class="form-control form-control-dark @error('plan_id') is-invalid @enderror" id="plan_id" name="plan_id">
                                            <option value="">No Subscription</option>
                                            @foreach($subscriptionPlans as $plan)
                                            <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }} - {{ number_format($plan->price, 2) }} JOD ({{ $plan->duration_days }} days)
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text">
                                            If selected, a subscription will be created for this user starting today.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-center mt-5">
                                <a href="{{ route('club.users') }}" class="btn btn-secondary-dark me-3">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary-dark">
                                    <i class="fas fa-user-plus me-2"></i> Create User & Send Verification
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
        const form = document.getElementById('create-user-form');

        function calculateBMI() {
            const height = parseFloat(heightInput.value) / 100;
            const weight = parseFloat(weightInput.value);

            if (height > 0 && weight > 0) {
                const bmi = weight / (height * height);
                bmiInput.value = bmi.toFixed(2); 

                let status = '';
                let color = '';

                if (bmi < 18.5) {
                    status = 'BMI Status: Underweight (' + bmi.toFixed(1) + ')';
                    color = '#3498db'; // blue
                } else if (bmi >= 18.5 && bmi < 25) {
                    status = 'BMI Status: Normal weight (' + bmi.toFixed(1) + ')';
                    color = '#2ecc71'; // green
                } else if (bmi >= 25 && bmi < 30) {
                    status = 'BMI Status: Overweight (' + bmi.toFixed(1) + ')';
                    color = '#f39c12'; // orange
                } else if (bmi >= 30) {
                    status = 'BMI Status: Obese (' + bmi.toFixed(1) + ')';
                    color = '#e74c3c'; // red
                }

                bmiDisplay.textContent = status;
                bmiDisplay.style.color = color;
                bmiDisplay.style.display = 'block';
            } else {
                bmiDisplay.textContent = '';
                bmiDisplay.style.display = 'none';
            }
        }

        heightInput.addEventListener('input', calculateBMI);
        weightInput.addEventListener('input', calculateBMI);

        if (heightInput.value && weightInput.value) {
            calculateBMI();
        }

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
                showCustomAlert('Please correct the following errors:\n\n' + errorMessage);
            }
        });

        function isValidEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }

        function showCustomAlert(message) {
            const alertModal = document.createElement('div');
            alertModal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.8);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 10000;
            `;

            const alertBox = document.createElement('div');
            alertBox.style.cssText = `
                background: linear-gradient(145deg, #2a2a2a, #1e1e1e);
                border: 1px solid #444;
                border-radius: 15px;
                padding: 30px;
                max-width: 500px;
                color: white;
                box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            `;

            alertBox.innerHTML = `
                <div style="text-align: center;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #f59e0b; margin-bottom: 20px;"></i>
                    <h4 style="color: #fff; margin-bottom: 15px;">Validation Error</h4>
                    <p style="white-space: pre-line; color: #e5e5e5; line-height: 1.6;">${message}</p>
                    <button onclick="this.closest('.alert-modal').remove()" 
                            style="background: linear-gradient(135deg, #6366f1, #8b5cf6); 
                                   border: none; 
                                   color: white; 
                                   padding: 12px 30px; 
                                   border-radius: 8px; 
                                   cursor: pointer; 
                                   font-weight: 600; 
                                   margin-top: 20px;
                                   transition: all 0.3s ease;">
                        OK
                    </button>
                </div>
            `;

            alertModal.className = 'alert-modal';
            alertModal.appendChild(alertBox);
            document.body.appendChild(alertModal);

            const button = alertBox.querySelector('button');
            button.addEventListener('mouseenter', function() {
                this.style.background = 'linear-gradient(135deg, #5855eb, #7c3aed)';
                this.style.transform = 'translateY(-2px)';
            });
            button.addEventListener('mouseleave', function() {
                this.style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)';
                this.style.transform = 'translateY(0)';
            });
        }

        const inputs = document.querySelectorAll('.form-control-dark');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            input.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
        });
    });
</script>
@endsection