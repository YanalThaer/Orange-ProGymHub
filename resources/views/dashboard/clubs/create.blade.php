@extends('layouts.dashboard')
@section('title', 'Admin - Create Club')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card bg-dark text-white shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Club</h5>
                    <a href="{{ route('admin.clubs') }}" class="btn btn-light text-white btn-sm">← Back to List</a>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('clubs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-info">Basic Information</h5>
                                <hr class="border-white">
                                @foreach ([
                                'name' => 'Club Name',
                                'email' => 'Email',
                                'phone' => 'Phone',
                                'password' => 'Password',
                                'bio' => 'Bio',
                                'description' => 'Description',
                                'logo' => 'Logo',
                                'established_date' => 'Established Date',
                                'capacity' => 'Capacity'
                                ] as $field => $label)
                                <div class="form-group mb-3">
                                    <label for="{{ $field }}" class="text-white">{{ $label }}</label>
                                    @if($field === 'bio' || $field === 'description')
                                    <textarea class="form-control bg-dark text-white border-white" id="{{ $field }}" name="{{ $field }}" rows="2">{{ old($field) }}</textarea>
                                    @elseif($field === 'logo')
                                    <input type="file" class="form-control bg-dark text-white border-white" id="{{ $field }}" name="{{ $field }}">
                                    @elseif($field === 'password')
                                    <input type="password" class="form-control bg-dark text-white border-white" id="{{ $field }}" name="{{ $field }}" required>
                                    @else
                                    <input type="{{ $field === 'email' ? 'email' : ($field === 'established_date' ? 'date' : ($field === 'capacity' ? 'number' : 'text')) }}" class="form-control bg-dark text-white border-white" id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" {{ $field !== 'bio' && $field !== 'description' && $field !== 'logo' ? 'required' : '' }}>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-info">Location Details</h5>
                                <hr class="border-white">
                                @foreach ([
                                'location' => 'Location',
                                'address' => 'Address',
                                'city' => 'City',
                                'country' => 'Country'
                                ] as $field => $label)
                                <div class="form-group mb-3">
                                    <label for="{{ $field }}" class="text-white">{{ $label }}</label>
                                    <input type="text" class="form-control bg-dark text-white border-white" id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" required>
                                </div>
                                @endforeach
                                <h5 class="text-info mt-4">Contact Information</h5>
                                <hr class="border-white">
                                <div class="form-group mb-3">
                                    <label for="website" class="text-white">Website</label>
                                    <input type="url" class="form-control bg-dark text-white border-white" id="website" name="website" value="{{ old('website') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="social_media" class="text-white">Social Media (JSON)</label>
                                    <textarea class="form-control bg-dark text-white border-white" id="social_media" name="social_media" placeholder='{"facebook": "url", "instagram": "url"}' rows="2">{{ old('social_media') }}</textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="emergency_contact" class="text-white">Emergency Contact</label>
                                    <input type="text" class="form-control bg-dark text-white border-white" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h5 class="text-info">Amenities</h5>
                                <hr class="border-white">
                                @foreach ([
                                'has_parking' => 'Has Parking',
                                'has_wifi' => 'Has WiFi',
                                'has_showers' => 'Has Showers',
                                'has_lockers' => 'Has Lockers',
                                'has_pool' => 'Has Pool',
                                'has_sauna' => 'Has Sauna'
                                ] as $field => $label)
                                <div class="form-check form-switch mb-2">
                                    <input type="checkbox" class="form-check-input bg-primary border" id="{{ $field }}" name="{{ $field }}" value="1" {{ old($field) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white" for="{{ $field }}">{{ $label }}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-info">Working Hours</h5>
                                <hr class="border-white">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="open_time" class="text-white">Opening Time</label>
                                        <input type="time" class="form-control bg-dark text-white border-white" id="open_time" name="open_time" value="{{ old('open_time') }}">
                                    </div>
                                    <div class="col">
                                        <label for="close_time" class="text-white">Closing Time</label>
                                        <input type="time" class="form-control bg-dark text-white border-white" id="close_time" name="close_time" value="{{ old('close_time') }}">
                                    </div>
                                </div>
                                <label class="text-white">Working Days</label>
                                <div class="row">
                                    @php
                                    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    @endphp
                                    @foreach ($days as $index => $day)
                                    <div class="col-6 col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input bg-primary border" type="checkbox" id="working_days_{{ $index }}" name="working_days[{{ $index }}]" value="1" {{ old("working_days.$index") ? 'checked' : '' }}>
                                            <label class="form-check-label text-white" for="working_days_{{ $index }}">{{ $day }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="text-white">Status</label>
                                    <select class="form-control bg-dark text-white border-white" id="status" name="status">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="under_maintenance" {{ old('status') == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="admin_id" class="text-white">Admin</label>
                                    <select class="form-control bg-dark text-white border-white" id="admin_id" name="admin_id">
                                        <option value="">-- Select Admin --</option>
                                        @foreach(\App\Models\Admin::all() as $admin)
                                        <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-danger px-4 py-2">Create Club</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection