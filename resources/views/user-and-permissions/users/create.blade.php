@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('libs/sweetalert2/dist/sweetalert2.min.css') }}">
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
                        <a href="{{ route('users.index') }}" class="text-primary">{{ $page_title }}</a>
                    </li>
                    <li class="breadcrumb-item active text-primary " aria-current="page">
                        Create
                    </li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Create User</h4>
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="row">

                            {{-- Name --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" autocomplete="name"
                                        autofocus>
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" autocomplete="email">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" autocomplete="new-password">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password-confirm"
                                        name="password_confirmation" autocomplete="new-password">
                                    <label for="password-confirm">Confirm Password <span class="text-danger">*</span></label>
                                </div>
                            </div>

                            {{-- User Roles --}}
                            <div class="col-md-12 row justify-content-center">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="mr-sm-2" for="inlineFormCustomSelect">Select <span class="text-danger">*</span></label>
                                        <select class="form-select mr-sm-2" name="roles" id="inlineFormCustomSelect"
                                            required>
                                            <option selected="" value="">Choose roles...</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"> {{ ucfirst($role->name) }}</option>
                                            @endforeach
                                        </select>
                                        @error('roles')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        Create User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/plugins/toastr-init.js') }}"></script>

    <script>
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
