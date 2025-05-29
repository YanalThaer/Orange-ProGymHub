@extends('layouts.public')
@section('title', 'ProGymHub - Coaches')
@section('content')
<style>
    .team-img img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
</style>
<main>
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 pt-70">
                            <h2>Coaches</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="team-area pt-80 pb-50">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12">
                    <div class="filter-container bg-dark p-4 rounded shadow-sm">
                        <form action="{{ route('all_coaches') }}" method="GET" class="">
                            <div class="d-flex justify-content-between align-items-center coustem-search">
                                <div class="  mt-3 mt-md-0">
                                    <label for="search-box" class="text-white me-3">Search:</label>
                                    <div class="input-group">
                                        <input type="text" id="search-box" name="search" class="form-control"
                                            placeholder="Search by coach name..."
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
            <div class="row">
                @forelse($coaches as $coach)
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-team mb-30" style="border: 1px solid #ccc; padding: 10px;">
                        <div class="team-img">
                            @if($coach->profile_image)
                            <img src="{{ asset('storage/' . $coach->profile_image) }}" alt="{{ $coach->name }}">
                            @else
                            <img src="{{ asset('assets/img/gallery/team-default.jpg') }}" alt="{{ $coach->name }}">
                            @endif
                        </div>
                        <div class="team-caption text-white" style="font-size: 2rem;">
                            <h3 class="text-center mt-3"><a href="{{ route('profile', $coach->encoded_id) }}" class="text-white ">{{ $coach->name }}</a></h3>
                            @php
                            $specializations = is_string($coach->specializations) ? json_decode($coach->specializations, true) : $coach->specializations;
                            $displaySpecializations = $specializations && is_array($specializations) ? implode(', ', array_slice($specializations, 0, 2)) : 'Fitness Coach';
                            @endphp
                            <p class="text-white">{{ $displaySpecializations }}</p>
                            <span>{{ $coach->experience_years ? $coach->experience_years . ' years experience' : '' }}</span>
                            @if($coach->club)
                            <div class="club-info mt-2">
                                <small>Works at <a
                                        href="{{ route('club_details', $coach->club->encoded_id) }}">{{ $coach->club->name }}</a></small>
                            </div>
                            @endif
                            <div class="team-social mt-3">
                                <a href="{{ route('profile', $coach->encoded_id) }}" class="btn border-btn2">View
                                    Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    @if(isset($search) && !empty($search))
                    <p>No coaches found matching "{{ $search }}". <a href="{{ route('all_coaches') }}">View all
                            coaches</a></p>
                    @else
                    <p>No coaches available at the moment.</p>
                    @endif
                </div>
                @endforelse
            </div>
            <div class="row mt-5 mb-4">
                <div class="col-12 d-flex justify-content-center">
                    <div class="pagination-wrapper">
                        {{ $coaches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection