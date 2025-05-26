@extends('layouts.dashboard')

@section('title', 'Coach - My Profile')

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">My Profile</h4>
                </div>
                <div class="card-body bg-dark text-white">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 mb-4 text-center">
                            <div class="card shadow mb-4">
                                <div class="card-body bg-dark text-white">
                                    <div class="position-relative">
                                        @if ($coach->profile_image)
                                        <img src="{{ asset('storage/' . $coach->profile_image) }}" alt="{{ $coach->name }}" class="img-fluid rounded-circle img-thumbnail mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                                        @else
                                        <img src="{{ asset('img/default-avatar.png') }}" alt="{{ $coach->name }}" class="img-fluid rounded-circle img-thumbnail mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                                        @endif
                                        @if($coach->email_verified_at)
                                        <span class="position-absolute badge bg-success p-1" style="bottom: 45px; right: 70px;" title="Verified Coach">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        @endif
                                    </div>
                                    <h4 class="mb-0">{{ $coach->name }}</h4>
                                    <p class="text-muted">Coach</p>
                                    <a href="{{ route('coach.profile.edit') }}" class="btn btn-primary mt-3 w-100">
                                        <i class="fas fa-edit"></i> Edit Profile
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card mb-4 shadow">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fa fa-user me-2"></i>Personal Information</h5>
                                </div>
                                <div class="card-body bg-dark text-white">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th class="text-white" style="width: 35%"><i class="fas fa-envelope me-2 text-primary"></i>Email:</th>
                                                    <td class="text-white">{{ $coach->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-white"><i class="fas fa-phone me-2 text-primary"></i>Phone:</th>
                                                    <td class="text-white">{{ $coach->phone ?? 'Not specified' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-white"><i class="fas fa-venus-mars me-2 text-primary"></i>Gender:</th>
                                                    <td class="text-white">{{ ucfirst($coach->gender ?? 'Not specified') }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-white"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Location:</th>
                                                    <td class="text-white">{{ $coach->location ?? 'Not specified' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-white"><i class="fas fa-calendar-check me-2 text-primary"></i>Years of Experience:</th>
                                                    <td class="text-white">
                                                        @if($coach->experience_years)
                                                        <span class="badge bg-info p-2">{{ $coach->experience_years }} {{ Str::plural('year', $coach->experience_years) }}</span>
                                                        @else
                                                        <span class="text-muted">Not specified</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-white"><i class="fas fa-briefcase me-2 text-primary"></i>Employment Type:</th>
                                                    <td class="text-white">
                                                        @if($coach->employment_type)
                                                        @php
                                                        $badgeClass = $coach->employment_type == 'full-time' ? 'bg-success' : 'bg-warning text-dark';
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }} p-2">{{ ucfirst($coach->employment_type) }}</span>
                                                        @else
                                                        <span class="text-muted">Not specified</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-white"><i class="fas fa-building me-2 text-primary"></i>Club:</th>
                                                    <td class="text-white">
                                                        @if($coach->club)
                                                        {{ $coach->club->name }}
                                                        @else
                                                        <span class="text-muted">Not assigned to any club</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-white"><i class="fas fa-user-check me-2 text-primary"></i>Account Status:</th>
                                                    <td class="text-white">
                                                        @if($coach->email_verified_at)
                                                        <span class="badge bg-success p-2">Verified</span>
                                                        <small class="text-muted">({{ $coach->email_verified_at->diffForHumans() }})</small>
                                                        @else
                                                        <span class="badge bg-warning text-dark p-2">Pending Verification</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-4 shadow">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fa fa-briefcase me-2"></i>Professional Information</h5>
                                </div>
                                <div class="card-body bg-dark text-white">
                                    <div class="row mb-4">
                                        <div class="col-md-4 fw-bold"><i class="fas fa-quote-left me-2 text-primary"></i>Bio:</div>
                                        <div class="col-md-8">
                                            @if($coach->bio)
                                            <div class="p-3 bg-light rounded">
                                                {{ $coach->bio }}
                                            </div>
                                            @else
                                            <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Certifications:</div>
                                        <div class="col-md-8">
                                            @php
                                            $certifications = $coach->certifications;
                                            if (is_string($certifications)) {
                                            $certifications = json_decode($certifications, true);
                                            }
                                            @endphp

                                            @if(isset($certifications) && is_array($certifications) && count($certifications) > 0)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($certifications as $certification)
                                                <span class="badge bg-primary p-2 mb-1 text-white">{{ $certification }}</span>
                                                @endforeach
                                            </div>
                                            @else
                                            <span class="text-white">Not specified</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Specializations:</div>
                                        <div class="col-md-8">
                                            @php
                                            $specializations = $coach->specializations;
                                            if (is_string($specializations)) {
                                            $specializations = json_decode($specializations, true);
                                            }
                                            @endphp

                                            @if(isset($specializations) && is_array($specializations) && count($specializations) > 0)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($specializations as $specialization)
                                                <span class="badge bg-success p-2 mb-1">{{ $specialization }}</span>
                                                @endforeach
                                            </div>
                                            @else
                                            <span class="text-muted">Not specified</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Working Hours:</div>
                                        <div class="col-md-8">
                                            @php
                                            $workingHours = $coach->working_hours;
                                            if (is_string($workingHours)) {
                                            $workingHours = json_decode($workingHours, true);
                                            }
                                            $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                            @endphp

                                            @if(isset($workingHours) && is_array($workingHours) && count($workingHours) > 0)
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light text-white">
                                                    <tr>
                                                        <th>Day</th>
                                                        <th>Hours</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($daysOfWeek as $day)
                                                    <tr>
                                                        <td><strong>{{ ucfirst($day) }}</strong></td>
                                                        <td> @if(isset($workingHours[$day]) && is_array($workingHours[$day]) && !empty($workingHours[$day]) && isset($workingHours[$day][0]) && $workingHours[$day][0] !== null)
                                                            {{ implode(', ', $workingHours[$day]) }}
                                                            @else
                                                            <span class="text-white">Not available</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @else
                                            <span class="text-white">Not specified</span>
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
    </div>
</div>
@endsection