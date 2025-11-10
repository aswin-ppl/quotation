@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-selection__arrow {
            display: none !important;
        }

        #status {
            height: 58px;
        }
    </style>
@endsection

@section('content')
    @php
        $parent_title = 'Master';
        $page_title = 'Customer';
    @endphp

    <div class="body-wrapper">
        <div class="container-fluid">
            <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '>'" class="mb-3">
                <ol class="breadcrumb bg-primary-subtle px-3 py-2 rounded">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)" class="text-primary d-flex align-items-center">
                            <i class="ti ti-home fs-4 mt-1"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)" class="text-primary">{{ $parent_title }}</a>
                    </li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">
                        <a href="{{ route('customers.index') }}" class="text-primary">{{ $page_title }}</a>
                    </li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Edit</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Edit Customer</h4>

                    <form id="customerForm" method="POST" action="{{ route('customers.update', $customer->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Name --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $customer->name) }}" placeholder="Customer Name" required>
                                    <label for="name">Name</label>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $customer->email) }}" placeholder="example@gmail.com">
                                    <label for="email">Email</label>
                                </div>
                            </div>

                            {{-- Mobile --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="mobile" name="mobile"
                                        value="{{ old('mobile', $customer->mobile) }}" placeholder="Mobile Number">
                                    <label for="mobile">Mobile</label>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-4">
                                <select class="form-select" id="status" name="status">
                                    <option value="active"
                                        {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive"
                                        {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- State --}}
                            <div class="col-md-6 mb-4">
                                <label for="state">State</label>
                                <select id="state" name="state_id" class="form-select">
                                    @if ($state)
                                        <option value="{{ $state->id }}" selected>{{ $state->name }}</option>
                                    @else
                                        <option value="" selected>Select State</option>
                                    @endif
                                </select>
                            </div>

                            {{-- District --}}
                            <div class="col-md-6 mb-4">
                                <label for="district">District</label>
                                <select id="district" name="district_id" class="form-select">
                                    @if ($district)
                                        <option value="{{ $district->id }}" selected>{{ $district->name }}</option>
                                    @else
                                        <option value="" selected>Select District</option>
                                    @endif
                                </select>
                            </div>

                            {{-- City --}}
                            <div class="col-md-6 mb-4">
                                <label for="city">City</label>
                                <select id="city" name="city_id" class="form-select">
                                    @if ($city)
                                        <option value="{{ $city->id }}" selected>{{ $city->name }}</option>
                                    @else
                                        <option value="" selected>Select City</option>
                                    @endif
                                </select>
                            </div>

                            {{-- Pincode --}}
                            <div class="col-md-6 mb-4">
                                <label for="pincode">Pincode</label>
                                <select id="pincode" name="pincode_id" class="form-select">
                                    @if ($pincode)
                                        <option value="{{ $pincode->id }}" selected>{{ $pincode->code }}</option>
                                    @else
                                        <option value="" selected>Select Pincode</option>
                                    @endif
                                </select>
                            </div>

                            {{-- Address 1 --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" name="address_line_1" placeholder="Address 1..">{{ old('address_line_1', $address->address_line_1 ?? '') }}</textarea>
                                </div>
                            </div>

                            {{-- Address 2 --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" name="address_line_2" placeholder="Address 2..">{{ old('address_line_2', $address->address_line_2 ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-md-flex justify-content-between align-items-center">
                                    <a href="{{ route('customers.index') }}" class="btn btn-dark hstack gap-6">
                                        Back
                                    </a>
                                    <button type="submit" class="btn btn-primary hstack gap-6">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div> {{-- row end --}}
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // --- Base Select2 setup ---
            $('#state, #district, #city, #pincode').select2({
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true,
                minimumInputLength: 1
            });

            // --- STATE: search all states ---
            $('#state').select2({
                width: '100%',
                placeholder: 'Search state',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '/states/search',
                    dataType: 'json',
                    delay: 300,
                    data: params => ({
                        q: params.term
                    }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.name
                        }))
                    })
                }
            });

            // --- DISTRICT: depends on state ---
            $('#district').select2({
                width: '100%',
                placeholder: 'Search district',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '/districts/search',
                    dataType: 'json',
                    delay: 300,
                    data: params => {
                        const stateId = $('#state').val();
                        if (!stateId) return {}; // don't fetch without state
                        return {
                            q: params.term,
                            state_id: stateId
                        };
                    },
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.name
                        }))
                    })
                }
            });

            // --- CITY: depends on district ---
            $('#city').select2({
                width: '100%',
                placeholder: 'Search city',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '/cities/search',
                    dataType: 'json',
                    delay: 300,
                    data: params => {
                        const districtId = $('#district').val();
                        if (!districtId) return {};
                        return {
                            q: params.term,
                            district_id: districtId
                        };
                    },
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.name
                        }))
                    })
                }
            });

            // --- PINCODE: depends on district ---
            $('#pincode').select2({
                width: '100%',
                placeholder: 'Search pincode',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '/pincodes/search',
                    dataType: 'json',
                    delay: 300,
                    data: params => {
                        const districtId = $('#district').val();
                        if (!districtId) return {};
                        return {
                            q: params.term,
                            district_id: districtId
                        };
                    },
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.code
                        }))
                    })
                }
            });

            // --- Reset dependent dropdowns when parent changes ---
            $('#state').on('change', function() {
                resetBelow('#state');
            });

            $('#district').on('change', function() {
                resetBelow('#district');
            });

            $('#city').on('change', function() {
                resetBelow('#city');
            });

            // --- Handle manual pincode selection ---
            $('#pincode').on('select2:select', function(e) {
                const pincodeId = e.params.data.id;

                $.get(`/pincode/${pincodeId}`, function(response) {
                    if (!response) return;

                    // Update state + district
                    if (response.state && response.district) {
                        const s = new Option(response.state, response.state_id, true, true);
                        const d = new Option(response.district, response.district_id, true, true);
                        $('#state').append(s).trigger('change');
                        $('#district').append(d).trigger('change');
                    }

                    // Update city
                    if (response.city) {
                        const c = new Option(response.city, response.city_id, true, true);
                        $('#city').append(c).trigger('change');
                    }

                    // If response includes multiple cities, just clear and let user search
                    if (response.cities && response.cities.length > 1) {
                        $('#city').val(null).trigger('change');
                    }
                });
            });

            // --- Utility to reset lower selects ---
            function resetBelow(selector) {
                const order = ['#state', '#district', '#city', '#pincode'];
                const idx = order.indexOf(selector);
                order.slice(idx + 1).forEach(sel => {
                    $(sel).empty().val(null).trigger('change');
                });
            }
        });
    </script>
@endsection
