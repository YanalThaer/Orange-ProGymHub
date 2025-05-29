@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Coach Email') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <p>We have sent a verification code to <strong>{{ $coach->email }}</strong>.</p>
                    <p>Please enter the 6-digit code below to verify your email address.</p>

                    <form method="POST" action="{{ route('coach.verify.email', $coach->encoded_id) }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="verification_code" class="col-md-4 col-form-label text-md-right">{{ __('Verification Code') }}</label>

                            <div class="col-md-6">
                                <input id="verification_code" type="text" class="form-control @error('verification_code') is-invalid @enderror" name="verification_code" value="{{ old('verification_code') }}" required autocomplete="verification_code" autofocus maxlength="6">

                                @error('verification_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Verify Email') }}
                                </button>

                                <a href="{{ route('coach.resend.verification.code', $coach->encoded_id) }}" class="btn btn-link">
                                    {{ __('Resend Code') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection