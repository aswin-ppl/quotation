@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('libs/sweetalert2/dist/sweetalert2.min.css') }}">
    <!-- DataTables CSS (CDN fallback) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endsection
@section('content')
    @php
        $parent_title = 'User & Permissions';
        $page_title = 'Users';
    @endphp
    <div class="body-wrapper">
        <div class="container-fluid">
            <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '&gt;'" class="mb-3">
                <ol class="breadcrumb bg-primary-subtle px-3 py-2 rounded">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)" class="text-primary d-flex align-items-center"><i
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
                    @can('create-users')
                        <a href="{{ route('users.create') }}" class="btn btn-primary">Create New User</a>
                    @endcan
                </div>
            </div>
            <div class="bg-white p-3 rounded">

                <div class="table-responsive">

                    <table id="users-table" class="table text-nowrap mb-0 align-middle display nowrap" style="width:100%">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- DataTables will populate via AJAX --}}
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

    <!-- DataTables JS (CDN fallback) -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        // initialize DataTable
        $(function() {
            var table = $('#users-table').DataTable({
                processing: true,
                responsive: true,
                ajax: {
                    url: "{{ route('users.data') }}",
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.error('DataTables AJAX error', xhr, error, thrown);
                        var text = 'Failed to load users.';
                        try {
                            var j = JSON.parse(xhr.responseText);
                            if (j && j.error) text = j.error;
                        } catch (e) {}
                        toastr.error(text, 'Error');
                    }
                },
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'roles'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100]
            });
        });

        // swal
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();

            let form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                text: "This user will be permanently deleted!",
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
