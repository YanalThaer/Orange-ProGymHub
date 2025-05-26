@extends('layouts.dashboard')
@section('title', 'Club - Edit Subscription Plans')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-header bg-secondary">
                    <h4 class="mb-0">Edit Subscription Plan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('club.subscription-plans.update', $plan->getEncodedId()) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control bg-secondary text-white border-0 @error('name') is-invalid @enderror" 
                                id="name" name="name" 
                                value="{{ old('name', $plan->name) }}" 
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-secondary text-white border-0">$</span>
                                    <input 
                                        type="number" step="0.01" min="0" 
                                        class="form-control bg-secondary text-white border-0 @error('price') is-invalid @enderror" 
                                        id="price" name="price" 
                                        value="{{ old('price', $plan->price) }}" 
                                        required
                                    >
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="duration_days" class="form-label">Duration (days) <span class="text-danger">*</span></label>
                                <input 
                                    type="number" min="1" 
                                    class="form-control bg-secondary text-white border-0 @error('duration_days') is-invalid @enderror" 
                                    id="duration_days" name="duration_days" 
                                    value="{{ old('duration_days', $plan->duration_days) }}" 
                                    required
                                >
                                @error('duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Plan Type <span class="text-danger">*</span></label>
                            <select 
                                class="form-select bg-secondary text-white border-0 @error('type') is-invalid @enderror" 
                                id="type" name="type" 
                                required
                            >
                                <option value="" disabled>Select a plan type</option>
                                @foreach(['monthly','quarterly','yearly','custom'] as $type)
                                    <option 
                                        value="{{ $type }}" 
                                        {{ old('type', $plan->type) == $type ? 'selected' : '' }}
                                    >{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="is_active" name="is_active" 
                                value="1" 
                                {{ old('is_active', $plan->is_active) ? 'checked' : '' }}
                            >
                            <label class="form-check-label text-white" for="is_active">Active</label>
                            <div class="form-text text-muted">Uncheck to make the plan inactive.</div>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('club.subscription-plans') }}" class="btn btn-outline-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-danger">Update Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
