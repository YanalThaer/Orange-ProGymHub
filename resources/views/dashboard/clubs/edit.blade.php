@extends('layouts.dashboard')
@section('title', 'Admin - Edit Club')
@section('content')
<div class="container-fluid py-4 bg-dark text-white min-vh-100">
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card shadow-lg mb-4 border-0 bg-dark-card">
                <div class="card-header bg-gradient-dark text-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 fw-bold text-white">
                                <i class="fas fa-edit me-2 text-primary"></i>
                                Edit Club: {{ $club->name }}
                            </h4>
                            <small class="text-light opacity-75">Update club information from here</small>
                        </div>
                        @if (Auth::guard('admin')->check())
                        <a href="{{ route('admin.clubs') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to List
                        </a>
                        @else
                        <a href="{{ route('club.profile') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Profile
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger-dark alert-dismissible fade show border-0 shadow-lg mb-4" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-triangle text-danger me-2 mt-1"></i>
                    <div class="text-white">
                        <h6 class="alert-heading mb-2 text-white">Please correct the following errors:</h6>
                        <ul class="mb-0 pe-3 text-white">
                            @foreach ($errors->all() as $error)
                            <li class="text-white">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success-dark alert-dismissible fade show border-0 shadow-lg mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <div class="text-white">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
            @endif
            <form action="{{ Auth::guard('admin')->check() ? route('clubs.update', $club->getEncodedId()) : route('myclub.update', $club->getEncodedId()) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card h-100 shadow-lg border-0 bg-dark-card">
                            <div class="card-header bg-dark-header border-0 py-3">
                                <h5 class="mb-0 text-primary fw-bold">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Basic Information
                                </h5>
                            </div>
                            <div class="card-body p-4 bg-dark text-white">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label fw-semibold text-white">
                                            <i class="fas fa-university me-1 text-primary"></i>
                                            Club Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control form-control-lg bg-dark text-white border-secondary" id="name" name="name"
                                            value="{{ old('name', $club->name) }}" required
                                            placeholder="Enter club name">
                                    </div>
                                    <div class="col-12">
                                        <label for="email" class="form-label fw-semibold text-white">
                                            <i class="fas fa-envelope me-1 text-primary"></i>
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control bg-dark text-white border-secondary" id="email" name="email"
                                            value="{{ old('email', $club->email) }}" required
                                            placeholder="example@domain.com">
                                    </div>
                                    <div class="col-12">
                                        <label for="phone" class="form-label fw-semibold text-white">
                                            <i class="fas fa-phone me-1 text-primary"></i>
                                            Phone Number
                                        </label>
                                        <input type="text" class="form-control bg-dark text-white border-secondary" id="phone" name="phone"
                                            value="{{ old('phone', $club->phone) }}"
                                            placeholder="+1 234 567 8900">
                                    </div>
                                    @if (Auth::guard('club')->check() && Auth::guard('club')->id() === $club->id)
                                    <div class="col-12">
                                        <div class="alert alert-info-dark border-0 bg-dark-info">
                                            <i class="fas fa-info-circle me-2 text-info"></i>
                                            <strong class="text-white">Change Password</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-semibold text-white">
                                            <i class="fas fa-lock me-1 text-primary"></i>
                                            New Password
                                        </label>
                                        <input type="password" class="form-control bg-dark text-white border-secondary" id="password" name="password">
                                        <small class="text-light">Leave empty to keep current password</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label fw-semibold text-white">
                                            <i class="fas fa-lock me-1 text-primary"></i>
                                            Confirm Password
                                        </label>
                                        <input type="password" class="form-control bg-dark text-white border-secondary" id="password_confirmation" name="password_confirmation">
                                    </div>
                                    @endif
                                    <div class="col-12">
                                        <label for="bio" class="form-label fw-semibold text-white">
                                            <i class="fas fa-user-circle me-1 text-primary"></i>
                                            Bio
                                        </label>
                                        <textarea class="form-control bg-dark text-white border-secondary" id="bio" name="bio" rows="3"
                                            placeholder="Write a brief description about the club">{{ old('bio', $club->bio) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="city" class="form-label fw-semibold text-white">
                                            <i class="fas fa-city me-1 text-primary"></i>
                                            City
                                        </label>
                                        <input type="text" class="form-control bg-dark text-white border-secondary" id="city" name="city"
                                            value="{{ old('city', $club->city) }}"
                                            placeholder="New York">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="country" class="form-label fw-semibold text-white">
                                            <i class="fas fa-flag me-1 text-primary"></i>
                                            Country
                                        </label>
                                        <input type="text" class="form-control bg-dark text-white border-secondary" id="country" name="country"
                                            value="{{ old('country', $club->country) }}"
                                            placeholder="United States">
                                    </div>
                                    <div class="col-12">
                                        <label for="address" class="form-label fw-semibold text-white">
                                            <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                            Address
                                        </label>
                                        <input type="text" class="form-control bg-dark text-white border-secondary" id="address" name="address"
                                            value="{{ old('address', $club->address) }}"
                                            placeholder="Full address">
                                    </div>
                                    <div class="col-12">
                                        <label for="location" class="form-label fw-semibold text-white">
                                            <i class="fas fa-crosshairs me-1 text-primary"></i>
                                            Geographic Coordinates
                                        </label>
                                        <input type="text" class="form-control bg-dark text-white border-secondary" id="location" name="location"
                                            value="{{ old('location', $club->location) }}"
                                            placeholder="40.7128,-74.0060">
                                        <small class="text-light">Example: latitude,longitude</small>
                                    </div>
                                    <div class="col-12">
                                        <label for="description" class="form-label fw-semibold text-white">
                                            <i class="fas fa-align-left me-1 text-primary"></i>
                                            Detailed Description
                                        </label>
                                        <textarea class="form-control bg-dark text-white border-secondary" id="description" name="description" rows="4"
                                            placeholder="Detailed description about the club and its services">{{ old('description', $club->description) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="capacity" class="form-label fw-semibold text-white">
                                            <i class="fas fa-users me-1 text-primary"></i>
                                            Capacity
                                        </label>
                                        <input type="number" class="form-control bg-dark text-white border-secondary" id="capacity" name="capacity"
                                            value="{{ old('capacity', $club->capacity) }}"
                                            placeholder="100">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="established_date" class="form-label fw-semibold text-white">
                                            <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                            Established Date
                                        </label>
                                        <input type="date" class="form-control bg-dark text-white border-secondary" id="established_date" name="established_date"
                                            value="{{ old('established_date', $club->established_date ? $club->established_date->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="website" class="form-label fw-semibold text-white">
                                            <i class="fas fa-globe me-1 text-primary"></i>
                                            Website
                                        </label>
                                        <input type="url" class="form-control bg-dark text-white border-secondary" id="website" name="website"
                                            value="{{ old('website', $club->website) }}"
                                            placeholder="https://example.com">
                                    </div>
                                    <div class="col-12">
                                        <label for="emergency_contact" class="form-label fw-semibold text-white">
                                            <i class="fas fa-phone-square-alt me-1 text-primary"></i>
                                            Emergency Contact
                                        </label>
                                        <input type="text" class="form-control bg-dark text-white border-secondary" id="emergency_contact" name="emergency_contact"
                                            value="{{ old('emergency_contact', $club->emergency_contact) }}"
                                            placeholder="Contact name and number">
                                    </div>
                                    <div class="col-12">
                                        <label for="logo" class="form-label fw-semibold text-white">
                                            <i class="fas fa-image me-1 text-primary"></i>
                                            Club Logo
                                        </label>
                                        @if($club->logo)
                                        <div class="mb-3 text-center">
                                            <img src="{{ asset('storage/' . $club->logo) }}" alt="Club Logo"
                                                class="img-thumbnail bg-dark border-secondary" style="max-width: 120px; max-height: 120px;">
                                            <p class="small text-light mt-1">Current Logo</p>
                                        </div>
                                        @endif
                                        <input type="file" class="form-control bg-dark text-white border-secondary" id="logo" name="logo" accept="image/*">
                                        <small class="text-light">Leave empty to keep current logo</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-lg border-0 bg-dark-card mb-4">
                            <div class="card-header bg-dark-header border-0 py-3">
                                <h5 class="mb-0 text-success fw-bold">
                                    <i class="fas fa-star me-2"></i>
                                    Amenities & Features
                                </h5>
                            </div>
                            <div class="card-body p-4 bg-dark text-white">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input bg-dark border-secondary" type="checkbox" id="has_parking" name="has_parking"
                                                value="1" {{ old('has_parking', $club->has_parking) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-white" for="has_parking">
                                                <i class="fas fa-parking text-primary me-2"></i>
                                                Parking
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input bg-dark border-secondary" type="checkbox" id="has_wifi" name="has_wifi"
                                                value="1" {{ old('has_wifi', $club->has_wifi) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-white" for="has_wifi">
                                                <i class="fas fa-wifi text-primary me-2"></i>
                                                Free WiFi
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input bg-dark border-secondary" type="checkbox" id="has_showers" name="has_showers"
                                                value="1" {{ old('has_showers', $club->has_showers) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-white" for="has_showers">
                                                <i class="fas fa-shower text-primary me-2"></i>
                                                Showers
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input bg-dark border-secondary" type="checkbox" id="has_lockers" name="has_lockers"
                                                value="1" {{ old('has_lockers', $club->has_lockers) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-white" for="has_lockers">
                                                <i class="fas fa-box text-primary me-2"></i>
                                                Lockers
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input bg-dark border-secondary" type="checkbox" id="has_pool" name="has_pool"
                                                value="1" {{ old('has_pool', $club->has_pool) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-white" for="has_pool">
                                                <i class="fas fa-swimming-pool text-primary me-2"></i>
                                                Pool
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input bg-dark border-secondary" type="checkbox" id="has_sauna" name="has_sauna"
                                                value="1" {{ old('has_sauna', $club->has_sauna) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-white" for="has_sauna">
                                                <i class="fas fa-hot-tub text-primary me-2"></i>
                                                Sauna
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-lg border-0 bg-dark-card mb-4">
                            <div class="card-header bg-dark-header border-0 py-3">
                                <h5 class="mb-0 text-info fw-bold">
                                    <i class="fas fa-clock me-2"></i>
                                    Operating Hours
                                </h5>
                            </div>
                            <div class="card-body p-4 bg-dark text-white">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="open_time" class="form-label fw-semibold text-white">
                                            <i class="fas fa-sun me-1 text-warning"></i>
                                            Opening Time
                                        </label>
                                        <input type="time" class="form-control form-control-lg bg-dark text-white border-secondary" id="open_time" name="open_time"
                                            value="{{ old('open_time', $club->open_time ? date('H:i', strtotime($club->open_time)) : '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="close_time" class="form-label fw-semibold text-white">
                                            <i class="fas fa-moon me-1 text-light"></i>
                                            Closing Time
                                        </label>
                                        <input type="time" class="form-control form-control-lg bg-dark text-white border-secondary" id="close_time" name="close_time"
                                            value="{{ old('close_time', $club->close_time ? date('H:i', strtotime($club->close_time)) : '') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6 class="fw-bold text-white mb-3">
                                        <i class="fas fa-calendar-week me-2 text-info"></i>
                                        Working Days
                                    </h6>
                                    @php
                                    $workingDays = is_array($club->working_days) ? $club->working_days : (json_decode($club->working_days, true) ?: []);
                                    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    @endphp
                                    <div class="row g-2">
                                        @foreach($days as $index => $day)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-check form-check-lg">
                                                <input class="form-check-input bg-dark border-secondary" type="checkbox"
                                                    id="working_day_{{ $index }}"
                                                    name="working_days[{{ $index }}]"
                                                    value="1"
                                                    {{ isset($workingDays[$index]) && $workingDays[$index] ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold text-white" for="working_day_{{ $index }}">
                                                    {{ $day }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-lg border-0 bg-dark-card mb-4">
                            <div class="card-header bg-dark-header border-0 py-3">
                                <h5 class="mb-0 text-primary fw-bold">
                                    <i class="fas fa-share-alt me-2"></i>
                                    Social Media
                                </h5>
                            </div>
                            <div class="card-body p-4 bg-dark text-white">
                                @php
                                $socialMedia = is_array($club->social_media) ? $club->social_media : (json_decode($club->social_media, true) ?: []);
                                $platforms = [
                                'facebook' => ['name' => 'Facebook', 'icon' => 'fab fa-facebook', 'color' => 'text-primary'],
                                'instagram' => ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'color' => 'text-danger'],
                                'twitter' => ['name' => 'Twitter', 'icon' => 'fab fa-twitter', 'color' => 'text-info'],
                                'youtube' => ['name' => 'YouTube', 'icon' => 'fab fa-youtube', 'color' => 'text-danger'],
                                'tiktok' => ['name' => 'TikTok', 'icon' => 'fab fa-tiktok', 'color' => 'text-light']
                                ];
                                @endphp
                                <div class="row g-3">
                                    @foreach($platforms as $platform => $info)
                                    <div class="col-12">
                                        <label for="social_{{ $platform }}" class="form-label fw-semibold text-white">
                                            <i class="{{ $info['icon'] }} {{ $info['color'] }} me-2"></i>
                                            {{ $info['name'] }}
                                        </label>
                                        <input type="url" class="form-control bg-dark text-white border-secondary" id="social_{{ $platform }}"
                                            name="social_media[{{ $platform }}]"
                                            value="{{ old("social_media.$platform", $socialMedia[$platform] ?? '') }}"
                                            placeholder="https://{{ $platform }}.com/yourpage">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-lg border-0 bg-dark-card">
                            <div class="card-header bg-dark-header border-0 py-3">
                                <h5 class="mb-0 text-warning fw-bold">
                                    <i class="fas fa-toggle-on me-2"></i>
                                    Club Status
                                </h5>
                            </div>
                            <div class="card-body p-4 bg-dark text-white">
                                <label for="status" class="form-label fw-semibold text-white">
                                    <i class="fas fa-circle me-1 text-success"></i>
                                    Current Status
                                </label>
                                <select class="form-select form-select-lg bg-dark text-white border-secondary" id="status" name="status">
                                    <option value="active" {{ old('status', $club->status) == 'active' ? 'selected' : '' }}>
                                        🟢 Active
                                    </option>
                                    <option value="inactive" {{ old('status', $club->status) == 'inactive' ? 'selected' : '' }}>
                                        🔴 Inactive
                                    </option>
                                    <option value="under_maintenance" {{ old('status', $club->status) == 'under_maintenance' ? 'selected' : '' }}>
                                        🟡 Under Maintenance
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card shadow-lg border-0 bg-dark-card">
                            <div class="card-body text-center py-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 shadow-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Update Club
                                </button>
                                <p class="text-light mt-2 mb-0 small">
                                    Make sure all information is correct before saving
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    body {
        background-color: #000000 !important;
        color: #ffffff !important;
    }

    .bg-dark {
        background-color: #000000 !important;
    }

    .bg-dark-card {
        background-color: #1a1a1a !important;
        border: 1px solid #333333 !important;
    }

    .bg-dark-header {
        background-color: #0d1117 !important;
        border-bottom: 1px solid #333333 !important;
    }

    .bg-dark-info {
        background-color: #1a2332 !important;
        border: 1px solid #2c5aa0 !important;
    }

    .bg-gradient-dark {
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
    }

    .text-white {
        color: #ffffff !important;
    }

    .text-light {
        color: #e9ecef !important;
    }

    .form-control,
    .form-select {
        background-color: #000000 !important;
        border: 1px solid #333333 !important;
        color: #ffffff !important;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #000000 !important;
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        color: #ffffff !important;
    }

    .form-control::placeholder {
        color: #888888 !important;
    }

    .form-check-input {
        background-color: #000000 !important;
        border: 1px solid #333333 !important;
    }

    .form-check-input:checked {
        background-color: #667eea !important;
        border-color: #667eea !important;
    }

    .form-check-input:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
    }

    .alert-danger-dark {
        background-color: #2c1618 !important;
        border: 1px solid #842029 !important;
        color: #ffffff !important;
    }

    .alert-success-dark {
        background-color: #0f2419 !important;
        border: 1px solid #0a3622 !important;
        color: #ffffff !important;
    }

    .card {
        transition: all 0.3s ease;
        background-color: #1a1a1a !important;
        border: 1px solid #333333 !important;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.1) !important;
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
    }

    .btn-outline-light {
        border-color: #ffffff !important;
        color: #ffffff !important;
    }

    .btn-outline-light:hover {
        background-color: #ffffff !important;
        color: #000000 !important;
    }

    .btn-primary {
        background-color: #667eea !important;
        border-color: #667eea !important;
    }
</style>
@endsection