@extends('layouts.app')
@section('title', 'ProGymHub - Confirm Password')
@section('content')
<div class="container-fluid">
    <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
            <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                <div class="d-flex align-items-center justify-content-center mb-3 text-center">
                    <h5 class="me-3" style="color: red;">ProGymHub</h5>
                    <img src="{{ asset('img/logo.png') }}" alt="Logo">
                </div>
                <h3 class="text-center mb-4">Confirm Password</h3>
                <p class="text-center mb-4">{{ __('Please confirm your password before continuing.') }}</p>
                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf
                    <div class="form-floating mb-4">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                        <label for="password">{{ __('Password') }}</label>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary py-3 w-100 mb-4">
                        {{ __('Confirm Password') }}
                    </button>
                    @if (Route::has('password.request'))
                        <p class="text-center mb-0">
                            <a class="btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
