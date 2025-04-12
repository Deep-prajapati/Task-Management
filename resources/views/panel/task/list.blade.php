@extends('layout.structured.app')

@section('title', 'Task List | Task Management System')

@push('style')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatable/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/sweetalerts2/sweetalerts2.css') }}">

    <link href="{{ asset('assets/sweetalerts2/custom-sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatable/dt-global_style.css') }}">
    <!-- END PAGE LEVEL STYLES -->
@endpush

@section('content')
<div class="middle-content container-xxl p-0">
    <div class="row layout-top-spacing">
        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Task</a></li>
                    <li class="breadcrumb-item active" aria-current="page">List</li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->

        <div class="row layout-spacing">
            <div class="col-lg-12">
                <div class="statbox widget box box-shadow">
                    <form action="{{ url()->current() }}" class="form-horizontal" id="search-form" method="get">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-4 col-md-4 col-sm-12 col-12 p-4">
                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select name="status" class="form-control" onchange="SubmitForm()">
                                            <option disabled {{ !request()->has('status') || request()->get('status') == '' ? 'selected' : '' }}>All</option>
                                            <option value="0" {{ request()->has('status') && request()->get('status') == '0' ? 'selected' : '' }}>Pending</option>
                                            <option value="1" {{ request()->has('status') && request()->get('status') == '1' ? 'selected' : '' }}>Completed</option>
                                            <option value="2" {{ request()->has('status') && request()->get('status') == '2' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-12 col-12 p-4">
                                    <div class="form-group">
                                        <label for="">Start Date</label>
                                        <input type="date" class="form-control date" placeholder="date" name="start" value="{{ request()->has('start') ? request()->get('start') : '' }}" onchange="SubmitForm()">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-sm-12 col-12 p-4">
                                    <div class="form-group">
                                        <label for="">Due Date</label>
                                        <input type="date" class="form-control date" placeholder="date" name="due" value="{{ request()->has('due') ? request()->get('due') : '' }}" onchange="SubmitForm()">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="widget-content widget-content-area">
                        <table id="invoice-list" class="table dt-table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="checkbox-column"> Sl. </th>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($sl = 1)
                                @foreach($tasks as $task)
                                    <tr>
                                        <td class="checkbox-column"> {{ $sl }} </td>
                                        <td>
                                            <div class="d-flex">
                                                <p class="align-self-center mb-0 user-name"> {{ $task->title }} </p>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-info inv-status">
                                                {{ carbon\carbon::parse($task->start_date)->format('d-M-Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-info inv-status">
                                            {{ carbon\carbon::parse($task->due_date)->format('d-M-Y') }}
                                            </span>
                                        </td>
                                        <td title="Click To mark as complete this time">
                                            @if($task->status == 0)
                                            <a href="{{ route('task.status', $task->id) }}">
                                                <span class="badge outline-badge-info mb-2 me-4">
                                                    Pending
                                                </span>
                                            </a>
                                            @elseif($task->status == 1)
                                            <a href="{{ route('task.status', $task->id) }}">
                                                <span class="badge outline-badge-success mb-2 me-4">
                                                    Completed
                                                </span>
                                            </a>
                                            @else
                                            <a href="javascript:void(0);">
                                                <span class="badge outline-badge-danger mb-2 me-4">
                                                    Cancelled
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="badge badge-light-primary text-start me-2 action-edit edit-btn" href="{{ route('task.edit', $task->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                                </svg>
                                            </a>
                                            <a class="badge badge-light-primary text-start me-2 action-edit edit-btn" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ViewTask-{{ $task->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a class="badge badge-light-primary text-start me-2 warning confirm" href="javascript:void(0);" data-id="{{$task->id}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="ViewTask-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="ViewTaskTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="EditPriceModalCenterTitle">View Task</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-4">
                                                            <span>Title: </span>{{ $task->title }}
                                                        </div>
                                                        <br>
                                                        <div class="col-md-6 mb-4">
                                                            <span>Start: </span>{{ $task->start_date }}
                                                        </div>
                                                        <br>
                                                        <div class="col-md-6 mb-4">
                                                            <span>Due: </span>{{ $task->due_date }}
                                                        </div>
                                                        <br>
                                                        <div class="col-md-12 mb-4">
                                                            <span>Status: </span>
                                                            @if($task->status == 0)
                                                                <span class="badge outline-badge-info mb-2 me-4">
                                                                    Pending
                                                                </span>
                                                            @elseif($task->status == 1)
                                                                <span class="badge outline-badge-success mb-2 me-4">
                                                                    Completed
                                                                </span>
                                                            @else
                                                                <span class="badge outline-badge-danger mb-2 me-4">
                                                                    Cancelled
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <br>
                                                        <div class="col-md-12 mb-4">
                                                            <span>Description: </span>{{ $task->description }}
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal-footer">
                                                    <button type="button" class="btn" data-bs-dismiss="modal">
                                                        <i class="flaticon-cancel-12"></i>
                                                        Discard
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Edit Modal -->
                                    @php($sl++)
                                @endforeach
                            </tbody>
                        </table>
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
    <script src="{{ asset('assets/js/datatable/datatables.js') }}"></script>
    <!-- END THEME GLOBAL STYLE --> 

    <script>
        document.querySelector('.widget-content .warning.confirm').addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('task.delete') }}",
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
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

        $(document).ready(function() 
        {
            $('#invoice-list').DataTable({
                responsive: true,
                columnDefs: [{
                    targets: 0,
                    width: "30px",
                    className: "",
                    orderable: !1,
                }],
                "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                "oLanguage": {
                    "oPaginate": {
                        "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    "sInfo": "Showing page _PAGE_ of _PAGES_",
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "Search...",
                    "sLengthMenu": "Results :  _MENU_",
                },
                "lengthMenu": [5, 10, 20, 50],
                "pageLength": 10
            });
        });

        function SubmitForm() {
            $('#search-form').submit();
        }
    </script>
@endpush