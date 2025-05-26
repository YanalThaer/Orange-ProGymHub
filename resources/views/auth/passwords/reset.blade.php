@extends('layouts.app')
@section('title', 'ProGymHub - Reset Password')
@section('content')
<div class="container-fluid">
    <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
            <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                <div class="d-flex align-items-center justify-content-center mb-3 text-center">
                    <h5 class="me-3" style="color: red;">Welcome The ProGymHub</h5>
                    <img src="{{ asset('img/logo.png') }}" alt="logo">
                </div>
                <h3 class="text-center mb-4">Reset Password</h3>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-floating mb-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                        <label for="email">Email address</label>                        
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                        <label for="password">New Password</label>                   
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-floating mb-4">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                        <label for="password-confirm">Confirm Password</label>
                    </div>
                    <button type="submit" class="btn btn-primary py-3 w-100 mb-4">
                        Reset Password
                    </button>
                </form>              
                <p class="text-center mb-0">Remember your password? <a href="{{ route('login') }}">Back to Login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
