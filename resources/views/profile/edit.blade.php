@extends('layouts.public')
@section('title', 'ProGymHub - Edit Profile')
@section('content')
<style>
    body {
        background-color: #121212;
        color: #000 !important;
        font-size: medium !important;
    }

    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

    .profile-edit-container {
        max-width: 1000px;
        margin: auto;
        padding: 2rem;
    }

    .card {
        background-color: #1f1f1f !important;
        color: #fff !important;
        border: none;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }

    .card-header {
        background-color: #ff0000 !important;

        font-weight: bold;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .form-control,
    .form-select {
        background-color: #2c2c2c;
        border: 1px solid #444;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #ff0000;
        box-shadow: 0 0 0 0.25rem rgba(255, 0, 0, 0.25);
    }

    .form-select .current {
        color: #000 !important;
    }

    .form-control::placeholder {
        color: #888;
    }

    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        appearance: none;
        padding-right: 2rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-select:hover {
        border-color: #ff0000;
    }

    .custom-select-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #ff0000;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .form-select option {
        background-color: #1f1f1f;
        padding: 10px;
        font-size: 16px;
    }

    .form-select {
        padding: 12px 15px;
        font-size: 16px;
        border-radius: 8px;
        border: 1px solid #444;
    }

    .form-select:focus+.custom-select-icon,
    .form-select:hover+.custom-select-icon {
        color: #000 !important;
    }

    ul {
        color: black !important;
    }

    .form-select:focus {
        border: 2px solid #ff0000;
    }

    .form-label {
        color: #aaa;
    }

    .btn-danger {
        background-color: #ff0000;
        border-color: #ff0000;
    }

    .btn-danger:hover {
        background-color: #d60000;
        border-color: #d60000;
    }

    .btn-outline-secondary {
        color: #aaa;
        border-color: #444;
    }

    .btn-outline-secondary:hover {
        background-color: #333;
    }

    .invalid-feedback {
        color: #ff6b6b;
    }
</style>
<div class="container profile-edit-container mt-5">
    <h2 class="mt-5 text-white">Edit Profile</h2>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('status'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div id="formStatusMessage" class="alert alert-info alert-dismissible fade d-none" role="alert">
        <span id="statusMessageContent"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="debug_timestamp" value="{{ time() }}">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header" style="">Account Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                            @error('phone_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Change Password (Optional)</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password">
                            @error('current_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                            @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header" style="">Personal Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}">
                            @error('date_of_birth')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" class="form-select @error('gender') is-invalid @enderror" name="gender">
                                <option value="">Select gender</option>
                                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>♂ Male</option>
                                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>♀ Female</option>
                            </select>
                            @error('gender')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header" style="">Health Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="health_conditions" class="form-label">Health Conditions</label>
                            <textarea id="health_conditions" class="form-control @error('health_conditions') is-invalid @enderror" name="health_conditions" rows="2">{{ old('health_conditions', $user->health_conditions) }}</textarea>
                            @error('health_conditions')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="injuries" class="form-label">Injuries</label>
                            <textarea id="injuries" class="form-control @error('injuries') is-invalid @enderror" name="injuries" rows="2">{{ old('injuries', $user->injuries) }}</textarea>
                            @error('injuries')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="allergies" class="form-label">Allergies</label>
                            <textarea id="allergies" class="form-control @error('allergies') is-invalid @enderror" name="allergies" rows="2">{{ old('allergies', $user->allergies) }}</textarea>
                            @error('allergies')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="medications" class="form-label">Medications</label>
                            <textarea id="medications" class="form-control @error('medications') is-invalid @enderror" name="medications" rows="2">{{ old('medications', $user->medications) }}</textarea>
                            @error('medications')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header" style="">Physical Attributes</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="height_cm" class="form-label">Height (cm)</label>
                            <input id="height_cm" type="number" step="0.1" class="form-control @error('height_cm') is-invalid @enderror" name="height_cm" value="{{ old('height_cm', $user->height_cm) }}">
                            @error('height_cm')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="weight_kg" class="form-label">Current Weight (kg)</label>
                            <input id="weight_kg" type="number" step="0.1" class="form-control @error('weight_kg') is-invalid @enderror" name="weight_kg" value="{{ old('weight_kg', $user->weight_kg) }}">
                            @error('weight_kg')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="target_weight_kg" class="form-label">Target Weight (kg)</label>
                            <input id="target_weight_kg" type="number" step="0.1" class="form-control @error('target_weight_kg') is-invalid @enderror" name="target_weight_kg" value="{{ old('target_weight_kg', $user->target_weight_kg) }}">
                            @error('target_weight_kg')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header" style="">Fitness Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="goal" class="form-label">Fitness Goal</label>
                            <select id="goal" class="form-select @error('goal') is-invalid @enderror" name="goal" required>
                                <option value="">Select goal</option>
                                <option value="weight_loss" {{ old('goal', $user->goal) === 'weight_loss' ? 'selected' : '' }}>⚖️ Weight Loss</option>
                                <option value="muscle_gain" {{ old('goal', $user->goal) === 'muscle_gain' ? 'selected' : '' }}>💪 Muscle Gain</option>
                                <option value="strength" {{ old('goal', $user->goal) === 'strength' ? 'selected' : '' }}>🏋️ Strength</option>
                                <option value="endurance" {{ old('goal', $user->goal) === 'endurance' ? 'selected' : '' }}>🏃 Endurance</option>
                                <option value="flexibility" {{ old('goal', $user->goal) === 'flexibility' ? 'selected' : '' }}>🧘 Flexibility</option>
                                <option value="general_fitness" {{ old('goal', $user->goal) === 'general_fitness' ? 'selected' : '' }}>🏆 General Fitness</option>
                                <option value="maintenance" {{ old('goal', $user->goal) === 'maintenance' ? 'selected' : '' }}>✅ Maintenance</option>
                            </select>
                            @error('goal')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="fitness_level" class="form-label">Fitness Level</label>
                            <select id="fitness_level" class="form-select @error('fitness_level') is-invalid @enderror" name="fitness_level" required>
                                <option value="">Select level</option>
                                <option value="beginner" {{ old('fitness_level', $user->fitness_level) === 'beginner' ? 'selected' : '' }}>🌱 Beginner</option>
                                <option value="intermediate" {{ old('fitness_level', $user->fitness_level) === 'intermediate' ? 'selected' : '' }}>🌿 Intermediate</option>
                                <option value="advanced" {{ old('fitness_level', $user->fitness_level) === 'advanced' ? 'selected' : '' }}>🌳 Advanced</option>
                                <option value="athlete" {{ old('fitness_level', $user->fitness_level) === 'athlete' ? 'selected' : '' }}>🏅 Athlete</option>
                            </select>
                            @error('fitness_level')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="training_days_per_week" class="form-label">Training Days Per Week</label>
                            <input id="training_days_per_week" type="number" min="0" max="7" class="form-control @error('training_days_per_week') is-invalid @enderror" name="training_days_per_week" value="{{ old('training_days_per_week', $user->training_days_per_week) }}">
                            @error('training_days_per_week')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="preferred_training_time" class="form-label">Preferred Training Time</label>
                            <select id="preferred_training_time" class="form-select @error('preferred_training_time') is-invalid @enderror" name="preferred_training_time">
                                <option value="">Select time</option>
                                <option value="morning" {{ old('preferred_training_time', $user->preferred_training_time) === 'morning' ? 'selected' : '' }}>🌅 Morning</option>
                                <option value="afternoon" {{ old('preferred_training_time', $user->preferred_training_time) === 'afternoon' ? 'selected' : '' }}>☀️ Afternoon</option>
                                <option value="evening" {{ old('preferred_training_time', $user->preferred_training_time) === 'evening' ? 'selected' : '' }}>🌙 Evening</option>
                            </select>
                            @error('preferred_training_time')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="preferred_workout_duration" class="form-label">Preferred Workout Duration</label>
                            <select id="preferred_workout_duration" class="form-select @error('preferred_workout_duration') is-invalid @enderror" name="preferred_workout_duration">
                                <option value="">Select duration</option>
                                <option value="30_minutes" {{ old('preferred_workout_duration', $user->preferred_workout_duration) === '30_minutes' ? 'selected' : '' }}>⏱️ 30 Minutes</option>
                                <option value="45_minutes" {{ old('preferred_workout_duration', $user->preferred_workout_duration) === '45_minutes' ? 'selected' : '' }}>⏱️ 45 Minutes</option>
                                <option value="60_minutes" {{ old('preferred_workout_duration', $user->preferred_workout_duration) === '60_minutes' ? 'selected' : '' }}>⏱️ 60 Minutes</option>
                                <option value="90_minutes" {{ old('preferred_workout_duration', $user->preferred_workout_duration) === '90_minutes' ? 'selected' : '' }}>⌛ 90 Minutes</option>
                                <option value="2_hours" {{ old('preferred_workout_duration', $user->preferred_workout_duration) === '2_hours' ? 'selected' : '' }}>⌛ 2 Hours+</option>
                            </select>
                            @error('preferred_workout_duration')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exercise_preferences" class="form-label">Exercise Preferences</label>
                            <textarea id="exercise_preferences" class="form-control @error('exercise_preferences') is-invalid @enderror" name="exercise_preferences" rows="2" placeholder="e.g., Running, Swimming, Weightlifting">{{ old('exercise_preferences', $user->exercise_preferences) }}</textarea>
                            @error('exercise_preferences')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exercise_dislikes" class="form-label">Exercise Dislikes</label>
                            <textarea id="exercise_dislikes" class="form-control @error('exercise_dislikes') is-invalid @enderror" name="exercise_dislikes" rows="2" placeholder="e.g., Burpees, Treadmill, Jump Rope">{{ old('exercise_dislikes', $user->exercise_dislikes) }}</textarea>
                            @error('exercise_dislikes')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header" style="">Diet Preferences</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="diet_preference" class="form-label">Diet Type</label>
                            <select id="diet_preference" class="form-select @error('diet_preference') is-invalid @enderror" name="diet_preference">
                                <option value="">Select diet type</option>
                                <option value="no_restrictions" {{ old('diet_preference', $user->diet_preference) === 'no_restrictions' ? 'selected' : '' }}>🍽️ No Restrictions</option>
                                <option value="vegetarian" {{ old('diet_preference', $user->diet_preference) === 'vegetarian' ? 'selected' : '' }}>🥗 Vegetarian</option>
                                <option value="vegan" {{ old('diet_preference', $user->diet_preference) === 'vegan' ? 'selected' : '' }}>🌱 Vegan</option>
                                <option value="keto" {{ old('diet_preference', $user->diet_preference) === 'keto' ? 'selected' : '' }}>🥓 Keto</option>
                                <option value="paleo" {{ old('diet_preference', $user->diet_preference) === 'paleo' ? 'selected' : '' }}>🍖 Paleo</option>
                                <option value="low_carb" {{ old('diet_preference', $user->diet_preference) === 'low_carb' ? 'selected' : '' }}>🥦 Low Carb</option>
                                <option value="other" {{ old('diet_preference', $user->diet_preference) === 'other' ? 'selected' : '' }}>📋 Other</option>
                            </select>
                            @error('diet_preference')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="meals_per_day" class="form-label">Meals Per Day</label>
                            <input id="meals_per_day" type="number" min="1" max="10" class="form-control @error('meals_per_day') is-invalid @enderror" name="meals_per_day" value="{{ old('meals_per_day', $user->meals_per_day) }}">
                            @error('meals_per_day')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="food_preferences" class="form-label">Food Preferences</label>
                            <textarea id="food_preferences" class="form-control @error('food_preferences') is-invalid @enderror" name="food_preferences" rows="2" placeholder="e.g., Fish, Fruits, Whole Grains">{{ old('food_preferences', $user->food_preferences) }}</textarea>
                            @error('food_preferences')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="food_dislikes" class="form-label">Food Dislikes</label>
                            <textarea id="food_dislikes" class="form-control @error('food_dislikes') is-invalid @enderror" name="food_dislikes" rows="2" placeholder="e.g., Fast Food, Processed Sugar, Dairy">{{ old('food_dislikes', $user->food_dislikes) }}</textarea>
                            @error('food_dislikes')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary btn-lg" id="updateProfileBtn">
                <span class="spinner-border spinner-border-sm d-none" id="submitSpinner" role="status" aria-hidden="true"></span>
                <span id="submitBtnText">Update Profile</span>
            </button>
        </div>
        <div class="text-center mt-3 save-indicator d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Saving...</span>
            </div>
            <p class="mt-2">Saving your changes...</p>
        </div>
    </form>
</div>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profileForm');
        const submitBtn = document.getElementById('saveProfileBtn');
        const submitSpinner = document.getElementById('submitSpinner');
        const submitBtnText = document.getElementById('submitBtnText');
        const formStatusMessage = document.getElementById('formStatusMessage');
        const statusMessageContent = document.getElementById('statusMessageContent');
        form.addEventListener('submit', function(e) {
            submitBtn.setAttribute('disabled', true);
            submitSpinner.classList.remove('d-none');
            submitBtnText.textContent = 'Saving...';
            statusMessageContent.textContent = 'Saving your profile changes...';
            formStatusMessage.classList.remove('d-none', 'alert-danger');
            formStatusMessage.classList.add('show', 'alert-info');
            console.log('Form submission initiated at: ' + new Date().toISOString());
            console.log('Form debug timestamp: ' + document.querySelector('[name="debug_timestamp"]').value);
            return true;
        });
        const selectElements = document.querySelectorAll('.form-select');
        selectElements.forEach(select => {
            select.addEventListener('change', function() {
                const parent = this.closest('.custom-select-wrapper');
                if (parent) {
                    const icon = parent.querySelector('.custom-select-icon');
                    if (icon) {
                        icon.classList.add('pulse-animation');
                        setTimeout(() => {
                            icon.classList.remove('pulse-animation');
                        }, 500);
                    }
                }
            });
        });
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes pulse {
                0% { transform: translateY(-50%) scale(1); }
                50% { transform: translateY(-50%) scale(1.2); color: #ff0000; }
                100% { transform: translateY(-50%) scale(1); }
            }
            .pulse-animation {
                animation: pulse 0.5s ease;
            }
        `;
        document.head.appendChild(style);
        const heightInput = document.getElementById('height_cm');
        const weightInput = document.getElementById('weight_kg');

        function calculateBMI() {
            if (heightInput?.value && weightInput?.value) {
                const heightInMeters = parseFloat(heightInput.value) / 100;
                const weightKg = parseFloat(weightInput.value);
                if (heightInMeters > 0 && weightKg > 0) {
                    const bmi = weightKg / (heightInMeters * heightInMeters);
                    let bmiDisplay = document.getElementById('bmi-display');
                    if (!bmiDisplay) {
                        bmiDisplay = document.createElement('div');
                        bmiDisplay.id = 'bmi-display';
                        bmiDisplay.className = 'mt-3 mb-0 text-info';
                        weightInput.parentElement.appendChild(bmiDisplay);
                    }
                    bmiDisplay.textContent = `Calculated BMI: ${bmi.toFixed(2)}`;
                }
            }
        }
        if (heightInput && weightInput) {
            heightInput.addEventListener('input', calculateBMI);
            weightInput.addEventListener('input', calculateBMI);
            calculateBMI();
        }
    });
</script>
@endsection