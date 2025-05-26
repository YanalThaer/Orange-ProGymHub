@extends('layouts.dashboard')

@section('title', 'Club - Search')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-10 col-lg-8">
            <div class="bg-dark rounded h-100 p-4 mb-4">
                <h2 class="mb-4 text-white">Search in Your Club</h2>
                <p class="text-white">Search for coaches, members, or subscription plans by name or other details.</p>

                <form action="{{ route('club.search.results') }}" method="post" class="mb-3">
                    @csrf
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search_term" 
                            class="form-control bg-black text-white border-white" 
                            placeholder="Enter name, email, phone, etc." 
                            required 
                            minlength="2" 
                            value="{{ old('search_term') }}"
                        >
                        <select 
                            name="search_type" 
                            class="form-select bg-black text-white border-white"
                        >
                            <option value="all" {{ old('search_type') == 'all' ? 'selected' : '' }}>Everything</option>
                            <option value="coaches" {{ old('search_type') == 'coaches' ? 'selected' : '' }}>Coaches only</option>
                            <option value="users" {{ old('search_type') == 'users' ? 'selected' : '' }}>Members only</option>
                            <option value="subscription_plans" {{ old('search_type') == 'subscription_plans' ? 'selected' : '' }}>Subscription plans only</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-2"></i> Search
                        </button>
                    </div>
                    @error('search_term')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-control.bg-black, .form-select.bg-black {
        background-color: #121212 !important;
        color: #fff !important;
        border: 1px solid #444 !important;
    }
    .form-control.bg-black::placeholder {
        color: #bbb;
    }
    .form-control.bg-black:focus, .form-select.bg-black:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25) !important;
        color: #fff !important;
        background-color: #121212 !important;
    }
</style>
@endpush
@endsection
