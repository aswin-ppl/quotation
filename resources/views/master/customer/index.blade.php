@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('libs/sweetalert2/dist/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endsection
@section('content')
    @php
        $parent_title = 'Master';
        $page_title = 'Customers';
    @endphp
    <div class="body-wrapper">
        <div class="container-fluid">
            <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '&gt;'" class="mb-3">
                <ol class="breadcrumb bg-primary-subtle px-3 py-2 rounded">
                    <li class="breadcrumb-item">
                        <a href="/" class="text-primary d-flex align-items-center"><i
                                class="ti ti-home fs-4 mt-1"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)" class="text-primary">{{ $parent_title }}</a>
                    </li>
                    <li class="breadcrumb-item active text-primary " aria-current="page">
                        {{ $page_title }}
                    </li>
                </ol>
            </nav>

            <div class="d-flex flex-row-reverse my-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @can('create-customers')
                        <a href="{{ route('customers.create') }}" class="btn btn-primary">Create New Customer</a>
                    @endcan
                </div>
            </div>

            <div class="bg-white p-3 rounded">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <label for="status-filter" class="form-label mb-0">Filter: </label>
                        <select id="status-filter" class="form-select form-select-sm d-inline-block" style="width:auto">
                            <option value="all">All</option>
                            <option value="active">Active</option>
                            <option value="trashed">Trashed</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="customers-table" class="table text-nowrap mb-0 align-middle display nowrap"
                        style="width:100%">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Name</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Email</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Mobile</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">City</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Status</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Action</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will load rows via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/plugins/toastr-init.js') }}"></script>
    <script src="{{ asset('libs/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/forms/sweet-alert.init.js') }}"></script>

    <!-- DataTables (CDN fallback) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        // swal
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();

            let form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                text: "This product will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Initialize DataTable for customers with AJAX
        $(document).ready(function() {
            var table = $('#customers-table').DataTable({
                ajax: {
                    url: '{{ route('customers.data') }}',
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.error('Customers AJAX error', xhr.status, thrown, xhr.responseText);
                        toastr.error('Failed to load customers. See console for details.');
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'city',
                        name: 'city'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-trashed', data.trashed ? 'true' : 'false');
                },
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pageLength: 10,
            });

            // status filter
            $('#status-filter').on('change', function() {
                var val = $(this).val();
                if (val === 'all') {
                    table.rows().show();
                } else if (val === 'active') {
                    table.rows().every(function() {
                        var $row = $(this.node());
                        if ($row.data('trashed') === true || $row.data('trashed') === 'true') {
                            $(this.node()).hide();
                        } else {
                            $(this.node()).show();
                        }
                    });
                } else if (val === 'trashed') {
                    table.rows().every(function() {
                        var $row = $(this.node());
                        if ($row.data('trashed') === true || $row.data('trashed') === 'true') {
                            $(this.node()).show();
                        } else {
                            $(this.node()).hide();
                        }
                    });
                }
                table.draw(false);
            });
        });

        // toaster
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        @if (session('success'))
            toastr.success("{{ session('success') }}", "Success");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}", "Error");
        @endif
    </script>
@endsection
