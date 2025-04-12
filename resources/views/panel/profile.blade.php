@extends('layout.structured.app')

@section('title', 'Dashboard | Task Management System')

@push('style')
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="middle-content container-xxl p-0">
    <div class="row layout-top-spacing">
        <div class="card mt-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                        <div class="col-lg-11 mx-auto">
                            <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="customFileEg1">Image <span class="text-danger">*</span></label>
                                                    <input type="file" class="form-control mb-3" id="customFileEg1" name="image" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="w-100" id="profilepreview">
                                                <div class="avatar avatar-xl">
                                                    <img alt="avatar" src="{{ asset($user->avtar)}}" onerror="this.src='{{asset('assets/img/defaulta_profile.jpg')}}'" class="rounded m-auto" id="viewer" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Name">Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control mb-3" id="Name" placeholder="Full Name"
                                                    name="name" value="{{ $user->name}}">
                                            </div>
                                        </div>
                                           

                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control mb-3" id="email"
                                                        placeholder="Enter your email" name="email" value="{{ $user->email}}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 mt-1">
                                            <div class="form-group text-start">
                                                <button type="submit" class="btn btn-secondary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <form action="{{ route('password.update') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control mb-3" id="password" name="password" placeholder="********" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirm Password</label>
                                                <input type="password" class="form-control mb-3" id="password_confirmation" name="password_confirmation"  placeholder="********" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <div class="form-group text-end">
                                                <button type="submit" class="btn btn-secondary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
    <script>
        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    console.log(e.target.result);
                    $('#' + viewer).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this, 'viewer');
        });
    </script>
@endpush