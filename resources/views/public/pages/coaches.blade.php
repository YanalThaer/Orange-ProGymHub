@extends('layouts.public')
@section('title', 'Our Coaches')
@section('content')

<main>
    <!--? Hero Start -->
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 pt-70 text-center">
                            <h2>Our Coaches</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- Hero End -->

    <!-- Coaches Section -->
    <section class="team-area pt-80 pb-50">
        <div class="container">            <!-- Search Form -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="section-tittle text-center mb-4">
                        <h2>Find a Coach</h2>
                    </div>
                    <form action="{{ route('all_coaches') }}" method="GET" class="search-form">
                        <div class="input-group" style="max-width: 600px; margin: 0 auto;">
                            <input type="text" name="search" class="form-control" placeholder="Search by coach name..." value="{{ $search ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Search</button>
                                @if(isset($search) && !empty($search))
                                    <a href="{{ route('all_coaches') }}" class="btn border-btn ml-2">Reset</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row">
                @forelse($coaches as $coach)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="single-team mb-30">
                            <div class="team-img">
                                @if($coach->profile_image)
                                    <img src="{{ asset('storage/'.$coach->profile_image) }}" alt="{{ $coach->name }}">
                                @else
                                    <img src="{{ asset('assets/img/gallery/team-default.jpg') }}" alt="{{ $coach->name }}">
                                @endif
                            </div>                            <div class="team-caption">
                                <h3><a href="{{ route('profile', $coach->encoded_id) }}">{{ $coach->name }}</a></h3>
                                @php 
                                    $specializations = is_string($coach->specializations) ? json_decode($coach->specializations, true) : $coach->specializations;
                                    $displaySpecializations = $specializations && is_array($specializations) ? implode(', ', array_slice($specializations, 0, 2)) : 'Fitness Coach';
                                @endphp
                                <p>{{ $displaySpecializations }}</p>
                                <span>{{ $coach->experience_years ? $coach->experience_years.' years experience' : '' }}</span>
                                @if($coach->club)
                                    <div class="club-info mt-2">
                                        <small>Works at <a href="{{ route('club_details', $coach->club->encoded_id) }}">{{ $coach->club->name }}</a></small>
                                    </div>
                                @endif
                                <div class="team-social mt-3">
                                    <a href="{{ route('profile', $coach->encoded_id) }}" class="btn border-btn2">View Profile</a>
                                </div>
                            </div>
                        </div>
                    </div>                @empty
                    <div class="col-12 text-center">
                        @if(isset($search) && !empty($search))
                            <p>No coaches found matching "{{ $search }}". <a href="{{ route('all_coaches') }}">View all coaches</a></p>
                        @else
                            <p>No coaches available at the moment.</p>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    <div class="pagination-area text-center">
                        {{ $coaches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
