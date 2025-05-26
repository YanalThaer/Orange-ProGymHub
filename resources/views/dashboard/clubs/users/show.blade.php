@extends('layouts.dashboard')
@section('title', 'Club - User Details')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>User Details: {{ $user->name }}</span>
                    <div>                        <a href="{{ route('club.users.edit', $user->getEncodedId()) }}" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit User
                        </a>
                        <a href="{{ route('club.users') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>

                <div class="card-body bg-dark text-white">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <!-- User Status -->
                        <div class="col-12 mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3>{{ $user->name }}</h3>
                                <div>
                                    @if($activeSubscription)
                                        <span class="badge bg-success fs-6">Active Member</span>
                                    @else
                                        <span class="badge bg-warning text-dark fs-6">Inactive Member</span>
                                    @endif
                                    
                                    @if($user->deleted_at)
                                        <span class="badge bg-danger fs-6">Deleted</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                                </div>
                                <div class="card-body bg-dark text-white">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="35%" class="text-white">Full Name:</th>
                                            <td class="text-white">{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Email:</th>
                                            <td class="text-white">{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Phone Number:</th>
                                            <td class="text-white">{{ $user->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Gender:</th>
                                            <td class="text-white">{{ ucfirst($user->gender ?? 'Not specified') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Date of Birth:</th>
                                            <td class="text-white">
                                                @if($user->date_of_birth)
                                                    {{ is_object($user->date_of_birth) ? $user->date_of_birth->format('Y-m-d') : $user->date_of_birth }}
                                                    ({{ \Carbon\Carbon::parse($user->date_of_birth)->age }} years)
                                                @else
                                                    Not specified
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Join Date:</th>
                                            <td class="text-white">
                                                {{ is_object($user->join_date) ? $user->join_date->format('Y-m-d') : \Carbon\Carbon::parse($user->join_date)->format('Y-m-d') }}
                                                ({{ \Carbon\Carbon::parse($user->join_date)->diffForHumans() }})
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Physical Stats -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Physical Information</h5>
                                </div>
                                <div class="card-body bg-dark text-white">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="text-white" width="35%">Height:</th>
                                            <td class="text-white">{{ $user->height_cm ? $user->height_cm . ' cm' : 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Weight:</th>
                                            <td class="text-white">{{ $user->weight_kg ? $user->weight_kg . ' kg' : 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">BMI:</th>
                                            <td class="text-white">
                                                @if($user->bmi)
                                                    {{ number_format($user->bmi, 2) }}
                                                    @php
                                                        $bmiCategory = '';
                                                        if ($user->bmi < 18.5) {
                                                            $bmiCategory = 'Underweight';
                                                            $badgeColor = 'warning';
                                                        } elseif ($user->bmi >= 18.5 && $user->bmi < 25) {
                                                            $bmiCategory = 'Normal weight';
                                                            $badgeColor = 'success';
                                                        } elseif ($user->bmi >= 25 && $user->bmi < 30) {
                                                            $bmiCategory = 'Overweight';
                                                            $badgeColor = 'warning';
                                                        } else {
                                                            $bmiCategory = 'Obese';
                                                            $badgeColor = 'danger';
                                                        }
                                                    @endphp
                                                    <span class="badge bg-{{ $badgeColor }}">{{ $bmiCategory }}</span>
                                                @else
                                                    Not specified
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Body Fat:</th>
                                            <td class="text-white">{{ $user->body_fat_percentage ? $user->body_fat_percentage . '%' : 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Fitness Level:</th>
                                            <td class="text-white">
                                                @if($user->fitness_level)
                                                    <span class="badge bg-{{ $user->fitness_level == 'beginner' ? 'primary' : ($user->fitness_level == 'intermediate' ? 'info' : 'success') }}">
                                                        {{ ucfirst($user->fitness_level) }}
                                                    </span>
                                                @else
                                                    Not specified
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Goal:</th>
                                            <td class="text-white">{{ $user->goal ?? 'Not specified' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Health Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Health Information</h5>
                                </div>
                                <div class="card-body bg-dark text-white">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="text-white" width="35%">Health Conditions:</th>
                                            <td class="text-white">{{ $user->health_conditions ?? 'None reported' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Injuries:</th>
                                            <td class="text-white">{{ $user->injuries ?? 'None reported' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Allergies:</th>
                                            <td class="text-white">{{ $user->allergies ?? 'None reported' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Medications:</th>
                                            <td class="text-white">{{ $user->medications ?? 'None reported' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Training Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-dumbbell me-2"></i>Training Information</h5>
                                </div>
                                <div class="card-body bg-dark text-white">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="text-white" width="35%">Days Per Week:</th>
                                            <td class="text-white">{{ $user->training_days_per_week ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Preferred Time:</th>
                                            <td class="text-white">{{ ucfirst($user->preferred_training_time ?? 'Not specified') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Workout Duration:</th>
                                            <td class="text-white">{{ $user->preferred_workout_duration ? $user->preferred_workout_duration . ' minutes' : 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Exercise Preferences:</th>
                                            <td class="text-white">{{ $user->exercise_preferences ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Exercise Dislikes:</th>
                                            <td class="text-white">{{ $user->exercise_dislikes ?? 'None reported' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nutrition Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="fas fa-utensils me-2"></i>Nutrition Information</h5>
                                </div>
                                <div class="card-body bg-dark text-white">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="text-white" width="35%">Diet Preference:</th>
                                            <td class="text-white">
                                                @php
                                                    $dietText = 'Not specified';
                                                    if ($user->diet_preference) {
                                                        switch($user->diet_preference) {
                                                            case 'no_restriction':
                                                                $dietText = 'No restrictions';
                                                                break;
                                                            case 'vegetarian':
                                                                $dietText = 'Vegetarian';
                                                                break;
                                                            case 'vegan':
                                                                $dietText = 'Vegan';
                                                                break;
                                                            case 'keto':
                                                                $dietText = 'Ketogenic';
                                                                break;
                                                            case 'paleo':
                                                                $dietText = 'Paleo';
                                                                break;
                                                            case 'mediterranean':
                                                                $dietText = 'Mediterranean';
                                                                break;
                                                            default:
                                                                $dietText = ucfirst($user->diet_preference);
                                                        }
                                                    }
                                                @endphp
                                                {{ $dietText }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Meals Per Day:</th>
                                            <td class="text-white">{{ $user->meals_per_day ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Food Preferences:</th>
                                            <td class="text-white">{{ $user->food_preferences ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-white">Food Dislikes:</th>
                                            <td class="text-white">{{ $user->food_dislikes ?? 'None reported' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subscription Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Subscription Information</h5>
                                </div>
                                <div class="card-body bg-dark text-white">
                                    @if($activeSubscription)
                                        <div class="alert alert-success">
                                            <strong>Active Subscription:</strong> {{ $activeSubscription->plan->name }}
                                            <div class="mt-2">
                                                <small>
                                                    <strong>Start Date:</strong> {{ is_object($activeSubscription->start_date) ? $activeSubscription->start_date->format('Y-m-d') : \Carbon\Carbon::parse($activeSubscription->start_date)->format('Y-m-d') }}<br>
                                                    <strong>End Date:</strong> {{ is_object($activeSubscription->end_date) ? $activeSubscription->end_date->format('Y-m-d') : \Carbon\Carbon::parse($activeSubscription->end_date)->format('Y-m-d') }} 
                                                    ({{ \Carbon\Carbon::parse($activeSubscription->end_date)->diffForHumans() }})<br>
                                                    <strong>Payment Status:</strong> {{ ucfirst($activeSubscription->payment_status) }}<br>
                                                    <strong>Payment Method:</strong> {{ ucfirst($activeSubscription->payment_method) }}
                                                </small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <strong>No Active Subscription</strong>
                                        </div>
                                    @endif
                                    
                                    <h6 class="mt-4">Subscription History</h6>
                                    @if($userSubscriptions->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-white">Plan</th>
                                                        <th class="text-white">Start Date</th>
                                                        <th class="text-white">End Date</th>
                                                        <th class="text-white">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($userSubscriptions as $subscription)
                                                        <tr>
                                                            <td class="text-white">{{ $subscription->plan->name }}</td>
                                                            <td class="text-white">{{ is_object($subscription->start_date) ? $subscription->start_date->format('Y-m-d') : \Carbon\Carbon::parse($subscription->start_date)->format('Y-m-d') }}</td>
                                                            <td class="text-white">{{ is_object($subscription->end_date) ? $subscription->end_date->format('Y-m-d') : \Carbon\Carbon::parse($subscription->end_date)->format('Y-m-d') }}</td>
                                                            <td class="text-white">
                                                                @if($subscription->end_date >= now())
                                                                    <span class="badge bg-success">Active</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Expired</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-white">No subscription history found.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('club.users') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                        <div>                            <a href="{{ route('club.users.edit', $user->getEncodedId()) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit User
                            </a>
                            @if($user->deleted_at)
                                <form action="{{ route('club.users.restore', $user->getEncodedId()) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-trash-restore"></i> Restore User
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-danger" 
                                        onclick="confirmUserDelete('{{ $user->getEncodedId() }}', '{{ $user->name }}')">
                                    <i class="fas fa-trash"></i> Delete User
                                </button>                                <form id="delete-user-form-{{ str_replace(['/', '+', '='], '', $user->getEncodedId()) }}" 
                                      action="{{ route('club.users.delete', $user->getEncodedId()) }}" 
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmUserDelete(userId, userName) {
        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete user <strong>${userName}</strong>.<br>This action can be reversed later.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-user-form-${userId.replace(/[\/\+\=]/g, '')}`).submit();
            }
        });
    }
</script>
@endsection
