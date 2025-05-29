@extends('layouts.public')
@section('title', 'ProGymHub - Home')
@section('content')
<style>
    .section-underline {
        height: 4px;
        width: 80px;
        background-color: #ff0000;
    }

    .club-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: black !important;
    }

    .club-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .club-image img {
        width: 300px;
        height: 300px;
        object-fit: cover;
    }

    .swiper-pagination-bullet {
        background-color: #ff0000 !important;
        opacity: 0.5;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
        transform: scale(1.2);
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #ff0000 !important;
    }

    @media (max-width: 767px) {
        .section-title {
            font-size: 1.5rem;
        }

        .club-image img {
            width: 80px;
            height: 80px;
        }
    }

    .swiper-pagination-bullet {
        background-color: red !important;
        width: 12px;
        height: 12px;
        opacity: 0.5;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
        background-color: red !important;
        transform: scale(1.2);
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 18px;
    }

    .swiper-slide {
        transition: all 0.4s ease;
    }

    .swiper-slide-active {
        transform: scale(1.05);
        z-index: 2;
    }

    .single-cat {
        transition: all 0.3s ease;
    }

    .single-cat:hover {
        transform: translateY(-5px);
    }

    .img-design-coach {
        width: 300px;
        height: 300px;
    }

    .cat-cap h5 a:hover {
        color: #ff0000 !important;
        text-decoration: none;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 51px !important;
    }
