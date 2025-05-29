@extends('layouts.app')
@section('title', 'ProGymHub - Verify Email')
@section('content')
<div class="container-fluid">
    <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
            <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                <div class="d-flex align-items-center justify-content-center mb-3 text-center">
                    <h5 class="me-3" style="color: red;">Welcome To ProGymHub</h5>
                    <img src="{{ asset('img/logo.png') }}" alt="Logo">
                </div>
                <h3 class="text-center mb-4">{{ __('Verify Your Email Address') }}</h3>
                @if (session('resent'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success! </strong>{{ __('A fresh verification link has been sent to your email address.') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <p class="text-center mb-3">{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                <p class="text-center mb-4">{{ __('If you did not receive the email') }},</p>
                <form class="d-inline text-center" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary py-3 w-100 mb-4">{{ __('Request a new verification link') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection