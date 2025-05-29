@extends('layouts.dashboard')
@section('title', 'Club - Create Subscription Plans')
@section('content')
<style>
    body {
        background-color: #121212;
        color: #ffffff;
    }

    .card {
        background-color: #1e1e1e;
        border: none;
        color: #ffffff;
    }

    .card-header {
        background-color: black;
        border-bottom: 1px solid #444;
    }

    .form-control,
    .form-select {
        background-color: #2b2b2b;
        border: 1px solid #555;
        color: #ffffff;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #2b2b2b;
        color: #ffffff;
        border-color: #888;
        box-shadow: none;
    }

    .form-check-label,
    .form-text,
    .invalid-feedback {
        color: #cccccc;
    }

    .input-group-text {
        background-color: #2b2b2b;
        border: 1px solid #555;
        color: #ffffff;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
    }

    .btn:hover {
        opacity: 0.9;
    }

    label span.text-danger {
        color: #ff6b6b;
    }
</style>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h4 class="card-title">Create Subscription Plan</h4>
                </div>
                <div class="card-body bg-dark text-white">
                    <form action="{{ route('club.subscription-plans.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price">Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                    </div>
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="duration_days">Duration (days) <span class="text-danger">*</span></label>
                                    <input type="number" min="1" class="form-control @error('duration_days') is-invalid @enderror" id="duration_days" name="duration_days" value="{{ old('duration_days') }}" required>
                                    @error('duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="type">Plan Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select a plan type</option>
                                <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('type') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="yearly" {{ old('type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                                <small class="form-text">Uncheck this to create the plan as inactive. Inactive plans are not visible to users.</small>
                            </div>
                        </div>

                        <div class="form-group text-end">
                            <a href="{{ route('club.subscription-plans') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-danger">Create Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection