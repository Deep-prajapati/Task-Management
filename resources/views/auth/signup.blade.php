@extends('layout.blank.app')

@section('title', 'SignUp | Task Management System')

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
                        <form action="{{ route('register') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3"> 
                                    <h2>Sign Up</h2>
                                    <p>Enter your email and password to register</p>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Name
                                            <snap class="text-danger">*</snap>
                                        </label>
                                        <input type="text" class="form-control" placeholder="John Doe" name="name" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Email
                                            <snap class="text-danger">*</snap>
                                        </label>
                                        <input type="email" class="form-control" placeholder="JohnDoe@example.com" name="email" value="{{ old('email') }}" required>
                                        <snap class="text-danger" id="EmailError">This Email is Already Taken</snap>
                                        <snap class="text-danger" id="EmailValidError">Enter the valid email address</snap>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Password
                                            <snap class="text-danger">*</snap>
                                        </label>
                                        <input type="password" class="form-control" placeholder="********" name="password" required>
                                        <snap class="text-danger" id="PasswordError">Password should contain at least 8 characters</snap>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Confirm Password
                                            <snap class="text-danger">*</snap>
                                        </label>
                                        <input type="text" class="form-control" placeholder="********" name="password_confirmation" required>
                                        <snap class="text-danger" id="ConfirmPasswordError">Passwords do not match</snap>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input class="form-check-input me-3" type="checkbox" id="form-check-default" required>
                                            <label class="form-check-label" for="form-check-default">
                                                I agree the <a href="{{ route('terms.and.conditions') }}" class="text-primary" target="_blank">Terms and Conditions</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <button class="btn btn-secondary w-100">SIGN UP</button>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <div class="">
                                        <div class="seperator">
                                            <hr>
                                            <div class="seperator-text"> <span>Or continue with</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-center">
                                        <p class="mb-0">Already have an account ? <a href="{{ route('panel.login') }}" class="text-warning">Log In</a></p>
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

@push('script')
    <script>
        $(document).ready(function () {
            $('input[name="password"]').on('input', function () {
                if ($(this).val().length < 8) {
                    $('#PasswordError').show();
                } else {
                    $('#PasswordError').hide();
                }
            });
            $('input[name="password_confirmation"]').on('input', function () {
                if ($(this).val() !== $('input[name="password"]').val()) {
                    $('#ConfirmPasswordError').show();
                } else {
                    $('#ConfirmPasswordError').hide();
                }
            });

            $('input[name="email"]').on('keyup', function () {
                var email = $(this).val();
                $.ajax({
                    url: "{{ route('check.email') }}",
                    type: "POST",
                    data: {
                        email: email,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.status == true) 
                        {
                            $('#EmailValidError').hide();
                            if(response.exists == true)
                            {
                                $('#EmailError').show();
                            }else{
                                $('#EmailError').hide();
                            }
                        } else {
                            $('#EmailValidError').show();
                            $('#EmailError').hide();
                        }
                    }
                });
            });
        });
    </script>
@endpush