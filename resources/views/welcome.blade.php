
@extends('layouts.public')
@section('title', 'ProGymHome')
@section('content')


<style>
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
    
    .swiper-button-next:after, .swiper-button-prev:after {
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
    
    .cat-cap h5 a:hover {
        color: #ff0000 !important;
        text-decoration: none;
    }
    </style>

<main>
    <!--? slider Area Start-->
    <div class="slider-area position-relative">
        <div class="slider-active">
            <!-- Single Slider -->
            <div class="single-slider slider-height d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-9 col-lg-9 col-md-10">
                            <div class="hero__caption">
                                <span data-animation="fadeInLeft" data-delay="0.1s">Your gym is closer than you think</span>
                                <h1 data-animation="fadeInLeft" data-delay="0.4s">Pro Gym</h1>
                                <a href="{{ route('all_clubs') }}" class="border-btn hero-btn" data-animation="fadeInLeft" data-delay="0.8s">Our Club</a>
                            </div>
                        </div>
                    </div>
                </div>          
            </div>
        </div>
    </div>
    <!-- slider Area End-->
    <!-- Traning categories Start -->
    <!-- <section class="traning-categories black-bg">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="single-topic text-center mb-30">
                        <div class="topic-img">
                            <img src="assets/img/gallery/cat1.png" alt="">
                            <div class="topic-content-box">
                                <div class="topic-content">
                                    <h3>Personal traning</h3>
                                    <p>You’ll look at graphs and charts in Task One, how to approach the task and <br> the language needed for a successful answer.</p>
                                    <a href="courses.html" class="border-btn">View Clubs</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="single-topic text-center mb-30">
                        <div class="topic-img">
                            <img src="assets/img/gallery/cat2.png" alt="">
                            <div class="topic-content-box">
                                <div class="topic-content">
                                    <h3>Group traning</h3>
                                    <p>You’ll look at graphs and charts in Task One, how to approach the task and <br> the language needed for a successful answer.</p>
                                    <a href="courses.html" class="btn">View pricing</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- Traning categories End-->
    <!--? Team -->
    <section class="team-area fix">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-tittle text-center mb-55 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s">
                        <h2>Our Top Coaches</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($coaches as $coach)
                <div class="col-lg-4 col-md-6">
                    <div class="single-cat text-center mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".{{ $loop->index + 2 }}s">
                        <div class="cat-icon">
                            @if($coach->profile_image)
                                <img src="{{ asset('storage/'.$coach->profile_image) }}" alt="{{ $coach->name }}">
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
                            {{ $coach->experience_years ? '• '.$coach->experience_years.' years experience' : '' }}</p>
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
    <!-- Services End -->
    <!--? Gallery Area Start -->
    <!-- <div class="gallery-area section-padding30 ">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                    <div class="box snake mb-30">
                        <div class="gallery-img big-img" style="background-image: url(assets/img/gallery/gallery1.png);"></div>
                        <div class="overlay">
                            <div class="overlay-content">
                                <h3>Muscle gaining </h3>
                                <a href="gallery.html"><i class="ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                    <div class="box snake mb-30">
                        <div class="gallery-img big-img" style="background-image: url(assets/img/gallery/gallery2.png);"></div>
                        <div class="overlay">
                            <div class="overlay-content">
                                <h3>Muscle gaining </h3>
                                <a href="gallery.html"><i class="ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                    <div class="box snake mb-30">
                        <div class="gallery-img big-img" style="background-image: url(assets/img/gallery/gallery3.png);"></div>
                        <div class="overlay">
                            <div class="overlay-content">
                                <h3>Muscle gaining </h3>
                                <a href="gallery.html"><i class="ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="box snake mb-30">
                        <div class="gallery-img big-img" style="background-image: url(assets/img/gallery/gallery4.png);"></div>
                        <div class="overlay">
                            <div class="overlay-content">
                                <h3>Muscle gaining </h3>
                                <a href="gallery.html"><i class="ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="box snake mb-30">
                        <div class="gallery-img big-img" style="background-image: url(assets/img/gallery/gallery5.png);"></div>
                        <div class="overlay">
                            <div class="overlay-content">
                                <h3>Muscle gaining </h3>
                                <a href="gallery.html"><i class="ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                    <div class="box snake mb-30">
                        <div class="gallery-img big-img" style="background-image: url(assets/img/gallery/gallery6.png);"></div>
                        <div class="overlay">
                            <div class="overlay-content">
                                <h3>Muscle gaining </h3>
                                <a href="gallery.html"><i class="ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Gallery Area End -->
    <!-- Courses area start -->
    {{-- <section class="pricing-area section-padding40 fix">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-tittle text-center mb-55 wow fadeInUp" data-wow-duration="2s" data-wow-delay=".1s">
                        <h2>Pricing</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="properties mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">
                        <div class="properties__card">
                            <div class="about-icon">
                                <img src="assets/img/icon/price.svg" alt="">
                            </div>
                            <div class="properties__caption">
                                <span class="month">6 month</span>
                                <p class="mb-25">$30/m  <span>(Single class)</span></p>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Free riding </p>
                                    </div>
                                </div>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Unlimited equipments</p>
                                    </div>
                                </div>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Personal trainer</p>
                                    </div>
                                </div>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Weight losing classes</p>
                                    </div>
                                </div>
                                <div class="single-features mb-20">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Month to mouth</p>
                                    </div>
                                </div>
                                <a href="#" class="border-btn border-btn2">Join Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="properties mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">
                        <div class="properties__card">
                            <div class="about-icon">
                                <img src="assets/img/icon/price.svg" alt="">
                            </div>
                            <div class="properties__caption">
                                <span class="month">6 month</span>
                                <p class="mb-25">$30/m  <span>(Single class)</span></p>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Free riding </p>
                                    </div>
                                </div>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Unlimited equipments</p>
                                    </div>
                                </div>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Personal trainer</p>
                                    </div>
                                </div>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Weight losing classes</p>
                                    </div>
                                </div>
                                <div class="single-features mb-20">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Month to mouth</p>
                                    </div>
                                </div>
                                <a href="#" class="border-btn border-btn2">Join Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="properties mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">
                        <div class="properties__card">
                            <div class="about-icon">
                                <img src="assets/img/icon/price.svg" alt="">
                            </div>
                            <div class="properties__caption">
                                <span class="month">6 month</span>
                                <p class="mb-25">$30/m  <span>(Single class)</span></p>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Free riding </p>
                                    </div>
                                </div>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Unlimited equipments</p>
                                    </div>
                                </div>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Personal trainer</p>
                                    </div>
                                </div>
                                <div class="single-features">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Weight losing classes</p>
                                    </div>
                                </div>
                                <div class="single-features mb-20">
                                    <div class="features-icon">
                                        <img src="assets/img/icon/check.svg" alt="">
                                    </div>
                                    <div class="features-caption">
                                        <p>Month to mouth</p>
                                    </div>
                                </div>
                                <a href="#" class="border-btn border-btn2">Join Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- Courses area End -->
    <!--? About Area-2 Start -->
    <section class="team-area fix" style="background-color: #f8f9fa; padding: 80px 0 60px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-tittle text-center mb-55 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s">
                        <h2 class="position-relative">
                            Our Clubs
                            <span class="d-block" style="height: 4px; width: 80px; background-color: #ff0000; margin: 15px auto 0;"></span>
                        </h2>
                        <p class="mt-3 text-muted">Find the perfect gym location for your fitness journey</p>
                    </div>
                </div>
            </div>
    
            <!-- Swiper -->
            <div class="swiper team-slider" style="padding-bottom: 50px;">
                <div class="swiper-wrapper">
                    @forelse($clubs as $club)
                    <div class="swiper-slide">
                        <div class="single-cat text-center">
                            <div class="cat-icon">
                                @if($club->logo)
                                    <img src="{{ asset('storage/'.$club->logo) }}" alt="{{ $club->name }}">
                                @else
                                    <img src="assets/img/gallery/team{{ ($loop->index % 3) + 1 }}.png" alt="{{ $club->name }}">
                                @endif
                            </div>
                            <div class="cat-cap mt-3">
                                <h5><a href="{{ route('club_details', $club->encoded_id) }}">{{ $club->name }}</a></h5>
                                <p>{{ $club->city }}, {{ $club->country }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Default slides if no clubs are found -->
                    <div class="swiper-slide">
                        <div class="single-cat text-center">
                            <div class="cat-icon position-relative">
                                <div style="height: 230px; overflow: hidden; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                    <img src="assets/img/gallery/team1.png" alt="Club" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.6); padding: 5px;" class="d-none d-md-block">
                                        <p class="text-white mb-0 small"><i class="fas fa-map-marker-alt mr-1"></i> Sample Location</p>
                                    </div>
                                </div>
                            </div>
                            <div class="cat-cap mt-3">
                                <h5><a href="#" class="text-danger">Sample Club 1</a></h5>
                                <p class="d-md-none">Sample Location</p>
                                <a href="#" class="btn btn-sm btn-outline-danger mt-2">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="single-cat text-center">
                            <div class="cat-icon position-relative">
                                <div style="height: 230px; overflow: hidden; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                    <img src="assets/img/gallery/team2.png" alt="Club" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.6); padding: 5px;" class="d-none d-md-block">
                                        <p class="text-white mb-0 small"><i class="fas fa-map-marker-alt mr-1"></i> Sample Location</p>
                                    </div>
                                </div>
                            </div>
                            <div class="cat-cap mt-3">
                                <h5><a href="#" class="text-danger">Sample Club 2</a></h5>
                                <p class="d-md-none">Sample Location</p>
                                <a href="#" class="btn btn-sm btn-outline-danger mt-2">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="single-cat text-center">
                            <div class="cat-icon position-relative">
                                <div style="height: 230px; overflow: hidden; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                    <img src="assets/img/gallery/team3.png" alt="Club" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.6); padding: 5px;" class="d-none d-md-block">
                                        <p class="text-white mb-0 small"><i class="fas fa-map-marker-alt mr-1"></i> Sample Location</p>
                                    </div>
                                </div>
                            </div>
                            <div class="cat-cap mt-3">
                                <h5><a href="#" class="text-danger">Sample Club 3</a></h5>
                                <p class="d-md-none">Sample Location</p>
                                <a href="#" class="btn btn-sm btn-outline-danger mt-2">View Details</a>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
    
                <!-- If you want pagination or arrows -->
                <div class="swiper-pagination text-danger"></div>
                <div class="swiper-button-prev text-danger"></div>
                <div class="swiper-button-next text-danger"></div>
            </div>
            
            <div class="row">
                <div class="col-12 text-center mt-20 mb-50">
                    <a href="{{ route('all_clubs') }}" class="border-btn">Show All Clubs</a>
                </div>
            </div>
        </div>
    </section>
    







    <!-- About Area End -->
    <!--? Blog Area Start -->
    <!-- <section class="home-blog-area pt-10 pb-50">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9 col-sm-10">
                    <div class="section-tittle text-center mb-100 wow fadeInUp" data-wow-duration="2s" data-wow-delay=".2s">
                        <h2>From Blog</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="home-blog-single mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">
                        <div class="blog-img-cap">
                            <div class="blog-img">
                                <img src="assets/img/gallery/blog1.png" alt="">
                            </div>
                            <div class="blog-cap">
                                <span>Gym & Fitness</span>
                                <h3><a href="blog_details.html">Your Antibiotic One Day To 10 Day Options</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="home-blog-single mb-30 wow fadeInUp" data-wow-duration="2s" data-wow-delay=".6s">
                        <div class="blog-img-cap">
                            <div class="blog-img">
                                <img src="assets/img/gallery/blog2.png" alt="">
                            </div>
                            <div class="blog-cap">
                                <span>Gym & Fitness</span>
                                <h3><a href="blog_details.html">Your Antibiotic One Day To 10 Day Options</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- Blog Area End -->
    <!--? video_start -->
    {{-- <div class="video-area section-bg2 d-flex align-items-center"  data-background="assets/img/gallery/video-bg.png">
        <div class="container">
            <div class="video-wrap position-relative">
                <div class="video-icon" >
                    <a class="popup-video btn-icon" href="https://www.youtube.com/watch?v=up68UAfH0d0"><i class="fas fa-play"></i></a>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- video_end -->
    <!-- ? services-area -->
    <section class="services-area">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8">
                    <div class="single-services mb-40 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s">
                        <div class="features-icon">
                            <img src="assets/img/icon/icon1.png" alt="">
                        </div>
                        <div class="features-caption">
                            <h3>Location</h3>
                            <p>You’ll look at graphs and charts in Task One, how to approach </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8">
                    <div class="single-services mb-40 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">
                        <div class="features-icon">
                            <img src="assets/img/icon/icon1.png" alt="">
                        </div>
                        <div class="features-caption">
                            <h3>Phone</h3>
                            <p>(90) 277 278 2566</p>
                            <p>  (78) 267 256 2578</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8">
                    <div class="single-services mb-40 wow fadeInUp" data-wow-duration="2s" data-wow-delay=".4s">
                        <div class="features-icon">
                            <img src="assets/img/icon/icon1.png" alt="">
                        </div>
                        <div class="features-caption">
                            <h3>Email</h3>
                            <p>jacson767@gmail.com</p>
                            <p>contact56@zacsion.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection