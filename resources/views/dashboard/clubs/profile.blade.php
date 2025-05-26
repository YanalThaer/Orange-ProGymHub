@extends('layouts.dashboard')
@section('title', 'Club - My Club Profile')
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
    .btn-primary {
        background-color: #ffbe33;
        border-color: #ffbe33;
        color: #000;
    }
    .btn-secondary {
        background-color: #444;
        border-color: #555;
        color: white;
    }
    .btn:hover {
        opacity: 0.85;
    }
    .img-fluid {
        border-radius: 10px;
        border: 2px solid #555;
    }
    .bg-light {
        background-color: #2a2a3f !important;
    }
    .alert-success {
        background-color: #28a745;
        color: white;
        border: none;
    }
    .rounded-circle {
        object-fit: cover;
    }
    .stat-card {
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        height: 100%;
    }
    .stat-card i {
        font-size: 2.5rem;
        margin-bottom: 15px;
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Club Profile: {{ $club->name }}</span>
                    <div>
                        <a href="{{ route('myclub.edit', $club->getEncodedId()) }}" class="btn btn-primary btn-sm">Edit Profile</a>
                        <a href="{{ route('club.dashboard') }}" class="btn btn-secondary btn-sm ms-2">Back to Dashboard</a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4 text-center mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    @if($club->logo)
                                        <img src="{{ asset('storage/'.$club->logo) }}" alt="{{ $club->name }} Logo" class="img-fluid rounded-circle mb-3" style="max-height: 200px; width: auto;">
                                    @else
                                        <div class="bg-light p-5 rounded-circle mb-3 mx-auto" style="height: 200px; width: 200px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-dumbbell fa-4x text-secondary"></i>
                                        </div>
                                    @endif
                                    <h4>{{ $club->name }}</h4>
                                    <p class="badge {{ $club->status == 'active' ? 'bg-success' : ($club->status == 'inactive' ? 'bg-danger' : 'bg-warning') }} p-2">
                                        {{ ucfirst($club->status ?? 'Unknown') }}
                                    </p>
                                    <p><i class="fas fa-envelope me-2"></i> {{ $club->email }}</p>
                                    @if($club->phone)
                                        <p><i class="fas fa-phone me-2"></i> {{ $club->phone }}</p>
                                    @endif
                                    @if($club->location)
                                        <p><i class="fas fa-map-marker-alt me-2"></i> {{ $club->city }}, {{ $club->country }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table">
                                                <tr>
                                                    <th width="40%">Address:</th>
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
                                                    <th>Website:</th>
                                                    <td>
                                                        @if($club->website)
                                                            <a href="{{ $club->website }}" target="_blank">{{ $club->website }}</a>
                                                        @else
                                                            Not specified
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Established:</th>
                                                    <td>{{ $club->established_date ? $club->established_date->format('d/m/Y') : 'Not specified' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table">
                                                <tr>
                                                    <th width="40%">Capacity:</th>
                                                    <td>{{ $club->capacity ?? 'Not specified' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Emergency Contact:</th>
                                                    <td>{{ $club->emergency_contact ?? 'Not specified' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Regular Hours:</th>
                                                    <td>
                                                        {{ $club->open_time ? $club->open_time->format('H:i') : 'N/A' }} - 
                                                        {{ $club->close_time ? $club->close_time->format('H:i') : 'N/A' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Current Status:</th>
                                                    <td>
                                                        <span class="badge {{ $club->isOpen() ? 'bg-success' : 'bg-danger' }} p-2">
                                                            {{ $club->isOpen() ? 'Open Now' : 'Closed Now' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Description & Bio</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h6 class="fw-bold">Description:</h6>
                                        <p>{{ $club->description ?? 'No description available' }}</p>
                                    </div>
                                    
                                    <div>
                                        <h6 class="fw-bold">Bio:</h6>
                                        <p>{{ $club->bio ?? 'No bio available' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Amenities & Features</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-group mb-3">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-car me-2"></i> Parking</span>
                                                    <span class="badge {{ $club->has_parking ? 'bg-success' : 'bg-secondary' }} p-2 rounded-pill">
                                                        {{ $club->has_parking ? 'Yes' : 'No' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-wifi me-2"></i> WiFi</span>
                                                    <span class="badge {{ $club->has_wifi ? 'bg-success' : 'bg-secondary' }} p-2 rounded-pill">
                                                        {{ $club->has_wifi ? 'Yes' : 'No' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-shower me-2"></i> Showers</span>
                                                    <span class="badge {{ $club->has_showers ? 'bg-success' : 'bg-secondary' }} p-2 rounded-pill">
                                                        {{ $club->has_showers ? 'Yes' : 'No' }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-group mb-3">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-lock me-2"></i> Lockers</span>
                                                    <span class="badge {{ $club->has_lockers ? 'bg-success' : 'bg-secondary' }} p-2 rounded-pill">
                                                        {{ $club->has_lockers ? 'Yes' : 'No' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-swimming-pool me-2"></i> Pool</span>
                                                    <span class="badge {{ $club->has_pool ? 'bg-success' : 'bg-secondary' }} p-2 rounded-pill">
                                                        {{ $club->has_pool ? 'Yes' : 'No' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-hot-tub me-2"></i> Sauna</span>
                                                    <span class="badge {{ $club->has_sauna ? 'bg-success' : 'bg-secondary' }} p-2 rounded-pill">
                                                        {{ $club->has_sauna ? 'Yes' : 'No' }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Working Days</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                        $workingDays = is_array($club->working_days) ? $club->working_days : json_decode($club->working_days, true);
                                    @endphp
                                    @if($club->working_days && is_array($workingDays))
                                    <ul class="list-group">
                                        @foreach($days as $index => $day)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-calendar-day me-2"></i> {{ $day }}</span>
                                                @if(isset($workingDays[$index]) && $workingDays[$index])
                                                    <span class="badge bg-success p-2 rounded-pill">Open</span>
                                                @else
                                                    <span class="badge bg-secondary p-2 rounded-pill">Closed</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                    @else
                                        <p class="text-center">No working days information available</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Special Hours</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $specialHours = is_array($club->special_hours) ? $club->special_hours : json_decode($club->special_hours, true);
                                    @endphp
                                    @if($club->special_hours && is_array($specialHours) && count($specialHours) > 0)
                                    <ul class="list-group">
                                        @foreach($specialHours as $date => $hours)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-calendar-alt me-2"></i> {{ date('d/m/Y', strtotime($date)) }}</span>
                                                <span>{{ $hours['open'] }} - {{ $hours['close'] }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @else
                                        <p class="text-center">No special hours information available</p>
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
                                            $icons = [
                                                'facebook' => 'fab fa-facebook',
                                                'instagram' => 'fab fa-instagram',
                                                'twitter' => 'fab fa-twitter',
                                                'youtube' => 'fab fa-youtube',
                                                'linkedin' => 'fab fa-linkedin',
                                                'tiktok' => 'fab fa-tiktok',
                                                'snapchat' => 'fab fa-snapchat',
                                                'pinterest' => 'fab fa-pinterest',
                                                'website' => 'fas fa-globe'
                                            ];
                                        @endphp
                                        
                                        @if(is_array($socialMedia))
                                        @foreach($socialMedia as $platform => $link)
                                            @if($link)
                                            <div class="col-md-4 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <i class="{{ $icons[$platform] ?? 'fas fa-link' }} fa-2x me-3" style="width: 30px;"></i>
                                                            <div>
                                                                <h6 class="text-capitalize mb-1">{{ $platform }}</h6>
                                                                <a href="{{ $link }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 200px;">{{ $link }}</a>
                                                            </div>
                                                        </div>
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
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Club Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <div class="stat-card bg-primary">
                                                <i class="fas fa-users"></i>
                                                <h5 class="card-title">Total Members</h5>
                                                <h3>{{ $club->users->count() ?? 0 }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="stat-card bg-success">
                                                <i class="fas fa-user-tie"></i>
                                                <h5 class="card-title">Total Coaches</h5>
                                                <h3>{{ $club->coaches->count() ?? 0 }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="stat-card bg-warning">
                                                <i class="fas fa-calendar-check"></i>
                                                <h5 class="card-title">Today's Attendance</h5>
                                                <h3>{{ $club->todayAttendance ?? 0 }}</h3>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="stat-card bg-info">
                                                <i class="fas fa-chart-line"></i>
                                                <h5 class="card-title">Monthly Visitors</h5>
                                                <h3>{{ $club->monthlyVisitors ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
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