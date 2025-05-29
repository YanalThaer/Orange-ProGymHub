@extends('layouts.app')
@section('title', 'ProGymHub - Club Reset Password')

@section('content')
<style>
    body {
        background-color: #121212;
        height: 100%;
        margin: 0;
        overflow: hidden;
    }

    .reset-card {
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

    .form-floating>.form-control {
        background-color: #2b2b2b;
        color: white;
        border: 1px solid #444;
    }

    .form-floating>.form-control:focus {
        border-color: #ff0000;
        box-shadow: 0 0 0 0.2rem rgba(230, 57, 70, 0.25);
    }

    .form-floating>label {
        color: #ccc;
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label {
        color: #ff0000;
    }

    .btn-primary {
        background-color: #ff0000;
        border-color: #ff0000;
    }

    .btn-primary:hover {
        background-color: #e60000;
        border-color: #e60000;
    }

    a {
        text-decoration: none;
        color: #ff0000;
    }

    .welcome-text {
        color: #ff0000;
        font-weight: bold;
    }

    .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .logo-container img {
        max-height: 40px;
        width: auto;
    }
</style>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="reset-card shadow">
        <div class="logo-container">
            <h5 class="welcome-text mb-0">Welcome to ProGymHub</h5>
            <img src="{{ asset('img/logo.png') }}" alt="logo">
        </div>

        <div class="text-center mb-4">
            <h3 class="fw-bold">Club Password Reset</h3>
        </div>

        <form method="POST" action="{{ route('club.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-floating mb-3">
                <input id="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ $email ?? old('email') }}"
                    required autocomplete="email" autofocus
                    placeholder="name@example.com">
                <label for="email">Club Email Address</label>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-floating mb-3">
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password"
                    placeholder="Password">
                <label for="password">New Password</label>
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-floating mb-4">
                <input id="password-confirm" type="password"
                    class="form-control" name="password_confirmation"
                    required autocomplete="new-password"
                    placeholder="Confirm Password">
                <label for="password-confirm">Confirm Password</label>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary py-3">
                    Reset Password
                </button>
            </div>
        </form>

        <div class="text-center">
            <p class="text-white mb-0">
                Remember your password?
                <a href="{{ route('login') }}" class="fw-bold">Back to Login</a>
            </p>
        </div>
    </div>
</div>
@endsection