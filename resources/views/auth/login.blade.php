@extends('layouts.app')
@section('title', 'ProGymHub - Login')
@section('content')
<style>
    body {
        background-color: #121212;

        height: 100%;
        margin: 0;
        overflow: hidden;

    }

    .login-card {
        background-color: #1f1f1f;
        border: none;
        padding: 2rem;
        border-radius: 10px;
        color: white;
        width: 100%;
        max-width: 500px;
    }

    .form-control {
        background-color: #2b2b2b;
        color: white;
        border: 1px solid #444;
    }

    .form-control:focus {
        border-color: #ff0000;
        box-shadow: 0 0 0 0.2rem rgba(230, 57, 70, 0.25);
    }

    .btn-primary {
        background-color: #ff0000;
        border-color: #ff0000;
    }

    .btn-primary:hover {
        background-color: #ff0000;
        border-color: #ff0000;
    }

    a {
        text-decoration: none;
        color: #ff0000;
    }
</style>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-card shadow">
        <div class="text-center mb-4">
            <img src="{{ asset('img/image.png') }}" alt="Logo" style="width: 300px; height: 100px;" class="me-2">
            <h3 class="fw-bold" style="color: red; font-size: 3rem;">Login</h3>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            @if(isset($redirectParams) && !empty($redirectParams))
            @foreach($redirectParams as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            @endif

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autofocus>

                @error('email')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password" required>

                @error('password')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember"
                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">
                    Login
                </button>
            </div>

            <div class="text-center">
                @if (Route::has('password.request'))
                <a class="text-light d-block mb-2" href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a>
                @endif

                <p class="text-white">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="fw-bold">Register here</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection