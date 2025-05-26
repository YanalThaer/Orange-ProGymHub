@extends('layouts.app')
@section('title', 'ProGymHub - Club Password Reset Request')
@section('content')
<div class="container-fluid">
    <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
            <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                <div class="d-flex align-items-center justify-content-center mb-3 text-center">
                    <h5 class="me-3" style="color: red;">ProGymHub</h5>
                    <img src="{{ asset('img/logo.png') }}" alt="logo">
                </div>
                <h3 class="text-center mb-4">Club Password Reset</h3>               
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('club.password.email') }}">
                    @csrf
                    <div class="form-floating mb-4">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your club email">
                        <label for="email">Club Email address</label>                     
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
