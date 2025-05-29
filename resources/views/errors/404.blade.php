@extends('layouts.public')

@section('title', 'Page Not Found')

@section('content')
<style>
    .error-container {
        text-align: center;
        padding: 100px 20px;
        max-width: 800px;
        margin: 0 auto;
        color: #fff;
    }

    .error-code {
        font-size: 150px;
        font-weight: 700;
        color: #ff0000;
        margin-bottom: 20px;
        line-height: 1;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .error-message {
        font-size: 36px;
        margin-bottom: 30px;
        color: #fff;
    }

    .error-description {
        font-size: 18px;
        margin-bottom: 40px;
        color: #ccc;
    }

    .error-action .btn {
        font-size: 18px;
        padding: 12px 30px;
        margin: 0 10px;
        transition: all 0.3s ease;
    }

    .error-action .btn-home {
        background-color: #ff0000;
        border: none;
    }

    .error-action .btn-home:hover {
        background-color: #cc0000;
        transform: translateY(-3px);
    }

    .error-image {
        max-width: 100%;
        height: auto;
        margin-bottom: 40px;
    }
</style>

<div class="slider-area2">
    <div class="slider-height2 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="hero-cap hero-cap2 pt-70">
                        <h2>Page Not Found</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="error-container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="error-code">404</div>
            <h2 class="error-message">Oops! Page Not Found</h2>
            <p class="error-description">
                The page you are looking for might have been removed, had its name changed,
                or is temporarily unavailable. Please check the URL or try one of the options below.
            </p>
            <img src="{{ asset('img/dumbbell-icon.png') }}" alt="Dumbbell" class="error-image" style="max-width: 150px;">
            <div class="error-action">
                <a href="{{ route('home') }}" class="btn btn-home btn-lg">
                    <i class="fas fa-home mr-2"></i> Go to Homepage
                </a>
                <a href="{{ route('all_clubs') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-dumbbell mr-2"></i> Browse Clubs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection