@extends('layout.structured.app')

@section('title', 'Add Task | Task Management System')

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
                        <form action="{{ route('task.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="exampleFormControlInput1">
                                            Task Name
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Task Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="exampleFormControlInput1">
                                            Start Date
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="start" class="form-control" id="start" required value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="exampleFormControlInput1">
                                            Due Date
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="end" class="form-control" id="end" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="exampleFormControlTextarea1">
                                            Task Description
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" id="description" name="description" rows="5" placeholder="Task Description" required></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
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