</style>
<main>
    <div class="slider-area position-relative">
        <div class="slider-active">
            <div class="single-slider slider-height d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-9 col-lg-9 col-md-10">
                            <div class="hero__caption">
                                <span data-animation="fadeInLeft" data-delay="0.1s">Your gym is closer than you
                                    think</span>
                                <h1 style="font-size: 50px;" data-animation="fadeInLeft" data-delay="0.4s">Pro Gym HUB</h1>
                                <a href="{{ route('all_clubs') }}" class="border-btn hero-btn"
                                    data-animation="fadeInLeft" data-delay="0.8s">Our Clubs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="team-area fix">
        <div class="container">
            <div class="row ">
                <div class="col-xl-12">
                    <div class="section-tittle text-center mb-55 wow fadeInUp" data-wow-duration="1s"
                        data-wow-delay=".1s">
                        <h2>What we Offer</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center d-flex">
                <div class="col-lg-4 col-md-6">
                    <div class="single-cat text-center mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">
                        <div class="cat-icon">
                            <img src="assets/img/gallery/team1.png" alt="">
                        </div>
                        <div class="cat-cap">
                            <h5><a href="services.html">Find the Nearest Gym</a></h5>
                            <p>Browse gyms by location and sign up for the one that suits you best</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-cat text-center mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">
                        <div class="cat-icon">
                            <img src="assets/img/gallery/team2.png" alt="">
                        </div>
                        <div class="cat-cap">
                            <h5><a href="services.html">Choose the Right Coach</a></h5>
                            <p>Explore coach profiles, compare their experience, and pick the one that fits your goals
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-cat text-center mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">
                        <div class="cat-icon">
                            <img src="assets/img/gallery/team3.png" alt="">
                        </div>
                        <div class="cat-cap">
                            <h5><a href="services.html">Easy & Secure Payment</a></h5>
                            <p>Book your spot and pay online with confidence — then get started right away</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="clubs-area py-5">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-tittle text-center mb-55 wow fadeInUp" data-wow-duration="1s"
                        data-wow-delay=".1s">
                        <h2>Our Top Clubs</h2>
                    </div>
                </div>
            </div>
            <div class="swiper clubs-slider pb-4">
                <div class="swiper-wrapper">
                    @forelse($clubs as $club)
                    <div class="swiper-slide">
                        <div class="club-card text-center shadow-sm p-3 rounded bg-white h-100">
                            <div class="club-image mb-3">
                                @if($club->logo)
                                <img src="{{ asset('storage/' . $club->logo) }}" class="img-fluid "
                                    alt="{{ $club->name }}">
                                @else
                                <img src="assets/img/gallery/team{{ ($loop->index % 3) + 1 }}.png" class="img-fluid "
                                    alt="{{ $club->name }}">
                                @endif
                            </div>
                            <div class="club-info">
                                <h5 class="mb-1">
                                    <a href="{{ route('club_details', $club->encoded_id) }}"
                                        class="text-white text-decoration-none"
                                        style="font-size:2rem ;">{{ $club->name }}</a>
                                </h5>
                                <p class="text-white  ">{{ $club->city }}, {{ $club->country }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="swiper-slide">
                        <div class="club-card text-center shadow-sm p-3 rounded bg-white h-100">
                            <div class="club-image mb-3">
                                <img src="assets/img/gallery/team1.png" class="img-fluid rounded-circle"
                                    alt="Sample Club">
                            </div>
                            <div class="club-info">
                                <h5 class="mb-1 text-danger">Sample Club</h5>
                                <p class="text-muted small">Sample Location</p>
                                <a href="#" class="btn btn-sm btn-outline-danger mt-2">View Details</a>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
            <div class="col-12 text-center mt-20 mb-50">
                <a href="{{ route('all_clubs') }}" class="border-btn">Show All Clubs</a>
            </div>
        </div>
    </section>
    <section class="team-area fix">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-tittle text-center mb-55 wow fadeInUp" data-wow-duration="1s"
                        data-wow-delay=".1s">
                        <h2>Our Top Coaches</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($coaches as $coach)
                <div class="col-xl-4 col-md-6">
                    <div class="single-cat text-center mb-30 wow fadeInUp" data-wow-duration="1s"
                        data-wow-delay=".{{ $loop->index + 2 }}s">
                        <div class="cat-icon">
                            @if($coach->profile_image)
                            <img src="{{ asset('storage/' . $coach->profile_image) }}" alt="{{ $coach->name }}"
                                class="img-fluid img-design-coach">
                            @else
                            <img src="assets/img/gallery/team{{ ($loop->index % 3) + 1 }}.png" alt="{{ $coach->name }}">
                            @endif
                        </div>
                        <div class="cat-cap">
                            <h5><a href="{{ route('profile', $coach->encoded_id) }}">{{ $coach->name }}</a></h5>
                            <p>
                                @php
                                $specializations = is_string($coach->specializations) ? json_decode($coach->specializations, true) : $coach->specializations;
                                $displaySpecializations = $specializations && is_array($specializations) ? implode(', ', array_slice($specializations, 0, 2)) : 'Fitness Coach';
                                @endphp
                                {{ $displaySpecializations }}
                                {{ $coach->experience_years ? '• ' . $coach->experience_years . ' years experience' : '' }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="col-12 text-center mt-20 mb-50">
                    <a href="{{ route('all_coaches') }}" class="border-btn">Show All Coaches</a>
                </div>
            </div>
        </div>
    </section>
    <section class="services-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 mb-2">
                    <div class="section-tittle text-center mb-55 wow fadeInUp" data-wow-duration="1s"
                        data-wow-delay=".1s">
                        <h2>Contact Us</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8">
                    <div class="single-services mb-40 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s">
                        <div class="features-icon">
                            <img src="img/favicon.png" style="width: 100px;" alt="">
                        </div>
                        <div class="features-caption">
                            <h3>Subscription</h3>
                            <p>Club subscription directly with the admin </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8">
                    <div class="single-services mb-40 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">
                        <div class="features-icon">
                            <img src="img/favicon.png" style="width: 100px;" alt="">
                        </div>
                        <div class="features-caption">
                            <h3>Phone</h3>
                            <p>077 957 6855</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8">
                    <div class="single-services mb-40 wow fadeInUp" data-wow-duration="2s" data-wow-delay=".4s">
                        <div class="features-icon">
                            <img src="img/favicon.png" style="width: 100px;" alt="">
                        </div>
                        <div class="features-caption">
                            <h3>Email</h3>
                            <p>yanalthaer33@gmail.com</p>
                            <p>admin@example.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.clubs-slider', {
            loop: true,
            slidesPerView: 4,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                576: {
                    slidesPerView: 2,
                    spaceBetween: 15
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                992: {
                    slidesPerView: 4,
                    spaceBetween: 30
                }
            }
        });
    </script>
</main>
@endsection