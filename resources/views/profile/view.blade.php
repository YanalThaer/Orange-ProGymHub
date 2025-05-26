@extends('layouts.public')
@section('title', 'My Profile | ProGymHub')
@section('content')
<style>
    body {
        background-color: #121212;
        color: #ffffff;
    }

    .profile-container {
        max-width: 1000px;
        margin: auto;
        padding: 2rem;
    }

    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #ff0000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin-right: 1.5rem;
    }

    .profile-name {
        font-size: 2rem;
        font-weight: bold;
    }

    .card {
        background-color: #1f1f1f;
        border: none;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }

    .card-header {
        background-color: #ff0000;
        color: white;
        font-weight: bold;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .edit-button {
        background-color: #ff0000;
        border-color: #ff0000;
    }
    
    .edit-button:hover {
        background-color: #d60000;
        border-color: #d60000;
    }
    
    .info-item {
        display: flex;
        margin-bottom: 0.5rem;
    }
    
    .info-label {
        font-weight: bold;
        width: 150px;
        color: #aaa;
    }
    
    .info-value {
        flex-grow: 1;
    }
    
    .status-badge {
        background-color: #28a745;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
    }
    
    .status-badge.inactive {
        background-color: #dc3545;
    }
</style>

<div class="container profile-container">
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="profile-header">
        <div class="profile-avatar">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>        <div>
            <div class="profile-name">{{ $user->name }}</div>
            <div>Member since 
                @if ($user->join_date)
                    @if ($user->join_date instanceof \DateTime || $user->join_date instanceof \Carbon\Carbon)
                        {{ $user->join_date->format('F Y') }}
                    @else
                        {{ $user->join_date }}
                    @endif
                @else
                    N/A
                @endif
            </div>
            <div class="mt-2">
                <a href="{{ route('profile.edit') }}" class="btn edit-button text-white">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Personal Information -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    Personal Information
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone:</div>
                        <div class="info-value">{{ $user->phone_number ?? 'Not provided' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date of Birth:</div>
                        <div class="info-value">
                            @if ($user->date_of_birth)
                                @if ($user->date_of_birth instanceof \DateTime || $user->date_of_birth instanceof \Carbon\Carbon)
                                    {{ $user->date_of_birth->format('F d, Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($user->date_of_birth)->format('F d, Y') }}
                                @endif
                            @else
                                Not provided
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gender:</div>
                        <div class="info-value">{{ $user->gender ? ucfirst($user->gender) : 'Not provided' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Health Information -->
            <div class="card mb-4">
                <div class="card-header">
                    Health Information
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-label">Health Conditions:</div>
                        <div class="info-value">{{ $user->health_conditions ?? 'None' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Injuries:</div>
                        <div class="info-value">{{ $user->injuries ?? 'None' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Allergies:</div>
                        <div class="info-value">{{ $user->allergies ?? 'None' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Medications:</div>
                        <div class="info-value">{{ $user->medications ?? 'None' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Fitness Preferences -->
            <div class="card">
                <div class="card-header">
                    Fitness Preferences
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-label">Goal:</div>
                        <div class="info-value">{{ $user->goal ?? 'Not specified' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Fitness Level:</div>
                        <div class="info-value">{{ $user->fitness_level ?? 'Not specified' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Days per Week:</div>
                        <div class="info-value">{{ $user->training_days_per_week ?? 'Not specified' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Preferred Time:</div>
                        <div class="info-value">{{ $user->preferred_training_time ?? 'Not specified' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Workout Duration:</div>
                        <div class="info-value">{{ $user->preferred_workout_duration ?? 'Not specified' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Exercise Likes:</div>
                        <div class="info-value">{{ $user->exercise_preferences ?? 'Not specified' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Exercise Dislikes:</div>
                        <div class="info-value">{{ $user->exercise_dislikes ?? 'Not specified' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right column -->
        <div class="col-md-6">
            <!-- Physical Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    Physical Stats
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-label">Height:</div>
                        <div class="info-value">{{ $user->height_cm ? $user->height_cm . ' cm' : 'Not provided' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Current Weight:</div>
                        <div class="info-value">{{ $user->weight_kg ? $user->weight_kg . ' kg' : 'Not provided' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Target Weight:</div>
                        <div class="info-value">{{ $user->target_weight_kg ? $user->target_weight_kg . ' kg' : 'Not provided' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">BMI:</div>
                        <div class="info-value">{{ $user->bmi ?? 'Not calculated' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Body Fat %:</div>
                        <div class="info-value">{{ $user->body_fat_percentage ? $user->body_fat_percentage . '%' : 'Not provided' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Diet Preferences -->
            <div class="card mb-4">
                <div class="card-header">
                    Diet Preferences
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-label">Diet Type:</div>
                        <div class="info-value">{{ $user->diet_preference ?? 'Not specified' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Meals per Day:</div>
                        <div class="info-value">{{ $user->meals_per_day ?? 'Not specified' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Food Likes:</div>
                        <div class="info-value">{{ $user->food_preferences ?? 'Not specified' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Food Dislikes:</div>
                        <div class="info-value">{{ $user->food_dislikes ?? 'Not specified' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Membership Status -->
            <div class="card mb-4">
                <div class="card-header">
                    Membership Status
                </div>
                <div class="card-body">
                    @php
                        $activeSubscription = $user->getActiveSubscription();
                    @endphp
                    
                    @if($activeSubscription)
                        <div class="info-item">
                            <div class="info-label">Status:</div>
                            <div class="info-value">
                                <span class="status-badge">Active</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Club:</div>
                            <div class="info-value">{{ $activeSubscription->club->name ?? 'Unknown' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Plan:</div>
                            <div class="info-value">{{ $activeSubscription->plan->name ?? 'Standard Plan' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Start Date:</div>
                            <div class="info-value">
                                @if ($activeSubscription->start_date instanceof \DateTime || $activeSubscription->start_date instanceof \Carbon\Carbon)
                                    {{ $activeSubscription->start_date->format('F d, Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($activeSubscription->start_date)->format('F d, Y') }}
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">End Date:</div>
                            <div class="info-value">
                                @if ($activeSubscription->end_date instanceof \DateTime || $activeSubscription->end_date instanceof \Carbon\Carbon)
                                    {{ $activeSubscription->end_date->format('F d, Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($activeSubscription->end_date)->format('F d, Y') }}
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Days Remaining:</div>
                            <div class="info-value">{{ now()->diffInDays($activeSubscription->end_date, false) }}</div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <span class="status-badge inactive">No active subscription</span>
                            <p class="mt-3 mb-0">You currently don't have an active membership.</p>
                            <a href="{{ route('all_clubs') }}" class="btn btn-danger mt-3">Browse Clubs</a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Coach Information (if assigned) -->
            @if($user->coach)
            <div class="card">
                <div class="card-header">
                    Your Coach
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-label">Name:</div>
                        <div class="info-value">{{ $user->coach->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Specialization:</div>
                        <div class="info-value">{{ $user->coach->specialization ?? 'General Fitness' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Contact:</div>
                        <div class="info-value">{{ $user->coach->email }}</div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('profile', $user->coach->getEncodedId()) }}" class="btn btn-outline-danger">View Coach Profile</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
