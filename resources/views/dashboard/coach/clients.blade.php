@extends('layouts.dashboard')
@section('title')
ProGymHub | My Clients
@endsection
@section('content')
<!-- Page Header -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-secondary rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0">My Clients</h5>
                    <div>
                        @if($hasClub)
                        <a href="{{ route('coach.clients') }}" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-sync"></i> Refresh
                        </a>
                        @endif
                    </div>
                </div>
                
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <!-- Nav tabs for client management -->
                <ul class="nav nav-tabs mb-4" id="clientTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="my-clients-tab" data-bs-toggle="tab" data-bs-target="#my-clients" type="button" role="tab" aria-controls="my-clients" aria-selected="true">
                            <i class="fa fa-user-check me-2"></i>My Clients ({{ $clients->count() }})
                        </button>
                    </li>
                    @if($hasClub)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="club-users-tab" data-bs-toggle="tab" data-bs-target="#club-users" type="button" role="tab" aria-controls="club-users" aria-selected="false">
                            <i class="fa fa-building me-2"></i>Club Members ({{ $clubUsers->count() }})
                        </button>
                    </li>
                    @else
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="all-users-tab" data-bs-toggle="tab" data-bs-target="#all-users" type="button" role="tab" aria-controls="all-users" aria-selected="false">
                            <i class="fa fa-users me-2"></i>Available Users ({{ $allUsers->count() }})
                        </button>
                    </li>
                    @endif
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="my-clients" role="tabpanel" aria-labelledby="my-clients-tab">
                        @if($clients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Fitness Level</th>
                                        <th>Goal</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                    <tr>
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->phone_number ?? 'N/A' }}</td>
                                        <td>
                                            @if($client->fitness_level)
                                                <span class="badge bg-{{ $client->fitness_level == 'beginner' ? 'primary' : ($client->fitness_level == 'intermediate' ? 'info' : 'success') }}">
                                                    {{ ucfirst($client->fitness_level) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Not specified</span>
                                            @endif
                                        </td>
                                        <td>{{ $client->goal ?? 'Not specified' }}</td>
                                        <td>
                                            @if($client->end_date && \Carbon\Carbon::parse($client->end_date)->gte(now()))
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#clientModal{{ $client->id }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Client Detail Modals -->
                        @foreach($clients as $client)
                        <div class="modal fade" id="clientModal{{ $client->id }}" tabindex="-1" aria-labelledby="clientModalLabel{{ $client->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content bg-dark text-white">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title" id="clientModalLabel{{ $client->id }}">Client Details</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4 text-center mb-3">
                                                <h5>{{ $client->name }}</h5>
                                                <p class="text-muted">{{ $client->email }}</p>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <h6>Personal Information</h6>
                                                        <div class="mb-2"><strong>Phone:</strong> {{ $client->phone_number ?? 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Gender:</strong> {{ ucfirst($client->gender ?? 'Not specified') }}</div>
                                                        <div class="mb-2"><strong>Date of Birth:</strong> {{ $client->date_of_birth ? $client->date_of_birth->format('M d, Y') : 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Join Date:</strong> {{ $client->join_date ? $client->join_date->format('M d, Y') : 'Not specified' }}</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <h6>Fitness Details</h6>
                                                        <div class="mb-2"><strong>Goal:</strong> {{ $client->goal ?? 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Fitness Level:</strong> {{ ucfirst($client->fitness_level ?? 'Not specified') }}</div>
                                                        <div class="mb-2"><strong>Height:</strong> {{ $client->height_cm ? $client->height_cm . ' cm' : 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Weight:</strong> {{ $client->weight_kg ? $client->weight_kg . ' kg' : 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Target Weight:</strong> {{ $client->target_weight_kg ? $client->target_weight_kg . ' kg' : 'Not specified' }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <h6>Training Preferences</h6>
                                                        <div class="mb-2"><strong>Training Days:</strong> {{ $client->training_days_per_week ?? 'Not specified' }} days/week</div>
                                                        <div class="mb-2"><strong>Preferred Time:</strong> {{ $client->preferred_training_time ?? 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Workout Duration:</strong> {{ $client->preferred_workout_duration ?? 'Not specified' }}</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <h6>Health Information</h6>
                                                        <div class="mb-2"><strong>BMI:</strong> {{ $client->bmi ?? 'Not calculated' }}</div>
                                                        <div class="mb-2"><strong>Body Fat:</strong> {{ $client->body_fat_percentage ? $client->body_fat_percentage . '%' : 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Health Conditions:</strong> {{ $client->health_conditions ?? 'None reported' }}</div>
                                                        <div class="mb-2"><strong>Injuries:</strong> {{ $client->injuries ?? 'None reported' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <h6>Active Workout Plans</h6>
                                            @if(isset($clientWorkoutPlans[$client->id]) && $clientWorkoutPlans[$client->id]->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-dark">
                                                        <thead>
                                                            <tr>
                                                                <th>Plan Name</th>
                                                                <th>Category</th>
                                                                <th>Duration</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($clientWorkoutPlans[$client->id] as $plan)
                                                            <tr>
                                                                <td>{{ $plan->name }}</td>
                                                                <td>{{ $plan->category }}</td>
                                                                <td>{{ $plan->duration_weeks }} weeks</td>
                                                                <td><span class="badge bg-success">Active</span></td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-muted">No workout plans assigned yet.</p>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-4">
                                            <h6>Progress Tracking</h6>
                                            @if(isset($clientProgress[$client->id]))
                                                <div class="mb-3">
                                                    <label>Overall Progress:</label>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65%</div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Latest Metrics ({{ $clientProgress[$client->id]->created_at->format('M d, Y') }}):</label>
                                                    <div class="row mt-2">
                                                        <div class="col-md-4 mb-2">
                                                            <div class="bg-secondary rounded p-2">
                                                                <div class="d-flex justify-content-between">
                                                                    <span>Weight:</span>
                                                                    <span>{{ $clientProgress[$client->id]->weight_kg }} kg</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <div class="bg-secondary rounded p-2">
                                                                <div class="d-flex justify-content-between">
                                                                    <span>Body Fat:</span>
                                                                    <span>{{ $clientProgress[$client->id]->body_fat_percentage }}%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <div class="bg-secondary rounded p-2">
                                                                <div class="d-flex justify-content-between">
                                                                    <span>BMI:</span>
                                                                    <span>{{ $clientProgress[$client->id]->bmi }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <p class="text-muted">No progress data available yet.</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer border-secondary">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-center py-5">
                            <i class="fa fa-users fa-4x text-muted mb-3"></i>
                            <h5>No Clients Assigned</h5>
                            <p class="text-muted">You don't have any clients assigned to you yet.</p>
                        </div>
                        @endif
                    </div><!-- End my-clients tab -->
                    
                    @if($hasClub)
                    <!-- Club Users Tab -->
                    <div class="tab-pane fade" id="club-users" role="tabpanel" aria-labelledby="club-users-tab">
                        @if($clubUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Fitness Level</th>
                                        <th>Goal</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clubUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                        <td>
                                            @if($user->fitness_level)
                                                <span class="badge bg-{{ $user->fitness_level == 'beginner' ? 'primary' : ($user->fitness_level == 'intermediate' ? 'info' : 'success') }}">
                                                    {{ ucfirst($user->fitness_level) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Not specified</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->goal ?? 'Not specified' }}</td>
                                        <td>
                                            @if($user->end_date && \Carbon\Carbon::parse($user->end_date)->gte(now()))
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                @if(!$user->coach_id)
                                                <form action="{{ route('coach.clients.assign') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-user-plus"></i> Assign
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Club User Modals -->
                        @foreach($clubUsers as $user)
                        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content bg-dark text-white">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title">User Details</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4 text-center mb-3">
                                                <img src="{{ asset('img/default-avatar.png') }}" alt="Avatar" class="rounded-circle mb-3" style="width: 100px; height: 100px;">
                                                <h5>{{ $user->name }}</h5>
                                                <p class="text-muted">{{ $user->email }}</p>
                                                @if($user->coach_id)
                                                    <span class="badge bg-warning">Assigned to another coach</span>
                                                @else
                                                    <span class="badge bg-info">Available</span>
                                                @endif
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <h6>Personal Information</h6>
                                                        <div class="mb-2"><strong>Phone:</strong> {{ $user->phone_number ?? 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Gender:</strong> {{ ucfirst($user->gender ?? 'Not specified') }}</div>
                                                        <div class="mb-2"><strong>Date of Birth:</strong> {{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Join Date:</strong> {{ $user->join_date ? $user->join_date->format('M d, Y') : 'Not specified' }}</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <h6>Fitness Details</h6>
                                                        <div class="mb-2"><strong>Goal:</strong> {{ $user->goal ?? 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Fitness Level:</strong> {{ ucfirst($user->fitness_level ?? 'Not specified') }}</div>
                                                        <div class="mb-2"><strong>Height:</strong> {{ $user->height_cm ? $user->height_cm . ' cm' : 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Weight:</strong> {{ $user->weight_kg ? $user->weight_kg . ' kg' : 'Not specified' }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <h6>Training Preferences</h6>
                                                        <div class="mb-2"><strong>Training Days:</strong> {{ $user->training_days_per_week ?? 'Not specified' }} days/week</div>
                                                        <div class="mb-2"><strong>Preferred Time:</strong> {{ $user->preferred_training_time ?? 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Workout Duration:</strong> {{ $user->preferred_workout_duration ?? 'Not specified' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-secondary">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                        @if(!$user->coach_id)
                                        <form action="{{ route('coach.clients.assign') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-sm btn-success">Assign to Me</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-center py-5">
                            <i class="fa fa-building fa-4x text-muted mb-3"></i>
                            <h5>No Club Members</h5>
                            <p class="text-muted">There are no members in your club that can be assigned to you.</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <!-- All Users Tab (when coach has no club) -->
                    <div class="tab-pane fade" id="all-users" role="tabpanel" aria-labelledby="all-users-tab">
                        @if($allUsers->count() > 0)
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i> You are not assigned to any club. Here are all users in the system without a coach.
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Club</th>
                                        <th>Fitness Level</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                        <td>
                                            @if($user->club_id)
                                                {{ $user->club->name ?? 'Unknown Club' }}
                                            @else
                                                <span class="text-muted">No Club</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->fitness_level)
                                                <span class="badge bg-{{ $user->fitness_level == 'beginner' ? 'primary' : ($user->fitness_level == 'intermediate' ? 'info' : 'success') }}">
                                                    {{ ucfirst($user->fitness_level) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Not specified</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->end_date && \Carbon\Carbon::parse($user->end_date)->gte(now()))
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <form action="{{ route('coach.clients.assign') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-user-plus"></i> Assign
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- All Users Modals -->
                        @foreach($allUsers as $user)
                        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content bg-dark text-white">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title">User Details</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4 text-center mb-3">
\                                                <h5>{{ $user->name }}</h5>
                                                <p class="text-muted">{{ $user->email }}</p>
                                                @if($user->club_id)
                                                    <div class="badge bg-primary mb-2">{{ $user->club->name ?? 'Unknown Club' }}</div>
                                                @else
                                                    <div class="badge bg-secondary mb-2">No Club</div>
                                                @endif
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <h6>Personal Information</h6>
                                                        <div class="mb-2"><strong>Phone:</strong> {{ $user->phone_number ?? 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Gender:</strong> {{ ucfirst($user->gender ?? 'Not specified') }}</div>
                                                        <div class="mb-2"><strong>Date of Birth:</strong> {{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Join Date:</strong> {{ $user->join_date ? $user->join_date->format('M d, Y') : 'Not specified' }}</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <h6>Fitness Details</h6>
                                                        <div class="mb-2"><strong>Goal:</strong> {{ $user->goal ?? 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Fitness Level:</strong> {{ ucfirst($user->fitness_level ?? 'Not specified') }}</div>
                                                        <div class="mb-2"><strong>Height:</strong> {{ $user->height_cm ? $user->height_cm . ' cm' : 'Not specified' }}</div>
                                                        <div class="mb-2"><strong>Weight:</strong> {{ $user->weight_kg ? $user->weight_kg . ' kg' : 'Not specified' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-secondary">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <form action="{{ route('coach.clients.assign') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-sm btn-success">Assign to Me</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-center py-5">
                            <i class="fa fa-user-slash fa-4x text-muted mb-3"></i>
                            <h5>No Available Users</h5>
                            <p class="text-muted">There are no users in the system without a coach.</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection