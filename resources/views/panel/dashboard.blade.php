@extends('layout.structured.app')

@section('title', 'Dashboard | Task Management System')

@push('style')
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="middle-content container-xxl p-0">
    <div class="row layout-top-spacing">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-header">
                        <div class="w-info">
                            <h6 class="value">Total Tasks</h6>
                        </div>
                    </div>

                    <div class="w-progress-stats"></div>

                    <div class="w-content">
                        <div class="w-info">
                            <p class="value"> {{ $data['total'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-header">
                        <div class="w-info">
                            <h6 class="value">Completed Tasks</h6>
                        </div>
                    </div>

                    <div class="w-progress-stats"></div>

                    <div class="w-content">
                        <div class="w-info">
                            <p class="value"> {{ $data['completed'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>  

        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-header">
                        <div class="w-info">
                            <h6 class="value">Active Tasks</h6>
                        </div>
                    </div>

                    <div class="w-progress-stats"></div>

                    <div class="w-content">
                        <div class="w-info">
                            <p class="value"> {{ $data['active'] }} 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-header">
                        <div class="w-info">
                            <h6 class="value">Cancel Tasks</h6>
                        </div>
                    </div>

                    <div class="w-progress-stats"></div>

                    <div class="w-content">
                        <div class="w-info">
                            <p class="value"> {{ $data['cancel'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    
@endpush