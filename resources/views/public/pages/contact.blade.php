@extends('layouts.public')
@section('title', 'ProGymHub - Contact Us')
@section('content')
<style>
    .form-control {
        color: white !important;
    }
</style>
<main>
    <div class="slider-area2">
        <div class="slider-height2 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap hero-cap2 pt-70">
                            <h2>Contact Us</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title text-white">Get in Touch</h2>
                </div>
                <div class="col-lg-8">
                    @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                    @endif
                    <form class="form-contact contact_form" action="{{ route('contact.send') }}" method="post">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control w-100 @error('message') is-invalid @enderror" name="message" id="message" cols="30" rows="9" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" placeholder="Enter Message">{{ old('message') }}</textarea>
                                    @error('message')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control @error('name') is-invalid @enderror" name="name" id="name" type="text" value="{{ old('name') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" placeholder="Enter your name">
                                    @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control @error('email') is-invalid @enderror" name="email" id="email" type="email" value="{{ old('email') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'" placeholder="Email">
                                    @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control @error('subject') is-invalid @enderror" name="subject" id="subject" type="text" value="{{ old('subject') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Subject'" placeholder="Enter Subject">
                                    @error('subject')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="button button-contactForm boxed-btn">Send</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-3 offset-lg-1">
                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-home"></i></span>
                        <div class="media-body">
                            <h3 class="text-white">Jordan, Amman.</h3>
                            <p class="text-white">Aljofa</p>
                        </div>
                    </div>
                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-tablet"></i></span>
                        <div class="media-body">
                            <h3 class="text-white">077 957 6855</h3>
                        </div>
                    </div>
                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-email"></i></span>
                        <div class="media-body">
                            <h3 class="text-white">yanalthaer33@gmail.com</h3>
                            <p class="text-white">Send us your query anytime!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="services-area">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8">
                    <div class="single-services mb-40">
                        <div class="features-icon">
                            <img src="img/favicon.png" style="width: 100px;" alt="">
                        </div>
                        <div class="features-caption">
                            <h3>Subscription</h3>
                            <p>Club subscription directly with the admin </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8">
                    <div class="single-services mb-40">
                        <div class="features-icon">
                            <img src="img/favicon.png" style="width: 100px;" alt="">
                        </div>
                        <div class="features-caption">
                            <h3>Phone</h3>
                            <p>077 957 6855</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8">
                    <div class="single-services mb-40">
                        <div class="features-icon">
                            <img src="img/favicon.png" style="width: 100px;" alt="">
                        </div>
                        <div class="features-caption">
                            <h3>Email</h3>
                            <p>yanalthaer33@gmail.com</p>
                            <p>admin@example.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection