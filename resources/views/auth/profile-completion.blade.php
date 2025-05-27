@extends('layouts.app')
@section('title', 'ProGymHub - Verify Email Address')
@section('content')
<style>
    body {
        background-color: #121212;
        min-height: 100vh;
        margin: 0;
        color: white;
    }

    .profile-card {
        background-color: #1f1f1f;
        border: none;
        border-radius: 15px;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .card-header {
        background-color: #ff0000 !important;
        border-radius: 15px 15px 0 0 !important;
        border: none;
    }

    .form-control,
    .form-select {
        background-color: #2b2b2b;
        color: white;
        border: 1px solid #444;
        border-radius: 8px;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #2b2b2b;
        color: white;
        border-color: #ff0000;
        box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25);
    }

    .form-control::placeholder {
        color: #aaa;
    }

    .form-label {
        color: #fff;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .btn-danger {
        background-color: #ff0000;
        border-color: #ff0000;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-danger:hover {
        background-color: #e60000;
        border-color: #e60000;
    }

    .btn-outline-secondary {
        color: #fff;
        border-color: #444;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-outline-secondary:hover {
        background-color: #444;
        border-color: #444;
        color: #fff;
    }

    .section-title {
        color: #ff0000;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #333;
    }

    .alert-success {
        background-color: #1a4d3a;
        border-color: #2d7a5f;
        color: #b8e6d0;
        border-radius: 8px;
    }

    .invalid-feedback {
        color: #ff6b6b;
    }

    .is-invalid {
        border-color: #ff6b6b;
    }

    hr {
        border-color: #333;
        margin: 2rem 0;
    }

    .container {
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    .form-section {
        background-color: #252525;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }

    option {
        background-color: #2b2b2b;
        color: white;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="profile-card">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/image.png') }}" alt="Logo" style="width: 300px; height: 100px;" class="me-2">
                </div>
                <div class="card-header text-white p-4">
                    <h4 class="mb-0 fw-bold text-center">{{ __('Complete Your Profile') }}</h4>
                    <p class="mb-0 text-center mt-2 opacity-75">Build your personalized fitness journey</p>
                </div>

                <div class="card-body p-4">
                    @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('profile.complete') }}">
                        @csrf

                        <!-- Personal Information Section -->
                        <div class="form-section">
                            <h5 class="section-title">Personal Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                        @error('date_of_birth')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select id="gender" class="form-select @error('gender') is-invalid @enderror" name="gender">
                                            <option value="">Select gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Physical Attributes Section -->
                        <div class="form-section">
                            <h5 class="section-title">Physical Attributes</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="height_cm" class="form-label">Height (cm)</label>
                                        <input id="height_cm" type="number" step="0.01" class="form-control @error('height_cm') is-invalid @enderror" name="height_cm" value="{{ old('height_cm') }}">
                                        @error('height_cm')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="weight_kg" class="form-label">Current Weight (kg)</label>
                                        <input id="weight_kg" type="number" step="0.01" class="form-control @error('weight_kg') is-invalid @enderror" name="weight_kg" value="{{ old('weight_kg') }}">
                                        @error('weight_kg')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="target_weight_kg" class="form-label">Target Weight (kg)</label>
                                        <input id="target_weight_kg" type="number" step="0.01" class="form-control @error('target_weight_kg') is-invalid @enderror" name="target_weight_kg" value="{{ old('target_weight_kg') }}">
                                        @error('target_weight_kg')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Health Information Section -->
                        <div class="form-section">
                            <h5 class="section-title">Health Information</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="health_conditions" class="form-label">Health Conditions</label>
                                        <textarea id="health_conditions" class="form-control @error('health_conditions') is-invalid @enderror" name="health_conditions" rows="3" placeholder="Any medical conditions...">{{ old('health_conditions') }}</textarea>
                                        @error('health_conditions')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="injuries" class="form-label">Current or Previous Injuries</label>
                                        <textarea id="injuries" class="form-control @error('injuries') is-invalid @enderror" name="injuries" rows="3" placeholder="Any injuries to consider...">{{ old('injuries') }}</textarea>
                                        @error('injuries')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="allergies" class="form-label">Food Allergies</label>
                                        <textarea id="allergies" class="form-control @error('allergies') is-invalid @enderror" name="allergies" rows="3" placeholder="Any food allergies...">{{ old('allergies') }}</textarea>
                                        @error('allergies')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fitness Profile Section -->
                        <div class="form-section">
                            <h5 class="section-title">Fitness Profile</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="goal" class="form-label">Primary Goal</label>
                                        <select id="goal" class="form-select @error('goal') is-invalid @enderror" name="goal" required>
                                            <option value="">Select goal</option>
                                            <option value="Lose Weight" {{ old('goal') == 'Lose Weight' ? 'selected' : '' }}>Lose Weight</option>
                                            <option value="Gain Weight" {{ old('goal') == 'Gain Weight' ? 'selected' : '' }}>Gain Weight</option>
                                            <option value="Build Muscle" {{ old('goal') == 'Build Muscle' ? 'selected' : '' }}>Build Muscle</option>
                                            <option value="Improve Fitness" {{ old('goal') == 'Improve Fitness' ? 'selected' : '' }}>Improve Fitness</option>
                                            <option value="Maintain Weight" {{ old('goal') == 'Maintain Weight' ? 'selected' : '' }}>Maintain Weight</option>
                                        </select>
                                        @error('goal')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="fitness_level" class="form-label">Current Fitness Level</label>
                                        <select id="fitness_level" class="form-select @error('fitness_level') is-invalid @enderror" name="fitness_level">
                                            <option value="">Select fitness level</option>
                                            <option value="beginner" {{ old('fitness_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                            <option value="intermediate" {{ old('fitness_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                            <option value="advanced" {{ old('fitness_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                        </select>
                                        @error('fitness_level')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="training_days_per_week" class="form-label">Training Days Per Week</label>
                                        <select id="training_days_per_week" class="form-select @error('training_days_per_week') is-invalid @enderror" name="training_days_per_week">
                                            <option value="">Select days</option>
                                            @for ($i = 1; $i <= 7; $i++)
                                                <option value="{{ $i }}" {{ old('training_days_per_week') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                        </select>
                                        @error('training_days_per_week')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nutrition Information Section -->
                        <div class="form-section">
                            <h5 class="section-title">Nutrition Information</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="diet_preference" class="form-label">Diet Preference</label>
                                        <select id="diet_preference" class="form-select @error('diet_preference') is-invalid @enderror" name="diet_preference">
                                            <option value="">Select diet preference</option>
                                            <option value="no_restriction" {{ old('diet_preference') == 'no_restriction' ? 'selected' : '' }}>No Restriction</option>
                                            <option value="vegetarian" {{ old('diet_preference') == 'vegetarian' ? 'selected' : '' }}>Vegetarian</option>
                                            <option value="vegan" {{ old('diet_preference') == 'vegan' ? 'selected' : '' }}>Vegan</option>
                                            <option value="keto" {{ old('diet_preference') == 'keto' ? 'selected' : '' }}>Keto</option>
                                            <option value="paleo" {{ old('diet_preference') == 'paleo' ? 'selected' : '' }}>Paleo</option>
                                            <option value="mediterranean" {{ old('diet_preference') == 'mediterranean' ? 'selected' : '' }}>Mediterranean</option>
                                            <option value="other" {{ old('diet_preference') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('diet_preference')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="meals_per_day" class="form-label">Meals Per Day</label>
                                        <select id="meals_per_day" class="form-select @error('meals_per_day') is-invalid @enderror" name="meals_per_day">
                                            <option value="">Select meals per day</option>
                                            @for ($i = 2; $i <= 6; $i++)
                                                <option value="{{ $i }}" {{ old('meals_per_day') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                        </select>
                                        @error('meals_per_day')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="food_preferences" class="form-label">Food Preferences</label>
                                        <textarea id="food_preferences" class="form-control @error('food_preferences') is-invalid @enderror" name="food_preferences" rows="3" placeholder="Foods you like/dislike...">{{ old('food_preferences') }}</textarea>
                                        @error('food_preferences')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Workout Preferences Section -->
                        <div class="form-section">
                            <h5 class="section-title">Workout Preferences</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="preferred_training_time" class="form-label">Preferred Training Time</label>
                                        <select id="preferred_training_time" class="form-select @error('preferred_training_time') is-invalid @enderror" name="preferred_training_time">
                                            <option value="">Select preferred time</option>
                                            <option value="Morning" {{ old('preferred_training_time') == 'Morning' ? 'selected' : '' }}>Morning</option>
                                            <option value="Afternoon" {{ old('preferred_training_time') == 'Afternoon' ? 'selected' : '' }}>Afternoon</option>
                                            <option value="Evening" {{ old('preferred_training_time') == 'Evening' ? 'selected' : '' }}>Evening</option>
                                        </select>
                                        @error('preferred_training_time')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="preferred_workout_duration" class="form-label">Preferred Workout Duration</label>
                                        <select id="preferred_workout_duration" class="form-select @error('preferred_workout_duration') is-invalid @enderror" name="preferred_workout_duration">
                                            <option value="">Select duration</option>
                                            <option value="30" {{ old('preferred_workout_duration') == '30' ? 'selected' : '' }}>30 minutes</option>
                                            <option value="45" {{ old('preferred_workout_duration') == '45' ? 'selected' : '' }}>45 minutes</option>
                                            <option value="60" {{ old('preferred_workout_duration') == '60' ? 'selected' : '' }}>60 minutes</option>
                                            <option value="90" {{ old('preferred_workout_duration') == '90' ? 'selected' : '' }}>90 minutes</option>
                                            <option value="120" {{ old('preferred_workout_duration') == '120' ? 'selected' : '' }}>120 minutes</option>
                                        </select>
                                        @error('preferred_workout_duration')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="exercise_preferences" class="form-label">Exercise Preferences</label>
                                        <textarea id="exercise_preferences" class="form-control @error('exercise_preferences') is-invalid @enderror" name="exercise_preferences" rows="3" placeholder="Preferred exercises...">{{ old('exercise_preferences') }}</textarea>
                                        @error('exercise_preferences')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2" onclick="event.preventDefault(); document.getElementById('skip-form').submit();">
                                Skip for Now
                            </a>
                            <button type="submit" class="btn btn-danger px-5 py-2">
                                Save Profile
                            </button>
                        </div>
                    </form>

                    <form id="skip-form" action="{{ route('profile.skip') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection