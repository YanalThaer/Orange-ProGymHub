@extends('layouts.public')
@section('title', $coach->name . ' - Coach Profile')
@section('content')
<style>
    body {
        background-color: #121212;
        color: #ffffff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: medium;
    }
    .profile-container {
        max-width: 1000px;
        margin: 50px auto;
        padding: 0;
    }
    .profile-header {
        background: linear-gradient(to right, #1a1a1a, #2d2d2d);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 0, 0, 0.1);
    }
    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
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
    .profile-image {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #ff0000;
        box-shadow: 0 0 20px rgba(255, 0, 0, 0.3);
        margin-right: 30px;
    }
    .profile-name {

        font-weight: 700;
        margin-bottom: 5px;
        color: #ffffff;
    }
    .profile-title {

        color: #ff0000;
        margin-bottom: 15px;
        font-weight: 500;
    }
    .profile-meta {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
        color: #aaa;
    }
    .meta-item {
        margin-right: 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    .meta-item i {
        color: #ff0000;
        margin-right: 8px;

    }
    .profile-section {
        background-color: #1a1a1a;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .section-title {

        color: #ff0000;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(255, 0, 0, 0.2);
    }
    .bio-text {
        color: #ddd;
        line-height: 1.7;
        ;
    }
    .badge-specialization {
        background: linear-gradient(135deg, #ff0000, #cc0000);
        color: white;

        padding: 8px 15px;
        border-radius: 20px;
        margin-right: 10px;
        margin-bottom: 10px;
        display: inline-block;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    .badge-specialization:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
    }
    .certification-item {
        background-color: #2a2a2a;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 15px;
        border-left: 3px solid #ff0000;
        transition: all 0.3s ease;
    }
    .certification-item:hover {
        transform: translateX(5px);
        background-color: #333333;
    }
    .certification-name {
        color: white;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .certification-issuer {
        color: #aaa;

    }
    .social-links {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .social-link {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 10px;
        color: white;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .social-link:hover {
        background-color: #ff0000;
        transform: translateY(-5px);
        box-shadow: 0 5px 10px rgba(255, 0, 0, 0.3);
    }
    .working-hours {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    .day-block {
        background-color: #2a2a2a;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
        width: 48%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }
    .day-block:hover {
        background-color: #333;
        transform: translateY(-3px);
    }
    .day-name {
        color: white;
        font-weight: 600;
    }
    .day-hours {
        color: #ff6b6b;
    }
    .contact-btn {
        background: linear-gradient(45deg, #ff0000, #cc0000);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 30px;
        font-weight: 600;
        letter-spacing: 1px;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255, 0, 0, 0.2);
        text-decoration: none;
        margin-top: 10px;
    }
    .contact-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 0, 0, 0.4);
        color: white;
        text-decoration: none;
    }
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        .profile-image {
            margin: 0 auto 20px;
        }
        .day-block {
            width: 100%;
        }
        .profile-meta {
            justify-content: center;
        }
    }
    .club-badge {
        position: relative;
        display: inline-block;
        padding: 5px 15px;
        background-color: #333;
        border-radius: 20px;
        margin-left: 15px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 0, 0, 0.3);
    }
    .club-badge:hover {
        background-color: #444;
        transform: translateY(-2px);
    }
    .club-badge a {
        color: #fff;
        text-decoration: none;
    }
    .back-to-club {
        display: inline-flex;
        align-items: center;
        color: #ff6b6b;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .back-to-club i {
        margin-right: 8px;
        transition: transform 0.3s ease;
    }
    .back-to-club:hover {
        color: #ff0000;
        text-decoration: none;
    }
    .back-to-club:hover i {
        transform: translateX(-5px);
    }
</style>
<main>
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 pt-70">
                            <h2>{{ $coach->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container profile-container">
        <div class="profile-header d-flex align-items-center">
            <img src="{{ $coach->profile_image ? asset('storage/' . $coach->profile_image) : asset('assets/img/gallery/team1.png') }}"
                alt="{{ $coach->name }}" class="profile-image">
            <div>
                <h1 class="profile-name">{{ $coach->name }}</h1>
                <div class="d-flex align-items-center">
                    <p class="profile-title">Fitness Coach</p>
                    @if($coach->club)
                    <span class="club-badge">
                        <i class="fas fa-dumbbell me-1"></i>
                        <a href="{{ route('club_details', $coach->club->encoded_id) }}">{{ $coach->club->name }}</a>
                    </span>
                    @endif
                </div>
                <div class="profile-meta">
                    @if($coach->experience_years)
                    <div class="meta-item">
                        <i class="fas fa-star"></i>
                        <span>{{ $coach->experience_years }} Years Experience</span>
                    </div>
                    @endif
                    @if($coach->location)
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $coach->location }}</span>
                    </div>
                    @endif
                    @if($coach->gender)
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>{{ ucfirst($coach->gender) }}</span>
                    </div>
                    @endif
                    @if($coach->employment_type)
                    <div class="meta-item">
                        <i class="fas fa-briefcase"></i>
                        <span>{{ ucfirst(str_replace('_', ' ', $coach->employment_type)) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="profile-section">
            <h3 class="section-title">About Me</h3>
            <p class="bio-text">{{ $coach->bio }}</p>
        </div>
        @if($coach->specializations && (is_array($coach->specializations) || is_array(json_decode($coach->specializations, true))))
        <div class="profile-section">
            <h3 class="section-title">Specializations</h3>
            <div>
                @php
                $specializations = is_array($coach->specializations) ? $coach->specializations : json_decode($coach->specializations, true);
                @endphp
                @if(is_array($specializations))
                @foreach($specializations as $specialization)
                <span class="badge-specialization">{{ $specialization }}</span>
                @endforeach
                @endif
            </div>
        </div>
        @endif
        @if($coach->certifications && (is_array($coach->certifications) || is_array(json_decode($coach->certifications, true))))
        <div class="profile-section">
            <h3 class="section-title">Certifications</h3>
            @php
            $certifications = is_array($coach->certifications) ? $coach->certifications : json_decode($coach->certifications, true);
            @endphp

            @if(is_array($certifications))
            @foreach($certifications as $cert)
            <div class="certification-item">
                <div class="certification-name">{{ $cert['name'] ?? $cert }}</div>
                @if(isset($cert['issuer']))
                <div class="certification-issuer">{{ $cert['issuer'] }}</div>
                @endif
            </div>
            @endforeach
            @endif
        </div>
        @endif
        <div class="profile-section">
            <h3 class="section-title">Working Hours</h3>
            <div class="working-hours">
                @php
                $workingHours = $coach->getWorkingHoursArray();
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp
                @foreach($days as $day)
                @php
                $dayKey = strtolower($day); // to match keys like "monday", "tuesday", ...
                $hours = $workingHours[$dayKey] ?? [];
                $display = (is_array($hours) && count(array_filter($hours)))
                ? implode(', ', array_filter($hours))
                : 'Not Available';
                @endphp
                <div class="day-block" @if($display==='Not Available' ) style="opacity: 0.6" @endif>
                    <div class="day-name">{{ $day }}</div>
                    <div class="day-hours">{{ $display }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</main>
@endsection