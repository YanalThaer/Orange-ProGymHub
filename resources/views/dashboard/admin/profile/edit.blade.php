@extends('layouts.dashboard')
@section('title', 'Admin - Edit Profile')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0 text-white">Edit Profile</h4>
                    <a href="{{ route('admin.profile') }}" class="btn btn-outline-light">
                        <i class="fa fa-arrow-left me-2"></i> Back to Profile
                    </a>
                </div>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="bg-dark rounded p-4">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="position-relative" style="width: 150px; height: 150px;">
                                            @if($admin->profile_picture)
                                            <img src="{{ asset('storage/' . $admin->profile_picture) }}" id="profile-preview" alt="Admin Profile" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                            @else
                                            <img src="{{ asset('img/default-avatar.png') }}" id="profile-preview" alt="Admin Profile" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                            @endif
                                        </div>
                                    </div>
                                </div> 
                                <div class="mb-3">
                                    <label for="profile_picture" class="form-label text-white">Change Profile Picture</label>
                                    <input class="form-control form-control-sm bg-dark text-white" type="file" id="profile_picture" name="profile_picture" accept="image/*">
                                    <small class="text-white-50">Max file size: 2MB (JPEG, PNG, GIF)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="bg-dark rounded p-4">
                                <h5 class="text-white mb-4">Personal Information</h5>
                                <div class="mb-3">
                                    <label for="name" class="form-label text-white">Full Name</label>
                                    <input type="text" class="form-control bg-secondary text-white" id="name" name="name" value="{{ old('name', $admin->name) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label text-white">Email Address</label>
                                    <input type="email" class="form-control bg-secondary text-white" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label text-white">Phone Number</label>
                                    <input type="text" class="form-control bg-secondary text-white" id="phone_number" name="phone_number" value="{{ old('phone_number', $admin->phone_number) }}">
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save me-2"></i> Update Profile
                                    </button>
                                    <a href="{{ route('admin.profile') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.getElementById('profile_picture').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection