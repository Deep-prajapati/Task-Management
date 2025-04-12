@extends('layout.blank.app')

@section('title', 'Reset Password | Task Management System')

@push('style')
    <link href="{{ asset('assets/css/auth-boxed.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/auth-cover.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="container mx-auto align-self-center">
    <div class="row">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
            <div class="card mt-3 mb-3">
                <div class="card-body">
                    <form action="{{ route('reset.password.submit') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h2>Create New Password</h2>
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
                                <div class="mb-4">
                                    <button class="btn btn-secondary w-100 mt-3">Reset</button>
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
        });
    </script>
@endpush