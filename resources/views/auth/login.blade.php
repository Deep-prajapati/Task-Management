@extends('layout.blank.app')

@section('title', 'LogIn | Task Management System')

@push('style')
    <link href="{{ asset('assets/css/auth-cover.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <div class="container mx-auto align-self-center">
        <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto top-0 start-0 text-center justify-content-center flex-column">
                <div class="auth-cover-bg-image"></div>
                <div class="auth-overlay"></div>
                <div class="auth-cover">
                    <div class="position-relative">
                        <img src="{{ asset('assets/img/auth-cover.svg') }}" alt="auth-img">
                        <h2 class="mt-5 text-white font-weight-bolder px-2">Join the community of expert developers</h2>
                        <p class="text-white px-2">It is easy to setup with great experience.</p>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center ms-lg-auto me-lg-0 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('login.submit') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h2>Sign In</h2>
                                    <p>Enter your email and password to login</p>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Email
                                            <snap class="text-danger">*</snap>
                                        </label>
                                        <input type="email" class="form-control" placeholder="JohnDoe@example.com" name="email" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label">
                                            Password
                                            <snap class="text-danger">*</snap>
                                        </label>
                                        <input type="password" class="form-control" placeholder="********" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input class="form-check-input me-3" type="checkbox" id="form-check-default">
                                            <label class="form-check-label" for="form-check-default">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6  text-end">
                                    <div class="mb-3">
                                        <div class="form-check form-check-primary form-check-inline">
                                            <a href="{{ route('forget.password') }}" class="text-primary">Forget Password?</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="mb-4">
                                        <button class="btn btn-secondary w-100">
                                            SIGN IN
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-4">
                                    <div class="">
                                        <div class="seperator">
                                            <hr>
                                            <div class="seperator-text"> 
                                                <span>
                                                    Or continue with
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="text-center">
                                        <p class="mb-0">Don't have an account ? <a href="{{ route('register') }}" class="text-warning">Sign Up</a></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection