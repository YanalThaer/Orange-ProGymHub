@extends('layouts.dashboard')
@section('title')
@if(Auth::guard('admin')->check())
ProGymHub | Dashboard - Admin
@elseif(Auth::guard('club')->check())
ProGymHub | Dashboard - Club
@elseif(Auth::guard('coach')->check())
ProGymHub | Dashboard - Coach
@else
ProGymHub | Dashboard
@endif
@endsection
@section('content')
<!-- Global CSS styles to apply to all headings and card headers -->
<style>
    .card-header h6,
    .accordion h6,
    .card-body h6 {
        color: white !important;
        font-weight: bold !important;
        font-size: 1.1rem !important;
    }

    .text-muted {
        color: #adb5bd !important;
    }

    .card-body p strong {
        color: white !important;
    }

    table th {
        color: white !important;
        font-weight: bold !important;
    }

    .accordion-button {
        font-weight: bold;
    }
</style>
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        @if(Auth::guard('admin')->check() && isset($totalClubs))
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-dumbbell fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">Total Clubs</p>
                    <h5 class="mb-0 text-white">{{ $totalClubs }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-users fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">Total Users</p>
                    <h5 class="mb-0 text-white">{{ $totalUsers }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-user-tie fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">Total Coaches</p>
                    <h5 class="mb-0 text-white">{{ $totalCoaches }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-credit-card fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">Subscription Plans</p>
                    <h5 class="mb-0 text-white">{{ $totalSubscriptionPlans }}</h5>
                </div>
            </div>
        </div> @elseif(Auth::guard('club')->check())
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-users fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">Total Members</p>
                    <h5 class="mb-0 text-white">{{ Auth::guard('club')->user()->users->count() }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-user-tie fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">Total Coaches</p>
                    <h5 class="mb-0 text-white">{{ Auth::guard('club')->user()->coaches->count() }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-credit-card fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">Subscription Plans</p>
                    <h5 class="mb-0 text-white">{{ Auth::guard('club')->user()->subscriptionPlans->count() }}</h5>
                </div>
            </div>
        </div> @elseif(Auth::guard('coach')->check())
        <!-- Coach Dashboard -->
        @if(isset($clubInfo))
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-building fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">My Club</p>
                    <h5 class="mb-0 text-white">{{ $clubInfo->name }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-user-friends fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">Club Members</p>
                    <h5 class="mb-0 text-white">{{ $totalClubMembers }}</h5>
                </div>
            </div>
        </div>
        @endif
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-user-check fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">My Clients</p>
                    <h5 class="mb-0 text-white">{{ $coachClients }}</h5>
                </div>
            </div>
        </div> @else
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-chart-line fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-white fs-5 fw-bold">Today Sale</p>
                    <h5 class="mb-0 text-white">$1234</h5>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<!-- Sale & Revenue End -->

@if(Auth::guard('coach')->check())

@if(isset($clubInfo))
<!-- Club Information -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">My Club Information</h5>
            <a href="{{ route('coach.club') }}" class="btn btn-primary">View Club Details</a>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card bg-dark">
                    <div class="card-body text-center">
                        @if($clubInfo->logo)
                        <img src="{{ asset('storage/' . $clubInfo->logo) }}" alt="{{ $clubInfo->name }}" class="img-fluid rounded-circle mb-3" style="max-height: 150px;">
                        @else
                        <img src="{{ asset('img/default-avatar.png') }}" alt="{{ $clubInfo->name }}" class="img-fluid rounded-circle mb-3" style="max-height: 150px;">
                        @endif
                        <h5 class="text-white">{{ $clubInfo->name }}</h5>
                        <p class="text-muted mb-1">{{ $clubInfo->email }}</p>
                        <p class="text-muted">{{ $clubInfo->phone ?? 'No phone number' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card bg-dark mb-3">
                    <div class="card-body">
                        <h6 class="text-white fw-bold fs-5 mb-3">Club Details</h6>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Location:</strong> {{ $clubInfo->location ?? 'Not specified' }}</p>
                                <p><strong>Address:</strong> {{ $clubInfo->address ?? 'Not specified' }}</p>
                                <p><strong>City:</strong> {{ $clubInfo->city ?? 'Not specified' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> <span class="badge {{ $clubInfo->status == 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($clubInfo->status ?? 'Unknown') }}</span></p>
                                <p><strong>Coaches:</strong> {{ $totalClubCoaches }}</p>
                                <p><strong>Members:</strong> {{ $totalClubMembers }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-dark">
                    <div class="card-body">
                        <h6 class="text-white fw-bold fs-5 mb-3">Club Facilities</h6>
                        <div class="d-flex flex-wrap">
                            @if($clubInfo->has_parking)
                            <span class="badge bg-primary me-2 mb-2">Parking</span>
                            @endif
                            @if($clubInfo->has_wifi)
                            <span class="badge bg-primary me-2 mb-2">WiFi</span>
                            @endif
                            @if($clubInfo->has_showers)
                            <span class="badge bg-primary me-2 mb-2">Showers</span>
                            @endif
                            @if($clubInfo->has_lockers)
                            <span class="badge bg-primary me-2 mb-2">Lockers</span>
                            @endif
                            @if($clubInfo->has_pool)
                            <span class="badge bg-primary me-2 mb-2">Pool</span>
                            @endif
                            @if($clubInfo->has_sauna)
                            <span class="badge bg-primary me-2 mb-2">Sauna</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Coach Information and Stats -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">My Clients</h5>
        </div>@if(isset($myClients) && count($myClients) > 0)
        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subscription</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myClients->count() > 5 ? $myClients->random(5) : $myClients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->phone ?? 'N/A' }}</td>
                        <td>
                            @if($client->userSubscription)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">None</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($clientProgress[$client->id]))
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $clientProgress[$client->id] }}%" aria-valuenow="{{ $clientProgress[$client->id] }}" aria-valuemin="0" aria-valuemax="100">{{ $clientProgress[$client->id] }}%</div>
                            </div>
                            @else
                            <span class="text-muted">No data</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fa fa-user-friends fa-4x text-muted mb-3"></i>
            <p>You don't have any clients assigned to you yet.</p>
        </div>
        @endif
    </div>
</div>

@elseif(Auth::guard('admin')->check())
<!-- Clubs & Subscription Plans Overview -->
@if(isset($clubs) && $clubs->count() > 0)
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">Clubs & Subscription Plans Overview</h5>
            <a href="{{ route('admin.clubs') }}" class="btn btn-primary">Manage Clubs</a>
        </div>
        <div class="accordion" id="clubsAccordion">
            @foreach($clubs->count() > 5 ? $clubs->random(5) : $clubs as $club)
            <div class="accordion-item bg-dark mb-3 border-0">
                <h2 class="accordion-header" id="heading{{ $club->id }}">
                    <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $club->id }}" aria-expanded="false"
                        aria-controls="collapse{{ $club->id }}">
                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                            <span>
                                <i class="fa fa-dumbbell me-2"></i>
                                {{ $club->name }}
                            </span>
                            <span class="badge {{ $club->status == 'active' ? 'bg-success' : 'bg-danger' }} me-2">
                                {{ ucfirst($club->status ?? 'Unknown') }}
                            </span>
                        </div>
                    </button>
                </h2>
                <div id="collapse{{ $club->id }}" class="accordion-collapse collapse"
                    aria-labelledby="heading{{ $club->id }}" data-bs-parent="#clubsAccordion">
                    <div class="accordion-body bg-secondary">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card bg-dark h-100">
                                    <div class="card-header bg-secondary">
                                        <h6 class="mb-0 text-white fw-bold">Club Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Email:</strong> {{ $club->email }}</p>
                                                <p><strong>Phone:</strong> {{ $club->phone ?? 'N/A' }}</p>
                                                <p><strong>Location:</strong> {{ $club->city }}, {{ $club->country }}</p>
                                                <p><strong>Members:</strong> {{ $club->users->count() }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Coaches:</strong> {{ $club->coaches->count() }}</p>
                                                <p><strong>Status:</strong> {{ ucfirst($club->status) }}</p>
                                                <p><strong>Hours:</strong>
                                                    {{ $club->open_time ? $club->open_time->format('H:i') : 'N/A' }} -
                                                    {{ $club->close_time ? $club->close_time->format('H:i') : 'N/A' }}
                                                </p>
                                                <a href="{{ route('clubs.show', $club->getEncodedId()) }}" class="btn btn-sm btn-primary mt-2">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-dark h-100">
                                    <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-white fw-bold">Subscription Plans ({{ $club->subscriptionPlans->count() }})</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($club->subscriptionPlans->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-dark">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Type</th>
                                                        <th>Price</th>
                                                        <th>Duration</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($club->subscriptionPlans as $plan)
                                                    <tr>
                                                        <td>{{ $plan->name }}</td>
                                                        <td>{{ ucfirst($plan->type) }}</td>
                                                        <td>{{ number_format($plan->price, 2) }} JOD</td>
                                                        <td>{{ $plan->duration_days }} days</td>
                                                        <td>
                                                            <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <p class="text-center text-muted my-4">No subscription plans available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Clubs & Subscription Plans End -->
@endif

<!-- Coaches Overview -->
@if(isset($coaches) && $coaches->count() > 0)
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">Coaches Overview</h5>
            <a href="{{ route('admin.coaches') }}" class="btn btn-primary">Manage Coaches</a>
        </div>
        <div class="accordion" id="coachesAccordion">
            @foreach($coaches->count() > 5 ? $coaches->random(5) : $coaches as $coach)
            <div class="accordion-item bg-dark mb-3 border-0">
                <h2 class="accordion-header" id="headingCoach{{ $coach->id }}">
                    <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseCoach{{ $coach->id }}" aria-expanded="false"
                        aria-controls="collapseCoach{{ $coach->id }}">
                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                            <span>
                                <i class="fa fa-user-tie me-2"></i>
                                {{ $coach->name }}
                            </span>
                            <span class="badge {{ $coach->email_verified_at ? 'bg-success' : 'bg-warning' }} me-2">
                                {{ $coach->email_verified_at ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>
                    </button>
                </h2>
                <div id="collapseCoach{{ $coach->id }}" class="accordion-collapse collapse"
                    aria-labelledby="headingCoach{{ $coach->id }}" data-bs-parent="#coachesAccordion">
                    <div class="accordion-body bg-secondary">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-dark h-100">
                                    <div class="card-header bg-secondary">
                                        <h6 class="mb-0">Coach Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Email:</strong> {{ $coach->email }}</p>
                                                <p><strong>Phone:</strong> {{ $coach->phone ?? 'N/A' }}</p>
                                                <p><strong>Gender:</strong> {{ ucfirst($coach->gender ?? 'N/A') }}</p>
                                                <p><strong>Experience:</strong> {{ $coach->experience_years ? $coach->experience_years . ' years' : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Employment:</strong> {{ ucfirst($coach->employment_type ?? 'N/A') }}</p>
                                                <p><strong>Club:</strong> {{ $coach->club ? $coach->club->name : 'Not assigned' }}</p>
                                                <p><strong>Workout Plans:</strong> {{ $coach->workoutPlans ? $coach->workoutPlans->count() : 0 }}</p>
                                                <a href="{{ route('admin.coaches.show', $coach->getEncodedId()) }}" class="btn btn-sm btn-primary mt-2">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-dark h-100">
                                    <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Specializations & Certifications</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-2">Specializations</h6>
                                                @php
                                                $specializations = $coach->specializations;
                                                if (is_string($specializations)) {
                                                $specializations = json_decode($specializations, true) ?: [];
                                                }
                                                @endphp

                                                @if(is_array($specializations) && count($specializations) > 0)
                                                <ul class="list-group list-group-flush bg-transparent">
                                                    @foreach($specializations as $specialization)
                                                    <li class="list-group-item bg-dark text-white border-secondary">
                                                        <i class="fas fa-star text-warning me-2"></i> {{ $specialization }}
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                @else
                                                <p class="text-muted">No specializations listed</p>
                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <h6 class="text-success mb-2">Certifications</h6>
                                                @php
                                                $certifications = $coach->certifications;
                                                if (is_string($certifications)) {
                                                $certifications = json_decode($certifications, true) ?: [];
                                                }
                                                @endphp

                                                @if(is_array($certifications) && count($certifications) > 0)
                                                <ul class="list-group list-group-flush">
                                                    @foreach($certifications as $certification)
                                                    <li class="list-group-item bg-dark text-white border-secondary">
                                                        <i class="fas fa-certificate text-info me-2"></i> {{ $certification }}
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                @else
                                                <p class="text-muted">No certifications listed</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Coaches Overview End -->
@endif

<!-- Users Overview -->
@if(isset($users) && $users->count() > 0)
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">Recent Users</h5>
            <a href="{{ route('admin.users') }}" class="btn btn-primary">Manage Users</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Club</th>
                        <th>Coach</th>
                        <th>Membership</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users->count() > 5 ? $users->random(5) : $users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->club ? $user->club->name : 'None' }}</td>
                        <td>{{ $user->coach ? $user->coach->name : 'None' }}</td>
                        <td>
                            @if($user->userSubscription)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">None</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->getEncodedId()) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Users Overview End -->
@endif

@elseif(Auth::guard('club')->check())

<!-- Subscription Plans Overview -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">Subscription Plans</h5>
            <a href="{{ route('club.subscription-plans') }}" class="btn btn-primary">Manage Plans</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $club = Auth::guard('club')->user(); @endphp
                    @if($club->subscriptionPlans->count() > 0)
                    @foreach($club->subscriptionPlans as $plan)
                    <tr>
                        <td>{{ $plan->name }}</td>
                        <td>{{ ucfirst($plan->type) }}</td>
                        <td>{{ number_format($plan->price, 2) }} JOD</td>
                        <td>{{ $plan->duration_days }} days</td>
                        <td>
                            <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('club.subscription-plans.edit', $plan->getEncodedId()) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" class="text-center">No subscription plans available</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Subscription Plans Overview End -->

<!-- Coaches Overview -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">Coaches Overview</h5>
            <a href="{{ route('club.coaches') }}" class="btn btn-primary">Manage Coaches</a>
        </div>

        <div class="accordion" id="coachesAccordion">
            @php $club = Auth::guard('club')->user(); @endphp
            @if($club->coaches->count() > 0)
            @foreach($club->coaches->count() > 5 ? $club->coaches->random(5) : $club->coaches as $coach)
            <div class="accordion-item bg-dark mb-3 border-0">
                <h2 class="accordion-header" id="headingCoach{{ $coach->id }}">
                    <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseCoach{{ $coach->id }}" aria-expanded="false"
                        aria-controls="collapseCoach{{ $coach->id }}">
                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                            <span>
                                <i class="fa fa-user-tie me-2"></i>
                                {{ $coach->name }}
                            </span>
                            <span class="badge {{ $coach->email_verified_at ? 'bg-success' : 'bg-warning' }} me-2">
                                {{ $coach->email_verified_at ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>
                    </button>
                </h2>
                <div id="collapseCoach{{ $coach->id }}" class="accordion-collapse collapse"
                    aria-labelledby="headingCoach{{ $coach->id }}" data-bs-parent="#coachesAccordion">
                    <div class="accordion-body bg-secondary">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-dark h-100">
                                    <div class="card-header bg-secondary">
                                        <h6 class="mb-0">Coach Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Email:</strong> {{ $coach->email }}</p>
                                                <p><strong>Phone:</strong> {{ $coach->phone ?? 'N/A' }}</p>
                                                <p><strong>Gender:</strong> {{ ucfirst($coach->gender ?? 'N/A') }}</p>
                                                <p><strong>Experience:</strong> {{ $coach->experience_years ? $coach->experience_years . ' years' : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Employment:</strong> {{ ucfirst($coach->employment_type ?? 'N/A') }}</p>
                                                <p><strong>Workout Plans:</strong> {{ $coach->workoutPlans ? $coach->workoutPlans->count() : 0 }}</p>
                                                <a href="{{ route('club.coaches.show', $coach->getEncodedId()) }}" class="btn btn-sm btn-primary mt-2">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-dark h-100">
                                    <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Specializations & Certifications</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-2">Specializations</h6>
                                                @php
                                                $specializations = $coach->specializations;
                                                if (is_string($specializations)) {
                                                $specializations = json_decode($specializations, true) ?: [];
                                                }
                                                @endphp

                                                @if(is_array($specializations) && count($specializations) > 0)
                                                <ul class="list-group list-group-flush bg-transparent">
                                                    @foreach($specializations as $specialization)
                                                    <li class="list-group-item bg-dark text-white border-secondary">
                                                        <i class="fas fa-star text-warning me-2"></i> {{ $specialization }}
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                @else
                                                <p class="text-muted">No specializations listed</p>
                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <h6 class="text-success mb-2">Certifications</h6>
                                                @php
                                                $certifications = $coach->certifications;
                                                if (is_string($certifications)) {
                                                $certifications = json_decode($certifications, true) ?: [];
                                                }
                                                @endphp

                                                @if(is_array($certifications) && count($certifications) > 0)
                                                <ul class="list-group list-group-flush">
                                                    @foreach($certifications as $certification)
                                                    <li class="list-group-item bg-dark text-white border-secondary">
                                                        <i class="fas fa-certificate text-info me-2"></i> {{ $certification }}
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                @else
                                                <p class="text-muted">No certifications listed</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="alert alert-info">
                No coaches are currently registered with your club.
            </div>
            @endif
        </div>
    </div>
</div>
<!-- Coaches Overview End -->

<!-- Users/Members Overview -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">Members Overview</h5>
            <a href="{{ route('club.users') }}" class="btn btn-primary">Manage Members</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Coach</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $club = Auth::guard('club')->user(); @endphp
                    @if($club->users->count() > 0)
                    @foreach($club->users->count() > 5 ? $club->users->random(5) : $club->users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->coach ? $user->coach->name : 'None' }}</td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('club.users.show', $user->getEncodedId()) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" class="text-center">No members registered yet</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Users/Members Overview End -->

<!-- Coach-Member Assignments Overview -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0 text-white fw-bold">Coach-Member Assignments</h5>
        </div>
        @php $club = Auth::guard('club')->user(); @endphp
        @if($club->coaches->count() > 0)
        <div class="accordion" id="coachMembersAccordion">
            @foreach($club->coaches->count() > 5 ? $club->coaches->random(5) : $club->coaches as $coach)
            <div class="accordion-item bg-dark mb-3 border-0">
                <h2 class="accordion-header" id="headingCoachMembers{{ $coach->id }}">
                    <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseCoachMembers{{ $coach->id }}" aria-expanded="false"
                        aria-controls="collapseCoachMembers{{ $coach->id }}">
                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                            <span>
                                <i class="fa fa-user-tie me-2"></i>
                                {{ $coach->name }}
                            </span>
                            <span class="badge bg-primary me-2">
                                {{ $coach->users->count() }} Members
                            </span>
                        </div>
                    </button>
                </h2>
                <div id="collapseCoachMembers{{ $coach->id }}" class="accordion-collapse collapse"
                    aria-labelledby="headingCoachMembers{{ $coach->id }}" data-bs-parent="#coachMembersAccordion">
                    <div class="accordion-body bg-secondary"> @php
                        // Filter users who belong to the same club as the coach
                        $usersInSameClub = $coach->users->filter(function($user) use ($coach) {
                        return $user->club_id == $coach->club_id;
                        });
                        @endphp

                        @if($usersInSameClub->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-dark">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Membership</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usersInSameClub->count() > 5 ? $usersInSameClub->random(5) : $usersInSameClub as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->userSubscription)
                                            <span class="badge bg-success">Active</span>
                                            @else
                                            <span class="badge bg-secondary">None</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('club.users.show', $user->getEncodedId()) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info">
                            This coach currently has no assigned members.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info">
            No coaches are currently registered with your club.
        </div>
        @endif
    </div>
</div>
<!-- Coach-Member Assignments Overview End -->

@else

<!-- Widgets Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-md-6 col-xl-4">
            <div class="h-100 bg-secondary rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="mb-0">Messages</h6>
                    <a href="">Show All</a>
                </div>
                <div class="d-flex align-items-center border-bottom py-3">
                    <img class="rounded-circle flex-shrink-0" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Jhon Doe</h6>
                            <small>15 minutes ago</small>
                        </div>
                        <span>Short message goes here...</span>
                    </div>
                </div>
                <div class="d-flex align-items-center border-bottom py-3">
                    <img class="rounded-circle flex-shrink-0" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Jhon Doe</h6>
                            <small>15 minutes ago</small>
                        </div>
                        <span>Short message goes here...</span>
                    </div>
                </div>
                <div class="d-flex align-items-center border-bottom py-3">
                    <img class="rounded-circle flex-shrink-0" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Jhon Doe</h6>
                            <small>15 minutes ago</small>
                        </div>
                        <span>Short message goes here...</span>
                    </div>
                </div>
                <div class="d-flex align-items-center pt-3">
                    <img class="rounded-circle flex-shrink-0" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Jhon Doe</h6>
                            <small>15 minutes ago</small>
                        </div>
                        <span>Short message goes here...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Widgets End -->
@endsection