@extends('layouts.app')
@section('title', 'ProGymHub - Email Reset Password')
@section('content')
<style>
    body {
        background-color: #000;
        color: #fff;
    }

    .login-container {
        background-color: #1c1c1c;
        border-radius: 10px;
        padding: 2rem;
    }

    .form-control {
        background-color: #2c2c2c;
        color: #fff;
        border: 1px solid #444;
    }

    .form-control:focus {
        background-color: #2c2c2c;
        color: #fff;
        border-color: #f00;
        box-shadow: none;
    }

    .btn-primary {
        background-color: #f00;
        border: none;
    }

    .btn-primary:hover {
        background-color: #c00;
    }

    a {
        color: #f00;
    }

    a:hover {
        text-decoration: underline;
    }

    input::placeholder {
        color: white;
    }

    label {
        color: white !important;
    }
</style>
<div class="container-fluid">
    <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
            <div class=" rounded p-4 p-sm-5 my-4 mx-3" style="background-color: #1f1f1f;">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/image.png') }}" alt="Logo" style="width: 300px; height: 100px;" class="me-2">
                    <h3 class="fw-bold" style="color: red; font-size: 3rem;">Login</h3>
                </div>
                <h3 class="text-center mb-4">Reset Password</h3>
                @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-floating mb-4">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                        <label for="email">Email address</label>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary py-3 w-100 mb-4">
                        Send Password Reset Link
                    </button>
                </form>
                <p class="text-center mb-0">Remember your password? <a href="{{ route('login') }}">Back to Login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection