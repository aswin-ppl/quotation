@extends('layouts.app')
@section('content')
    @php
        $parent_title = 'User & Permissions';
        $page_title = 'Roles & Permission';
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
                        <a href="{{ route('roles.index') }}" class="text-primary">{{ $page_title }}</a>
                    </li>
                    <li class="breadcrumb-item active text-primary " aria-current="page">
                        Edit
                    </li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Edit Role</h4>
                    <form action="{{ route('roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 row justify-content-center mb-3">
                                <div class="col-md-6">
                                    <label>Role Name</label>
                                    <input type="text" name="name" value="{{ $role->name }}" class="form-control"
                                        required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold mb-2">Permissions</label>

                                @foreach ($permissions as $module => $modulePermissions)
                                    <div class="card mb-3 border shadow-sm">
                                        <div class="card-header bg-light py-2">
                                            <strong>{{ $module }}</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach ($modulePermissions as $permission)
                                                    <div class="col-md-3 form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="permission-{{ $permission->id }}" name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="permission-{{ $permission->id }}">
                                                            {{ ucfirst(explode('-', $permission->name)[0]) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('roles.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-secondary">
                                        Update Role
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
