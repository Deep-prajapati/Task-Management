@extends('layout.blank.app')

@section('title', 'OTP Verification | Task Management System')

@push('style')
    <link href="{{ asset('assets/css/auth-boxed.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="container mx-auto align-self-center">
    <div class="row">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
            <div class="card mt-3 mb-3">
                <div class="card-body">
                    <form action="{{ route('verify.otp', $mode) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3"> 
                                <h2>OTP Verification</h2>
                                <p>Enter the code for verification.</p>
                            </div>
                            
                            <div class="col-sm-2 col-3 ms-auto">
                                <div class="mb-3">
                                    <input type="text" class="form-control opt-input" placeholder="0" name="otp[0]" required>
                                </div>
                            </div>
                            <div class="col-sm-2 col-3">
                                <div class="mb-3">
                                    <input type="email" class="form-control opt-input" placeholder="0" name="otp[1]" required>
                                </div>
                            </div>
                            <div class="col-sm-2 col-3">
                                <div class="mb-3">
                                    <input type="text" class="form-control opt-input" placeholder="0" name="otp[2]" required>
                                </div>
                            </div>
                            <div class="col-sm-2 col-3 me-auto">
                                <div class="mb-3">
                                    <input type="text" class="form-control opt-input" placeholder="0" name="otp[3]" required>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <div class="mb-4">
                                    <button class="btn btn-secondary w-100">VERIFY</button>
                                </div>
                            </div>
                        </div>
                    </form>
                        
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <p class="mb-0">Didn't receive the code ? <a href="{{ route('resend.otp' , $data['email']) }}" class="text-warning">Resend</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/2-Step-Verification.js') }}"></script>
@endpush