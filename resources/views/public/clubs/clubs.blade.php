@extends('layouts.public')
@section('title', 'ProGymHub - Clubs')
@section('content')
<style>
    @media (max-width: 567px) {
        .coustem-search {
            flex-direction: column;
        }
    }
</style>
<main>
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 pt-70">
                            <h2>Clubs</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="gyms-section py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="filter-container bg-dark p-4 rounded shadow-sm">
                        <form action="{{ route('all_clubs') }}" method="GET" class="">
                            <div class="d-flex justify-content-between align-items-center coustem-search">
                                <div class="me-4 mb-2 mb-md-0">
                                    <label for="status-filter" class="text-white me-3">Filter by Status:</label>
                                    <select id="status-filter" name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="active" {{ request('status') == 'active' || !request('status') ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="under_maintenance" {{ request('status') == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                    </select>
                                </div>
                                <div class="  mt-3 mt-md-0">
                                    <label for="search-box" class="text-white me-3">Search:</label>
                                    <div class="input-group">
                                        <input type="text" id="search-box" name="search" class="form-control"
                                            placeholder="Search by club name..."
                                            value="{{ request('search') }}"
                                            oninput="filterClubsInstantly(this.value)">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div id="no-results-message" class="col-12 text-center" style="display: none;">
                    <p class="text-white">No clubs match your search criteria.</p>
                </div>
                @forelse($clubs as $club)
                <div class="col-md-6 col-lg-4 mb-4 club-card">
                    <div class="card shadow-sm h-100">
                        <div class="position-absolute" style="right: 15px; top: 15px; ">
                            @if($club->status == 'active')
                            <span class="badge bg-success text-white">Active</span>
                            @elseif($club->status == 'inactive')
                            <span class="badge bg-warning text-dark">Inactive</span>
                            @elseif($club->status == 'under_maintenance')
                            <span class="badge bg-info text-white">Under Maintenance</span>
                            @else
                            <span class="badge bg-secondary">{{ ucfirst($club->status) }}</span>
                            @endif
                        </div> <img src="{{ $club->logo ? asset('storage/' . $club->logo) : 'assets/img/gallery/team1.png' }}" class="card-img-top img-fluid" alt="{{ $club->name }}" style="max-height: 200px;">
                        <div class="card-body" style="background-color: black;">
                            <h5 class="card-title fw-semibold text-white text-center" style="font-size: 2rem;">{{ $club->name }}</h5>
                            <p class="card-text  text-white">{{ Str::words($club->bio, 15, '...') }}</p>
                            <p class="card-text text-white"><i class="fas fa-phone-alt"></i> {{ $club->phone }}</p>
                            <p class="card-text club-city text-white"><i class="fas fa-map-marker-alt"></i> {{ $club->city }}, {{ $club->country }}</p>
                            <a href="{{ route('club_details', $club->getEncodedId()) }}" class="btn btn-sm mt-2 text-center" style="display: flex; justify-content: center;">View Details</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center" id="empty-state-message">
                    <p class="text-white">No clubs available at the moment.</p>
                </div>
                @endforelse
            </div>
            <div class="row mt-5 mb-4">
                <div class="col-12 d-flex justify-content-center">
                    <div class="pagination-wrapper">
                        {{ $clubs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/club-filter.js') }}"></script>
@endpush