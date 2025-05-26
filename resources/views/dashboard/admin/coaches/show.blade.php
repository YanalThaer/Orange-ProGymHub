@extends('layouts.dashboard')
@section('title', 'Admin - Coach Details')
@section('content')
<style>
    body, .container-fluid {
        background-color: #121212 !important;
        color: #eee !important;
    }
    .card {
        background-color: #1e1e1e;
        color: #eee;
        border: 1px solid #333;
    }
    .card-header {
        background-color: #333 !important;
        color: #fff !important;
        font-weight: 600;
    }
    table.table-bordered {
        border-color: #444;
    }
    table.table-bordered th,
    table.table-bordered td {
        border-color: #444;
        color: #eee;
    }
    .badge.bg-success {
        background-color: #28a745 !important;
    }
    .badge.bg-primary {
        background-color: #0d6efd !important;
    }
    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    .badge.bg-info {
        background-color: #17a2b8 !important;
    }
    .badge.bg-danger {
        background-color: #dc3545 !important;
    }
    .alert {
        background-color: #2a2a2a;
        border-color: #444;
        color: #eee;
    }
    a.btn.btn-primary.btn-sm {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    a.btn.btn-primary.btn-sm:hover {
        background-color: #0b5ed7;
        border-color: #0b5ed7;
    }
    a.btn.btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #eee;
    }
    a.btn.btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
        color: #fff;
    }
    button.btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    button.btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    .img-fluid.rounded-circle {
        border: 2px solid #444;
    }
    .text-end {
        text-align: right !important;
    }
    .mb-4 {
        margin-bottom: 1.5rem !important;
    }
    .row > .col-md-6,
    .row > .col-md-9,
    .row > .col-md-3 {
        padding-bottom: 1rem;
    }
</style>
<div class="container-fluid px-4">
    <div class="row justify-content-center mt-4">
        <div class="col-xl-10">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Coach Information
                </div>
                <div class="card-body">
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-3 text-center">
                            @if($coach->profile_image)
                                <img src="{{ asset('storage/' . $coach->profile_image) }}" class="img-fluid mb-3" alt="{{ $coach->name }}" style="max-width: 400px; max-height: 150px;">
                            @else
                                <img src="{{ asset('img/default-avatar.png') }}" class="img-fluid rounded-circle mb-3" alt="{{ $coach->name }}" style="max-width: 150px; max-height: 150px;">
                            @endif
                        </div>
                        <div class="col-md-9">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th width="30%">Coach ID</th>
                                        <td>{{ $coach->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Full Name</th>
                                        <td>{{ $coach->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $coach->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Club</th>
                                        <td>{{ $coach->club ? $coach->club->name : 'Not assigned to any club' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $coach->phone ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gender</th>
                                        <td>{{ ucfirst($coach->gender ?? 'Not provided') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Experience</th>
                                        <td>{{ $coach->experience_years ? $coach->experience_years . ' years' : 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Employment Type</th>
                                        <td>{{ ucfirst($coach->employment_type ?? 'Not provided') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td>{{ $coach->location ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Registered</th>
                                        <td>{{ $coach->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card bg-warning text-dark">
                                <div class="card-header">
                                    <i class="fas fa-bug me-1"></i>
                                    Diagnostic Information
                                </div>
                                <div class="card-body small">
                                    <div><strong>Certifications Raw:</strong> <code>{{ json_encode($coach->certifications) }}</code></div>
                                    <div><strong>Certifications Type:</strong> <code>{{ gettype($coach->certifications) }}</code></div>
                                    <div><strong>Specializations Raw:</strong> <code>{{ json_encode($coach->specializations) }}</code></div>
                                    <div><strong>Specializations Type:</strong> <code>{{ gettype($coach->specializations) }}</code></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-certificate me-1"></i>
                                    Certifications
                                </div>
                                <div class="card-body">
                                    @php
                                        $certType = gettype($coach->certifications);
                                        if ($certType === 'string') {
                                            $coach->certifications = json_decode($coach->certifications, true) ?: [];
                                        }
                                    @endphp
                                    @if($coach->certifications && is_array($coach->certifications) && count($coach->certifications) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Certification</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($coach->certifications as $index => $certification)
                                                        <tr>
                                                            <td width="50">{{ $index + 1 }}</td>
                                                            <td><span class="badge bg-success me-2"><i class="fas fa-check-circle"></i></span>{{ $certification }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-end mt-2">
                                            <span class="badge bg-info">{{ count($coach->certifications) }} certifications</span>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            No certifications listed. This coach has not provided certification information.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-dumbbell me-1"></i>
                                    Specializations
                                </div>
                                <div class="card-body">
                                    @php
                                        $specType = gettype($coach->specializations);
                                        if ($specType === 'string') {
                                            $coach->specializations = json_decode($coach->specializations, true) ?: [];
                                        }
                                    @endphp
                                    @if($coach->specializations && is_array($coach->specializations) && count($coach->specializations) > 0)
                                        <div class="row mb-3">
                                            @foreach($coach->specializations as $specialization)
                                                <div class="col-md-6 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-primary me-2"><i class="fas fa-star"></i></span>
                                                        <span>{{ $specialization }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-info">{{ count($coach->specializations) }} specialization areas</span>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            No specializations listed. This coach has not provided specialization information.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($coach->bio)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-dark text-light">
                                    <div class="card-header">
                                        <i class="fas fa-info-circle me-1"></i>
                                        About Coach
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br(e($coach->bio)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.coaches') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Coaches List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
