@extends('layouts.dashboard')
@section('title', 'Admin - My Profile')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0 text-white">Admin Profile</h4>
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                        <i class="fa fa-edit me-2"></i> Edit Profile
                    </a>
                </div>
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-4 mb-4 text-center">
                        <div class="bg-dark rounded p-4">
                            @if($admin->profile_picture)
                            <img src="{{ asset('storage/' . $admin->profile_picture) }}" alt="Admin Profile" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                            <img src="{{ asset('img/default-avatar.png') }}" alt="Admin Profile" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @endif
                            <h4 class="text-white">{{ $admin->name }}</h4>
                            <span class="badge bg-primary px-3 py-2">Administrator</span>
                            @if($admin->role)
                            <span class="badge bg-info px-3 py-2 mt-2">{{ ucfirst($admin->role) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="bg-dark rounded p-4">
                            <h5 class="text-white mb-4">Personal Information</h5>
                            <div class="row mb-3">
                                <div class="col-md-4 text-white-50">Name:</div>
                                <div class="col-md-8 text-white">{{ $admin->name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-white-50">Email:</div>
                                <div class="col-md-8 text-white">{{ $admin->email }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-white-50">Phone Number:</div>
                                <div class="col-md-8 text-white">{{ $admin->phone_number ?? 'Not provided' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-white-50">Email Verified:</div>
                                <div class="col-md-8">
                                    @if($admin->email_verified_at)
                                    <span class="badge bg-success">Verified on {{ $admin->email_verified_at->format('M d, Y') }}</span>
                                    @else
                                    <span class="badge bg-danger">Not Verified</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-white-50">Last Login:</div>
                                <div class="col-md-8 text-white">
                                    {{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y H:i:s') : 'No login record' }}
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