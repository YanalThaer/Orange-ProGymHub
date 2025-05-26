@extends('layouts.dashboard')
@section('title', 'Club - Add New Coach')

@section('content')
<style>
    body, .container {
        background-color: #121212 !important;
        color: #eee !important;
    }
    .card {
        background-color: #1e1e1e;
        color: #eee;
        border: 1px solid #333;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .card-header {
        background-color: black !important;
        color: #fff !important;
        border-bottom: 1px solid #444;
        font-weight: 600;
    }
    .form-control, .form-select {
        background-color: #2d2d2d;
        border-color: #444;
        color: #eee;
    }
    .form-control:focus, .form-select:focus {
        background-color: #333;
        border-color: #555;
        color: #fff;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .form-check-input {
        background-color: #333;
        border-color: #555;
    }
    .form-check-input:checked {
        background-color: red;
        border-color: red;
    }
    .btn-primary {
        background-color: red;
        border-color: red;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .btn-outline-primary {
        color: red;
        border-color: red;
    }
    .btn-outline-primary:hover {
        background-color: red;
        color: #fff;
    }
    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }
    .alert {
        background-color: #252525;
        border-color: #444;
        color: #eee;
    }
    .alert-danger {
        background-color: #2a1a1a;
        border-color: #5c3c3c;
        color: #ff6b6b;
    }
    .alert-success {
        background-color: #1a2a1a;
        border-color: #3c5c3c;
        color: #6bff6b;
    }
    .img-thumbnail {
        background-color: #2d2d2d;
        border-color: #444;
    }
    .text-muted {
        color: #aaa !important;
    }
    .section-title {
        border-bottom: 1px solid #444;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        color: red;
    }
    .time-input {
        max-width: 150px;
    }
    .form-check-label {
        min-width: 120px;
    }
    .work-hours-container {
        background-color: #252525;
        padding: 1rem;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-user-plus me-2"></i>Add New Coach</span>
                    <a href="{{ route('club.coaches') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Coaches
                    </a>
                </div>
                <div class="card-body bg-dark text-light">
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Validation Errors</h5>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('club.coaches.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Personal Information Column -->
                            <div class="col-md-6">
                                <h5 class="section-title"><i class="fas fa-user-circle me-2"></i>Personal Information</h5>
                                
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                        id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                        id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                        id="phone" name="phone" value="{{ old('phone') }}">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                        id="location" name="location" value="{{ old('location') }}">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                                        id="profile_image" name="profile_image">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                        id="password" name="password" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                        id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                            
                            <!-- Professional Information Column -->
                            <div class="col-md-6">
                                <h5 class="section-title"><i class="fas fa-briefcase me-2"></i>Professional Information</h5>
                                
                                <div class="form-group mb-3">
                                    <label for="bio" class="form-label">Bio</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" 
                                        id="bio" name="bio" rows="4">{{ old('bio') }}</textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="experience_years" class="form-label">Years of Experience</label>
                                    <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                        id="experience_years" name="experience_years" value="{{ old('experience_years') }}">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="employment_type" class="form-label">Employment Type</label>
                                    <select class="form-select @error('employment_type') is-invalid @enderror" id="employment_type" name="employment_type">
                                        <option value="">Select Employment Type</option>
                                        <option value="Full-time" {{ old('employment_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                        <option value="Part-time" {{ old('employment_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                        <option value="Contractor" {{ old('employment_type') == 'Contractor' ? 'selected' : '' }}>Contractor</option>
                                    </select>
                                </div>
                                
                                <!-- Specializations -->
                                <div class="form-group mb-4">
                                    <label class="form-label">Specializations</label>
                                    <div class="row">
                                        @foreach([
                                            'Weight Loss', 'Muscle Building', 'Functional Training',
                                            'CrossFit', 'Yoga', 'Strength Training'
                                        ] as $specialization)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                        name="specializations[]" 
                                                        value="{{ $specialization }}" 
                                                        id="spec_{{ Str::slug($specialization) }}" 
                                                        {{ in_array($specialization, old('specializations', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="spec_{{ Str::slug($specialization) }}">
                                                        {{ $specialization }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Certifications -->
                                <div class="form-group mb-4">
                                    <label class="form-label">Certifications</label>
                                    <div class="row">
                                        @foreach([
                                            'ACE', 'NASM', 'ISSA',
                                            'ACSM', 'NSCA', 'CPR Certified'
                                        ] as $certification)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                        name="certifications[]" 
                                                        value="{{ $certification }}" 
                                                        id="cert_{{ Str::slug($certification) }}" 
                                                        {{ in_array($certification, old('certifications', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="cert_{{ Str::slug($certification) }}">
                                                        {{ $certification }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Working Hours -->
                                <div class="form-group">
                                    <label class="form-label">Working Hours</label>
                                    <small class="form-text text-muted d-block mb-3">Specify working hours for different days (optional)</small>
                                    
                                    <div class="work-hours-container">
                                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input day-toggle" type="checkbox" 
                                                            id="working_{{ $day }}" 
                                                            data-day="{{ $day }}" 
                                                            {{ old('working_days.'.$day) ? 'checked' : '' }}>
                                                        <label class="form-check-label text-capitalize" for="working_{{ $day }}">
                                                            {{ ucfirst($day) }}
                                                        </label>
                                                    </div>
                                                    <div class="work-hours-inputs flex-grow-1" 
                                                        id="hours_{{ $day }}" 
                                                        style="{{ old('working_days.'.$day) ? '' : 'display: none;' }}">
                                                        <div class="d-flex align-items-center">
                                                            <input type="text" class="form-control form-control-sm me-2 time-input" 
                                                                name="working_hours[{{ $day }}][]" 
                                                                value="{{ old('working_hours.'.$day.'.0') }}" 
                                                                placeholder="09:00-17:00">
                                                            <button type="button" class="btn btn-sm btn-outline-primary add-time-slot" data-day="{{ $day }}">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-plus me-1"></i> Add Coach
                                </button>
                                <a href="{{ route('club.coaches') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle working days
        $('.day-toggle').change(function() {
            const day = $(this).data('day');
            $('#hours_' + day).toggle(this.checked);
        });
        
        // Add time slot
        $('.add-time-slot').click(function() {
            const day = $(this).data('day');
            const container = $(this).closest('.d-flex');
            
            const newInput = $(`
                <div class="d-flex align-items-center mt-2">
                    <input type="text" class="form-control form-control-sm me-2 time-input" 
                        name="working_hours[${day}][]" placeholder="09:00-17:00">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-time-slot">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            `);
                
            container.after(newInput);
        });
        
        // Remove time slot
        $(document).on('click', '.remove-time-slot', function() {
            $(this).closest('.d-flex').remove();
        });
    });
</script>
@endpush
@endsection