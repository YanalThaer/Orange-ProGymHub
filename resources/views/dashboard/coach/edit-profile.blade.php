@extends('layouts.dashboard')

@section('title', 'Coach - Edit Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Profile</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('coach.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="text-center mb-3">
                                    <div class="mb-3">
                                        @if ($coach->profile_image)
                                        <img src="{{ asset('storage/' . $coach->profile_image) }}" alt="{{ $coach->name }}" class="img-fluid rounded-circle img-thumbnail mb-3" style="width: 200px; height: 200px; object-fit: cover;" id="profile-image-preview">
                                        @else
                                        <img src="{{ asset('img/default-avatar.png') }}" alt="{{ $coach->name }}" class="img-fluid rounded-circle img-thumbnail mb-3" style="width: 200px; height: 200px; object-fit: cover;" id="profile-image-preview">
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="profile_image" class="form-label">Change Profile Image</label>
                                        <input type="file" class="form-control" name="profile_image" id="profile_image" accept="image/*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0">Personal Information</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $coach->name) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email', $coach->email) }}" required>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" value="{{ old('phone', $coach->phone) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                                        <select class="form-select @error('gender') is-invalid @enderror" name="gender" id="gender" required>
                                                            <option value="">Select Gender</option>
                                                            <option value="male" {{ (old('gender', $coach->gender) == 'male') ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ (old('gender', $coach->gender) == 'female') ? 'selected' : '' }}>Female</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="location" class="form-label">Location</label>
                                                        <input type="text" class="form-control @error('location') is-invalid @enderror" name="location" id="location" value="{{ old('location', $coach->location) }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="experience_years" class="form-label">Years of Experience</label>
                                                        <input type="number" class="form-control @error('experience_years') is-invalid @enderror" name="experience_years" id="experience_years" value="{{ old('experience_years', $coach->experience_years) }}" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0">Professional Information</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label for="bio" class="form-label">Bio</label>
                                                    <textarea class="form-control @error('bio') is-invalid @enderror" name="bio" id="bio" rows="4">{{ old('bio', $coach->bio) }}</textarea>
                                                </div>

                                                <div class="mb-3" id="certifications-container">
                                                    <label class="form-label">Certifications</label>
                                                    @if(isset($coach->certifications) && is_array($coach->certifications) && count($coach->certifications) > 0)
                                                    @foreach($coach->certifications as $index => $certification)
                                                    <div class="input-group mb-2 certification-input">
                                                        <input type="text" class="form-control" name="certifications[]" value="{{ $certification }}">
                                                        <button type="button" class="btn btn-danger remove-certification">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <div class="input-group mb-2 certification-input">
                                                        <input type="text" class="form-control" name="certifications[]" value="">
                                                        <button type="button" class="btn btn-danger remove-certification">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary" id="add-certification">
                                                        <i class="fas fa-plus"></i> Add Certification
                                                    </button>
                                                </div>
                                                <div class="mb-3" id="specializations-container">
                                                    <label class="form-label">Specializations</label>
                                                    @if(isset($coach->specializations) && is_array($coach->specializations) && count($coach->specializations) > 0)
                                                    @foreach($coach->specializations as $index => $specialization)
                                                    <div class="input-group mb-2 specialization-input">
                                                        <input type="text" class="form-control" name="specializations[]" value="{{ $specialization }}">
                                                        <button type="button" class="btn btn-danger remove-specialization">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <div class="input-group mb-2 specialization-input">
                                                        <input type="text" class="form-control" name="specializations[]" value="">
                                                        <button type="button" class="btn btn-danger remove-specialization">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary" id="add-specialization">
                                                        <i class="fas fa-plus"></i> Add Specialization
                                                    </button>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="employment_type" class="form-label">Employment Type</label>
                                                    <select class="form-select @error('employment_type') is-invalid @enderror" name="employment_type" id="employment_type">
                                                        <option value="">Select Employment Type</option>
                                                        <option value="full-time" {{ (old('employment_type', $coach->employment_type) == 'full-time') ? 'selected' : '' }}>Full-time</option>
                                                        <option value="part-time" {{ (old('employment_type', $coach->employment_type) == 'part-time') ? 'selected' : '' }}>Part-time</option>
                                                        <option value="contract" {{ (old('employment_type', $coach->employment_type) == 'contract') ? 'selected' : '' }}>Contract</option>
                                                        <option value="freelance" {{ (old('employment_type', $coach->employment_type) == 'freelance') ? 'selected' : '' }}>Freelance</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0">Working Hours</h5>
                                            </div>
                                            <div class="card-body">
                                                @php
                                                $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                                $workingHours = $coach->working_hours ?? [];
                                                @endphp

                                                @foreach($daysOfWeek as $day)
                                                <div class="mb-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="form-check form-switch mb-2">
                                                                @php
                                                                $isDayEnabled = isset($workingHours[$day]) &&
                                                                is_array($workingHours[$day]) &&
                                                                !empty($workingHours[$day][0]);
                                                                $dayHours = $isDayEnabled && isset($workingHours[$day][0]) ?
                                                                $workingHours[$day][0] : '';
                                                                @endphp

                                                                <input class="form-check-input day-toggle" type="checkbox"
                                                                    id="toggle_{{ $day }}"
                                                                    data-day="{{ $day }}"
                                                                    {{ $isDayEnabled ? 'checked' : '' }}>
                                                                <label class="form-check-label text-capitalize" for="toggle_{{ $day }}">
                                                                    <strong>{{ $day }}</strong>
                                                                </label>
                                                            </div>

                                                            <div class="row day-hours {{ !$isDayEnabled ? 'd-none' : '' }}" id="hours_{{ $day }}">
                                                                <div class="col-md-5">
                                                                    <label for="{{ $day }}_start" class="form-label">Start Time</label>
                                                                    <input type="time" class="form-control"
                                                                        id="{{ $day }}_start"
                                                                        name="working_hours[{{ $day }}][]"
                                                                        value="{{ $isDayEnabled ? $dayHours : '' }}"
                                                                        {{ !$isDayEnabled ? 'disabled' : '' }}>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label for="{{ $day }}_end" class="form-label">End Time</label>
                                                                    <input type="time" class="form-control"
                                                                        id="{{ $day }}_end"
                                                                        name="working_hours[{{ $day }}][]"
                                                                        value="{{ $isDayEnabled && isset($workingHours[$day][1]) ? $workingHours[$day][1] : '' }}"
                                                                        {{ !$isDayEnabled ? 'disabled' : '' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0">Change Password</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="password" class="form-label">New Password</label>
                                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password">
                                                            <small class="text-muted">Leave blank if you don't want to change the password</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ route('coach.profile') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('profile_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-image-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('add-certification').addEventListener('click', function() {
        const container = document.getElementById('certifications-container');
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-2', 'certification-input');

        inputGroup.innerHTML = `
            <input type="text" class="form-control" name="certifications[]" value="">
            <button type="button" class="btn btn-danger remove-certification">
                <i class="fas fa-times"></i>
            </button>
        `;

        container.insertBefore(inputGroup, this);

        inputGroup.querySelector('.remove-certification').addEventListener('click', function() {
            this.closest('.certification-input').remove();
        });
    });

    document.querySelectorAll('.remove-certification').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.certification-input').remove();
        });
    });

    document.getElementById('add-specialization').addEventListener('click', function() {
        const container = document.getElementById('specializations-container');
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-2', 'specialization-input');

        inputGroup.innerHTML = `
            <input type="text" class="form-control" name="specializations[]" value="">
            <button type="button" class="btn btn-danger remove-specialization">
                <i class="fas fa-times"></i>
            </button>
        `;

        container.insertBefore(inputGroup, this);

        inputGroup.querySelector('.remove-specialization').addEventListener('click', function() {
            this.closest('.specialization-input').remove();
        });
    });
    document.querySelectorAll('.remove-specialization').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.specialization-input').remove();
        });
    });

    document.querySelectorAll('.day-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const day = this.dataset.day;
            const hoursDiv = document.getElementById(`hours_${day}`);
            const inputs = hoursDiv.querySelectorAll('input[type="time"]');

            if (this.checked) {
                hoursDiv.classList.remove('d-none');
                inputs.forEach(input => {
                    input.disabled = false;
                });
            } else {
                hoursDiv.classList.add('d-none');
                inputs.forEach(input => {
                    input.disabled = true;
                    input.value = '';
                });
            }
        });
    });
</script>
@endsection