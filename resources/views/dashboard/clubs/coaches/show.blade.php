@extends('layouts.dashboard')
@section('title', 'Club - Coach Details')
@section('content')
<style>
    body,
    .container {
        background-color: #121212 !important;
        color: #eee !important;
    }
    .card {
        background-color: #1e1e1e;
        color: #eee;
        border: 1px solid #333;
    }
    .card-header {
        background-color: black !important;
        color: #fff !important;
        border-bottom: 1px solid #444;
    }
    .table {
        color: #eee;
    }
    .table-bordered {
        border-color: #444;
    }
    .table-bordered th,
    .table-bordered td {
        border-color: #444;
    }
    .badge {
        font-weight: 500;
    }
    .badge.bg-primary {
        background-color: #0d6efd !important;
    }
    .badge.bg-info {
        background-color: #17a2b8 !important;
    }
    .badge.bg-light {
        background-color: #2d2d2d !important;
        color: #eee !important;
    }
    .alert {
        background-color: #2a2a2a;
        border-color: #444;
        color: #eee;
    }
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .img-fluid.rounded-circle {
        border: 3px solid #444;
    }
    .text-muted {
        color: #aaa !important;
    }
</style>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-user-tie me-2"></i>Coach Details</span>
                    <div>
                        <a href="{{ route('club.coaches.edit', $coach->encoded_id) }}" class="btn btn-danger btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('club.coaches') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body bg-dark text-white">
                    @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-4 text-center mb-4 mb-lg-0">
                            <div class="coach-profile-image mb-3">
                                @if($coach->profile_image)
                                <img src="{{ asset('storage/' . $coach->profile_image) }}" alt="{{ $coach->name }}"
                                    class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                                @else
                                <img src="{{ asset('img/default-avatar.png') }}" alt="{{ $coach->name }}"
                                    class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                                @endif
                            </div>
                            <h3 class="mb-1">{{ $coach->name }}</h3>
                            @if($coach->employment_type)
                            <span class="badge bg-primary mb-2">{{ $coach->employment_type }}</span>
                            @endif
                            <div class="mb-3">
                                @if($coach->experience_years)
                                <p class="mb-1">
                                    <i class="fas fa-history me-1"></i> {{ $coach->experience_years }} {{ Str::plural('year', $coach->experience_years) }} experience
                                </p>
                                @endif
                                <p class="mb-0">
                                    <i class="fas fa-envelope me-1"></i> {{ $coach->email }}
                                </p>
                                @if($coach->phone)
                                <p class="mb-0">
                                    <i class="fas fa-phone me-1"></i> {{ $coach->phone }}
                                </p>
                                @endif
                                @if($coach->location)
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $coach->location }}
                                </p>
                                @endif
                            </div>
                            <div class="d-flex justify-content-center">
                                <form action="{{ route('club.coaches.delete', $coach->encoded_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this coach?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card mb-4 bg-dark">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Coach Bio</h5>
                                </div>
                                <div class="card-body">
                                    @if($coach->bio)
                                    <p>{{ $coach->bio }}</p>
                                    @else
                                    <p class="text-muted">No bio information provided.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4 bg-dark">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>Certifications</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($coach->certifications && count(json_decode($coach->certifications)) > 0)
                                            <ul class="list-group list-group-flush bg-transparent">
                                                @foreach(json_decode($coach->certifications) as $certification)
                                                <li class="list-group-item bg-transparent border-secondary">{{ $certification }}</li>
                                                @endforeach
                                            </ul>
                                            @else
                                            <p class="text-muted">No certifications listed.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-4 bg-dark">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-dumbbell me-2"></i>Specializations</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($coach->specializations && count(json_decode($coach->specializations)) > 0)
                                            <div>
                                                @foreach(json_decode($coach->specializations) as $specialization)
                                                <span class="badge bg-info mb-2 me-2">{{ $specialization }}</span>
                                                @endforeach
                                            </div>
                                            @else
                                            <p class="text-muted">No specializations listed.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card bg-dark">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Working Hours</h5>
                                </div>
                                <div class="card-body">
                                    @if($coach->working_hours && !empty(json_decode($coach->working_hours, true)))
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Hours</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(json_decode($coach->working_hours, true) as $day => $hours)
                                                <tr>
                                                    <td class="text-capitalize">{{ $day }}</td>
                                                    <td>
                                                        @foreach($hours as $timeSlot)
                                                        <span class="badge bg-light text-dark me-2">{{ $timeSlot }}</span>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <p class="text-muted">No working hours specified.</p>
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