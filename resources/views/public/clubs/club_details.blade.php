@extends('layouts.public')
@section('title', $club->name . ' - Details')
@section('content')
<style>
    body {
        background-color: #121212 !important;
        color: white !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        font-size: medium !important;
    }
    .zoom-on-hover {
        transition: transform 0.3s ease;
    }
    .zoom-on-hover:hover {
        transform: scale(1.05);
    }
    .gym-details-container {
        background-color: #1f1f1f;
        border-radius: 20px;
        padding: 40px;
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        height: auto;
    }
    .gym-details-image {
        flex: 1 1 350px;
        max-width: 400px;
        border-radius: 15px;
        object-fit: cover;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    }
    @media (max-width: 768px) {
        .gym-details-image {
            max-width: 703px;
        }
    }
    @media (max-width: 576px) {
        .gym-details-image {
            max-width: 100%;
        }
    }
    .gym-details-info {
        flex: 2 1 400px;
    }
    .gym-details-title {

        font-weight: bold !important;
        color: #ff0000;
        margin-bottom: 20px;
    }
    .gym-details-description {
        line-height: 1.7;
        color: #ccc;
        margin-bottom: 20px;
    }
    .coaches-section {
        background-color: #1a1a1a;
        padding: 100px 0;
        margin-top: 60px;
        position: relative;
        overflow: hidden;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff0000' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .coaches-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #ff0000, #ff6b6b, #ff0000);
        background-size: 200% 100%;
        animation: gradientShift 8s infinite linear;
    }
    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }
    .coach-card {
        background: linear-gradient(145deg, #252525, #2d2d2d);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(255, 0, 0, 0.1);
        position: relative;
        animation: fadeInUp 0.6s ease-out forwards;
        animation-delay: calc(var(--coach-index, 0) * 0.1s);
        opacity: 0;
    }
    .coach-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 20px 40px rgba(255, 0, 0, 0.25);
        border-color: rgba(255, 0, 0, 0.4);
    }
    .coach-img {
        height: 270px;
        object-fit: cover;
        transition: all 0.5s ease;
        position: relative;
    }
    .coach-img-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .coach-card:hover .coach-img-overlay {
        opacity: 1;
    }
    .coach-card:hover .coach-img {
        transform: scale(1.05);
    }
    .coach-info {
        padding: 28px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        position: relative;
        z-index: 2;
        background: #2a2a2a;
        border-top: 4px solid;
        border-image: linear-gradient(90deg, #ff0000, transparent) 1;
    }
    .coach-name {
        font-weight: 600;
        margin-bottom: 10px;
        color: white;
        border-bottom: 2px solid rgba(255, 0, 0, 0.3);
        padding-bottom: 10px;
    }
    .specialization-badge {
        background: linear-gradient(135deg, #ff0000, #cc0000);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-block;
        margin-right: 6px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(255, 0, 0, 0.2);
        font-weight: 500;
    }
    .specialization-badge:hover {
        background: linear-gradient(135deg, #e60000, #b30000);
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 8px rgba(255, 0, 0, 0.3);
    }
    .coach-bio {
        font-size: large;
        color: #bbb;
        margin: 15px 0;
        line-height: 1.6;
        flex-grow: 1;
    }
    .view-profile-btn {
        background-color: transparent;
        color: #ff0000;
        border: 2px solid #ff0000;
        padding: 10px 24px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: inline-block;
        text-align: center;
        text-decoration: none !important;
        margin-top: auto;
        position: relative;
        overflow: hidden;
        z-index: 1;
        letter-spacing: 0.5px;
    }
    .view-profile-btn::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background-color: #ff0000;
        transition: all 0.4s ease;
        z-index: -1;
    }
    .view-profile-btn:hover {
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 7px 15px rgba(255, 0, 0, 0.35);
    }
    .view-profile-btn:hover::after {
        width: 100%;
    }
    .view-profile-btn i {
        transition: transform 0.3s ease;
    }

    .view-profile-btn:hover i {
        transform: translateX(3px);
    }
    .coaches-title,
    .section-title {
        font-weight: 700;
        color: white;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        position: relative;
        display: inline-block;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff0000, #ff6b6b);
        border-radius: 2px;
    }
    .section-subtitle {
        color: #aaa;
        margin-top: 15px;
    }
    .social-icons {
        display: flex;
        justify-content: center;
        margin-top: 15px;
    }
    .social-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 5px;
        color: white;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .social-icon:hover {
        background: #ff0000;
        transform: translateY(-3px);
    }
    .coach-experience {
        color: #ff6b6b;
        margin-top: 3px;
        margin-bottom: 12px;
    }
    @media (max-width: 992px) {
        .coach-card {
            margin-bottom: 30px;
        }
    }
    @media (max-width: 768px) {
        .coaches-section {
            padding: 60px 0;
        }

        .section-title {}
    }
    @media (max-width: 576px) {
        .coach-img {
            height: 240px;
        }
    }
    .gym-price-box {
        background-color: #2a2a2a;
        padding: 20px;
        border-left: 5px solid #ff0000;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .gym-price-box h5 {
        margin-bottom: 10px !important;
        color: #ff0000;
    }
    .btn-gym-book {
        background-color: #ff0000;
        border: none;
        padding: 15px 32px;
        border-radius: 4px;
        color: white;
        transition: background-color 0.3s;
    }
    .btn-gym-book:hover {
        background-color: #cc0000;
    }
    li {

        color: #ccc !important;
    }
    .feature-icon {
        color: #ff0000;
        margin-right: 8px;
    }
    .status-badge {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 20px;
        font-weight: bold;
        margin-bottom: 15px;
    }
    .status-active {
        background-color: #28a745;
        color: white;
    }
    .status-inactive {
        background-color: #ffc107;
        color: #212529;
    }
    .status-maintenance {
        background-color: #17a2b8;
        color: white;
    }
    .breadcrumb {
        background-color: #1a1a1a !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px !important;
    }
    .breadcrumb-item+.breadcrumb-item::before {
        color: #ff0000;
        content: ">";
        font-weight: bold;
        padding: 0 10px;
    }
    .breadcrumb-item a {
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .breadcrumb-item a:hover {
        color: #ff0000 !important;
    }
    .breadcrumb-item.active {
        font-weight: 600;
    }
    .breadcrumb i {
        margin-right: 6px;
    }
    ul {
        font-size: medium;
    }
    .subscription-plan {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 3px solid #ff0000;
    }
    .subscription-plan:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }
    .subscription-plan .btn-danger {
        background-color: #ff0000;
        border: none;
        transition: all 0.3s ease;
    }
    .subscription-plan .btn-danger:hover {
        background-color: #cc0000;
        transform: scale(1.05);
    }
    .day-item {
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        margin-right: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    .day-item i {
        margin-right: 7px;
    }
    .day-active {
        background: linear-gradient(145deg, rgba(255, 0, 0, 0.1), rgba(255, 0, 0, 0.2));
        color: #ff6666;
        border: 1px solid rgba(255, 0, 0, 0.4);
    }
    .day-active:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(255, 0, 0, 0.3);
    }
    .day-inactive {
        background-color: rgba(128, 128, 128, 0.1);
        color: #999;
        border: 1px solid rgba(128, 128, 128, 0.3);
        text-decoration: line-through;
        opacity: 0.7;
    }
</style>
<main>
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 pt-70">
                            <h2>{{ $club->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="gym-details-container">
        <img src="{{ $club->logo ? asset('storage/' . $club->logo) : asset('assets/img/gallery/team1.png') }}"
            alt="{{ $club->name }}" class="gym-details-image">
        <div class="gym-details-info">
            <h2 class="gym-details-title">{{ $club->name }}</h2>
            <div>
                @if($club->status == 'active')
                <span class="status-badge status-active">Active</span>
                @elseif($club->status == 'inactive')
                <span class="status-badge status-inactive">Inactive</span>
                @elseif($club->status == 'under_maintenance')
                <span class="status-badge status-maintenance">Under Maintenance</span>
                @else
                <span class="status-badge">{{ ucfirst($club->status) }}</span>
                @endif
            </div>
            <p class="gym-details-description">
                {{ $club->bio }}
            </p>
            @if($club->description)
            <p class="gym-details-description">
                {{ $club->description }}
            </p>
            @endif <div class="gym-price-box">
                <h5>Club Details</h5>
                <ul class="mb-0" style="">
                    <li><i class="fas fa-map-marker-alt feature-icon"></i> {{ $club->address }}, {{ $club->city }},
                        {{ $club->country }}
                    </li>
                    <li><i class="fas fa-phone feature-icon"></i> {{ $club->phone }}</li>
                    <li><i class="fas fa-envelope feature-icon"></i> {{ $club->email }}</li>
                    @if($club->website)
                    <li><i class="fas fa-globe feature-icon"></i> <a href="{{ $club->website }}" target="_blank"
                            class="text-white">{{ $club->website }}</a></li>
                    @endif
                    @if($club->established_date)
                    <li><i class="fas fa-calendar-alt feature-icon"></i> Established:
                        {{ $club->established_date->format('F Y') }}
                    </li>
                    @endif
                    @if($club->capacity)
                    <li><i class="fas fa-users feature-icon"></i> Capacity: {{ $club->capacity }} people</li>
                    @endif @if($club->open_time && $club->close_time)
                    <li><i class="far fa-clock feature-icon"></i> Hours: {{ $club->open_time->format('H:i') }} -
                        {{ $club->close_time->format('H:i') }}
                    </li>
                    @endif
                    @if(!empty($club->working_days))
                    <li>
                        <i class="fas fa-calendar-week feature-icon"></i> Working Days:
                        <div class="d-flex flex-wrap mt-2 working-days-container">
                            @php
                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            $dayIcons = [
                            'monday' => 'business-time',
                            'tuesday' => 'briefcase',
                            'wednesday' => 'chart-line',
                            'thursday' => 'tasks',
                            'friday' => 'coffee',
                            'saturday' => 'glass-cheers',
                            'sunday' => 'church'
                            ];
                            $activeDays = [];
                            foreach ($club->working_days as $index => $value) {
                            if ($value == "1") {
                            $activeDays[] = $days[$index];
                            }
                            }
                            @endphp
                            @foreach($days as $day)
                            <div
                                class="day-item mb-2 mr-2 {{ in_array($day, $activeDays) ? 'day-active' : 'day-inactive' }}">
                                <i class="fas fa-{{ $dayIcons[$day] }}"></i>
                                {{ ucfirst($day) }}
                            </div>
                            @endforeach
                        </div>
                    </li>
                    @endif
                    @if(!empty($club->emergency_contact))
                    <li><i class="fas fa-ambulance feature-icon"></i> Emergency: {{ $club->emergency_contact }}</li>
                    @endif
                </ul>
            </div>
            <div class="gym-price-box">
                <h5>Amenities</h5>
                <ul class="mb-0">
                    @if($club->has_wifi)
                    <li><i class="fas fa-wifi me-2"></i> Free WiFi</li>
                    @endif
                    @if($club->has_parking)
                    <li><i class="fas fa-car me-2"></i> Parking Available</li>
                    @endif
                    @if($club->has_showers)
                    <li><i class="fas fa-shower me-2"></i> Showers</li>
                    @endif
                    @if($club->has_lockers)
                    <li><i class="fas fa-lock me-2"></i> Lockers</li>
                    @endif
                    @if($club->has_pool)
                    <li><i class="fas fa-swimming-pool me-2"></i> Swimming Pool</li>
                    @endif
                    @if($club->has_sauna)
                    <li><i class="fas fa-hot-tub me-2"></i> Sauna</li>
                    @endif
                </ul>
            </div>
            @auth
            @include('public.clubs._user_subscriptions')
            @endauth
            @if($club->subscriptionPlans && $club->subscriptionPlans->isNotEmpty())
            <div class="gym-price-box">
                <h5>Subscription Plans</h5>
                <div class="subscription-plans-container mt-3">
                    @foreach($club->subscriptionPlans as $plan)
                    <div class="subscription-plan mb-3 p-3" style="background-color: #333; border-radius: 8px;">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-white mb-2">{{ $plan->name }}</h6>
                            <span class="badge bg-danger">{{ ucfirst($plan->type) }}</span>
                        </div>
                        @if($plan->description)
                        <p class="small text-light mb-2">{{ $plan->description }}</p>
                        @endif <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <span class="text-white font-weight-bold">{{ number_format($plan->price, 2) }}
                                    JOD</span>
                                <span class="text-muted small"> / {{ $plan->duration_days }} days</span>
                            </div>
                            @auth
                            @php
                            $isSubscribed = Auth::user()->subscriptions()
                            ->where('club_id', $club->id)
                            ->where('plan_id', $plan->id)
                            ->where('end_date', '>=', now())
                            ->exists();
                            @endphp @if($isSubscribed)
                            <span class="badge bg-success py-2 px-3">Already Subscribed</span>
                            @elseif($club->status !== 'active')
                            <span class="badge bg-secondary py-2 px-3">Club Temporarily Unavailable</span>
                            @elseif(!$plan->is_active)
                            <span class="badge bg-secondary py-2 px-3">Currently Unavailable</span>
                            @else
                            <a href="{{ route('payment', ['plan_id' => $plan->getEncodedId(), 'club_id' => $club->getEncodedId()]) }}"
                                class="btn btn-sm btn-danger">Subscribe</a>
                            @endif
                            @else
                            @if($club->status !== 'active')
                            <span class="badge bg-secondary py-2 px-3">Club Temporarily Unavailable</span>
                            @elseif($plan->is_active)
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="showLoginAlert({{ $plan->id }})">Subscribe</button>
                            @else
                            <span class="badge bg-secondary py-2 px-3">Currently Unavailable</span>
                            @endif
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @if(!empty($club->social_media))
            <div class="gym-price-box">
                <h5>Connect With Us</h5>
                <div class="d-flex justify-content-around mt-2">
                    @foreach($club->social_media as $platform => $url)
                    @if(!empty($url))
                    <a href="{{ $url }}" target="_blank" class="me-3 text-white">
                        @if($platform == 'facebook')
                        <i class="fab fa-facebook-f fa-lg"></i>
                        @elseif($platform == 'instagram')
                        <i class="fab fa-instagram fa-lg"></i>
                        @elseif($platform == 'twitter')
                        <i class="fab fa-twitter fa-lg"></i>
                        @elseif($platform == 'linkedin')
                        <i class="fab fa-linkedin-in fa-lg"></i>
                        @elseif($platform == 'youtube')
                        <i class="fab fa-youtube fa-lg"></i>
                        @else
                        <i class="fas fa-link fa-lg"></i>
                        @endif
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div> @if($club->coaches && $club->coaches->count() > 0)
    <section class="coaches-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <div class="section-title-wrapper">
                        <h2 class="section-title">Expert Coaches</h2>
                        <p class="section-subtitle">Meet our dedicated team of fitness professionals ready to help you
                            reach your goals</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($club->coaches as $index => $coach)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="coach-card" style="--coach-index: {{ $index }}">
                        <div class="position-relative">
                            <img src="{{ $coach->profile_image ? asset('storage/' . $coach->profile_image) : asset('assets/img/gallery/team1.png') }}"
                                class="coach-img w-100" alt="{{ $coach->name }}">
                            <div class="coach-img-overlay"></div>
                        </div>
                        <div class="coach-info">
                            <h5 class="mb-1  text-white">{{ $coach->name }}</h5>
                            @if($coach->experience_years)
                            <div class="coach-experience">
                                <i class="fas fa-star me-1"></i> {{ $coach->experience_years }} Years Experience
                            </div>
                            @endif
                            @if($coach->specializations)
                            <div class="mb-3">
                                @php
                                $specializations = is_array($coach->specializations) ? $coach->specializations : json_decode($coach->specializations, true);
                                @endphp
                                @if(is_array($specializations))
                                @foreach($specializations as $specialization)
                                <span class="specialization-badge">{{ $specialization }}</span>
                                @endforeach
                                @endif
                            </div>
                            @endif
                            <p class="coach-bio text-white">{{ Str::limit($coach->bio, 100) }}</p>
                            <div class="text-center">
                                <a href="{{ route('profile', $coach->encoded_id) }}" class="view-profile-btn">
                                    View Full Profile <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showLoginAlert(planId) {
        Swal.fire({
            title: 'Login Required',
            text: 'Please login to subscribe to this plan',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#ff0000',
            cancelButtonColor: '#333',
            confirmButtonText: 'Login Now',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('login') }}?redirect_after_login=1&plan_id=" + planId + "&club_id={{ $club->getEncodedId() }}";
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        const animateOnScroll = function() {
            const coachCards = document.querySelectorAll('.coach-card');

            coachCards.forEach(card => {
                const cardPosition = card.getBoundingClientRect();

                // If the card is in viewport
                if (cardPosition.top < window.innerHeight - 100) {
                    card.style.opacity = '1';
                }
            });
        };
        animateOnScroll();
        window.addEventListener('scroll', animateOnScroll);
    });
</script>
@endsection