@extends('layout.structured.app')

@section('title', 'Add Task | Task Management System')

@push('style')
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link rel="stylesheet" href="{{ asset('assets/sweetalerts2/sweetalerts2.css') }}">
    <link href="{{ asset('assets/sweetalerts2/custom-sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
@endpush

@section('content')
<div class="middle-content container-xxl p-0">
    <div class="row layout-top-spacing">
        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Task</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->

        <div class="row layout-spacing">
            <div class="col-lg-12">
                <div class="statbox widget box box-shadow">
                    <div class="widget-content widget-content-area">
                        <form action="{{ route('task.update' , $task->id) }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="exampleFormControlInput1">
                                            Task Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Task Name" value="{{ $task->title }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="exampleFormControlInput1">
                                            Start Date
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="start" class="form-control" id="start" required value="{{ $task->start_date }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="exampleFormControlInput1">
                                            Due Date
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="end" class="form-control" id="end" value="{{ $task->due_date }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="exampleFormControlTextarea1">
                                            Task Description
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" id="description" name="description" rows="5" placeholder="Task Description" required>{{ $task->description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    @if($task->status != 2)
                                        <button type="button" class="btn btn-danger ml-2 warning confirm">
                                            Cancel
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <!-- BEGIN THEME GLOBAL STYLE -->
    <script src="{{ asset('assets/sweetalerts2/sweetalerts2.min.js') }}"></script>
    <script src="{{ asset('assets/sweetalerts2/custom-sweetalert.js') }}"></script>
    <!-- END THEME GLOBAL STYLE -->   
    <script>
        document.querySelector('.widget-content .warning.confirm').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Do it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('task.cancel' , $task->id) }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (data) {
                            if (data.status == true) {
                                window.location.href = "{{ route('task.list') }}";
                            }
                        }
                    });
                }
            })
        })
    </script>
@endpush