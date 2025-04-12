@extends('layout.blank.app')

@section('title', 'Forget Password | Task Management System')

@push('style')
    <link href="{{ asset('assets/css/auth-boxed.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="container mx-auto align-self-center">
    <div class="row">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
            <div class="card mt-3 mb-3">
                <div class="card-body">
                    <form action="{{ route('forget.password.submit') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h2>Password Reset</h2>
                                <p>Enter your email to recover your password</p>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" placeholder="JohnDoe@example.com" name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-secondary w-100">RECOVER</button>
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
    <script src="{{ asset('assets/js/2-Step-Verification.js') }}"></script>
@endpush