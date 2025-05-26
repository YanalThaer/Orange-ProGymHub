@extends('layouts.dashboard')
@section('title', 'Admin - Club Details')
@section('content')
<style>
    body, .container, .card, .card-header, .card-body {
        background-color: black !important;
        color: #ffffff;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    .card-header {
        background-color: #2a2a3f !important;
        color: #ffffff;
        border-bottom: 1px solid #444;
        font-weight: 600;
    }
    .table {
        color: #fff;
    }
    .table th, .table td {
        background-color: transparent !important;
        border-color: #444 !important;
    }
    .table thead th {
        color: #ffbe33;
        border-bottom: 2px solid #666;
    }
    .badge {
        font-size: 0.9rem;
    }
    .list-group-item {
        background-color: #2e2e40;
        color: #ffffff;
        border-color: #444;
    }
    .btn {
        border-radius: 5px;
        font-weight: 500;
    }
    .btn-secondary {
        background-color: #444;
        border-color: #555;
        color: white;
    }
    .btn-info {
        background-color: #17a2b8;
        border-color: #138496;
    }
    .btn:hover {
        opacity: 0.85;
    }
    .img-fluid {
        border-radius: 10px;
        border: 2px solid #555;
    }
    @media (max-width: 767px) {
        .card-header h5, .card-body h6, .card-body p, .table th, .table td {
            font-size: 0.95rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 0.85rem;
        }
    }
</style>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
                @endif
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Club Details: {{ $club->name }}</span>
                    <div>
                        <a href="{{ route('admin.clubs') }}" class="btn btn-secondary btn-sm">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th width="30%">Club Name:</th>
                                            <td>{{ $club->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Location:</th>
                                            <td>{{ $club->location }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{ $club->address ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th>City:</th>
                                            <td>{{ $club->city ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Country:</th>
                                            <td>{{ $club->country ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $club->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $club->phone ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Website:</th>
                                            <td>{{ $club->website ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge {{ $club->status == 'active' ? 'bg-success' : ($club->status == 'inactive' ? 'bg-danger' : 'bg-warning') }}">
                                            {{ ucfirst($club->status) }}
                                        </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Established:</th>
                                            <td>{{ $club->established_date ? $club->established_date->format('d/m/Y') : 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Capacity:</th>
                                            <td>{{ $club->capacity ?? 'Not specified' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Description & Bio</h5>
                                </div>
                                <div class="card-body">
                                    <h6>Description:</h6>
                                    <p>{{ $club->description ?? 'No description available' }}</p>
                                    <h6 class="mt-3">Bio:</h6>
                                    <p>{{ $club->bio ?? 'No bio available' }}</p>
                                    @if($club->logo)
                                    <div class="text-center mt-3">
                                        <img src="{{ asset('storage/'.$club->logo) }}" alt="{{ $club->name }} Logo" class="img-fluid" style="max-height: 150px;">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Operating Hours</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span><strong>Current Status:</strong></span>
                                        <span class="badge {{ $club->isOpen() ? 'bg-success' : 'bg-danger' }} p-2">
                                            {{ $club->isOpen() ? 'Open Now' : 'Closed Now' }}
                                        </span>
                                    </div>
                                    <p><strong>Regular Hours:</strong>
                                        {{ $club->open_time ? $club->open_time->format('H:i') : 'N/A' }} -
                                        {{ $club->close_time ? $club->close_time->format('H:i') : 'N/A' }}
                                    </p>
                                    @if($club->working_days)
                                    <strong>Working Days:</strong>
                                    <ul class="list-group mt-2">
                                        @php
                                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                        $workingDays = is_array($club->working_days) ? $club->working_days : json_decode($club->working_days, true);
                                        @endphp
                                        @if(is_array($workingDays))
                                        @foreach($days as $index => $day)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $day }}
                                            @if(isset($workingDays[$index]) && $workingDays[$index])
                                            <span class="badge bg-success">Open</span>
                                            @else <span class="badge bg-secondary">Closed</span>
                                            @endif
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                    @endif
                                    @if($club->special_hours)
                                    <div class="mt-3">
                                        <strong>Special Hours:</strong>
                                        <ul class="list-group mt-2">
                                            @php
                                            $specialHours = is_array($club->special_hours) ? $club->special_hours : json_decode($club->special_hours, true);
                                            @endphp
                                            @if(is_array($specialHours))
                                            @foreach($specialHours as $date => $hours)
                                            <li class="list-group-item"> {{ date('d/m/Y', strtotime($date)) }}: {{ $hours['open'] }} - {{ $hours['close'] }}
                                            </li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Amenities & Features</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Parking
                                            <span class="badge {{ $club->has_parking ? 'bg-success' : 'bg-secondary' }} p-2">
                                                {{ $club->has_parking ? 'Available' : 'Not Available' }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            WiFi
                                            <span class="badge {{ $club->has_wifi ? 'bg-success' : 'bg-secondary' }} p-2">
                                                {{ $club->has_wifi ? 'Available' : 'Not Available' }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Showers
                                            <span class="badge {{ $club->has_showers ? 'bg-success' : 'bg-secondary' }} p-2">
                                                {{ $club->has_showers ? 'Available' : 'Not Available' }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Lockers
                                            <span class="badge {{ $club->has_lockers ? 'bg-success' : 'bg-secondary' }} p-2">
                                                {{ $club->has_lockers ? 'Available' : 'Not Available' }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Pool
                                            <span class="badge {{ $club->has_pool ? 'bg-success' : 'bg-secondary' }} p-2">
                                                {{ $club->has_pool ? 'Available' : 'Not Available' }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Sauna
                                            <span class="badge {{ $club->has_sauna ? 'bg-success' : 'bg-secondary' }} p-2">
                                                {{ $club->has_sauna ? 'Available' : 'Not Available' }}
                                            </span>
                                        </li>
                                    </ul>
                                    @if($club->emergency_contact)
                                    <div class="mt-3">
                                        <strong>Emergency Contact:</strong> {{ $club->emergency_contact }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($club->social_media)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Social Media</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @php
                                        $socialMedia = is_array($club->social_media) ? $club->social_media : json_decode($club->social_media, true);
                                        @endphp
                                        @if(is_array($socialMedia))
                                        @foreach($socialMedia as $platform => $link)
                                        @if($link)
                                        <div class="col-md-4 mb-2">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="text-capitalize">{{ $platform }}:</h6>
                                                    <a href="{{ $link }}" target="_blank">{{ $link }}</a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Subscription Plans</h5>
                                </div>
                                <div class="card-body">
                                    @if($club->subscriptionPlans && $club->subscriptionPlans->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Price</th>
                                                    <th>Duration</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($club->subscriptionPlans as $plan)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $plan->name }}</td>
                                                    <td>{{ ucfirst($plan->type) }}</td>
                                                    <td>{{ number_format($plan->price, 2) }} JOD</td>
                                                    <td>{{ $plan->duration_days }} days</td>
                                                    <td>
                                                        <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $plan->created_at->format('d M Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <p class="text-center">No subscription plans available for this club.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Associated Coaches</h5>
                                </div>
                                <div class="card-body">
                                    @if($club->coaches && $club->coaches->count() > 0)
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($club->coaches as $coach)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $coach->name }}</td>
                                                <td>{{ $coach->email }}</td>
                                                <td>{{ $coach->phone }}</td>
                                                <td>
                                                    <a href="" class="btn btn-sm btn-info">View</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <p>No coaches associated with this club.</p>
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
@endsection