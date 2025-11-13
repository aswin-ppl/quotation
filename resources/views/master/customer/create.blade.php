@extends('layouts.app')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-selection__arrow {
            display: none !important;
        }

        .address-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .address-text {
            flex: 1;
            font-size: 14px;
            color: #333;
        }

        .address-item .btn-danger {
            padding: 4px 10px;
            font-size: 12px;
        }
    </style>
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
                        <a href="javascript:void(0)" class="text-primary d-flex align-items-center">
                            <i class="ti ti-home fs-4 mt-1"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)" class="text-primary">{{ $parent_title }}</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}"
                            class="text-primary">{{ $page_title }}</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Create</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Create Customer</h4>
                    <form id="customerForm" method="POST" action="{{ route('customers.store') }}">
                        @csrf
                        <div class="row">
                            {{-- Name --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Mobile --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile">Mobile <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                        id="mobile" name="mobile" value="{{ old('mobile') }}" required>
                                    @error('mobile')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Address Input Section --}}
                        <div class="row">
                            {{-- Address Line --}}
                            <div class="col-md-12 mb-3">
                                <label for="address_line">Address Line <span class="text-danger">*</span></label>
                                <textarea id="address_line" class="form-control" rows="2" placeholder="Street, Building, Apartment"></textarea>
                            </div>

                            {{-- Pincode --}}
                            <div class="col-md-6 mb-3">
                                <label for="pincode">Pincode <span class="text-danger">*</span></label>
                                <select id="pincode" class="form-select">
                                    <option value="">Select Pincode</option>
                                </select>
                            </div>

                            {{-- City --}}
                            <div class="col-md-6 mb-3">
                                <label for="city">City <span class="text-danger">*</span></label>
                                <select id="city" class="form-select">
                                    <option value="">Select City</option>
                                </select>
                            </div>

                            {{-- District --}}
                            <div class="col-md-6 mb-3">
                                <label for="district">District <span class="text-danger">*</span></label>
                                <select id="district" class="form-select">
                                    <option value="">Select District</option>
                                </select>
                            </div>

                            {{-- State --}}
                            <div class="col-md-6 mb-3">
                                <label for="state">State <span class="text-danger">*</span></label>
                                <select id="state" class="form-select">
                                    <option value="">Select State</option>
                                </select>
                            </div>

                            {{-- Address Type --}}
                            <div class="col-md-6 mb-3">
                                <label for="address_type">Address Type <span class="text-danger">*</span></label>
                                <select id="address_type" class="form-select">
                                    <option value="home">Home</option>
                                    <option value="work">Work</option>
                                    <option value="billing">Billing</option>
                                    <option value="shipping">Shipping</option>
                                </select>
                            </div>

                            <div class="col-12 mb-4">
                                <button type="button" id="add-address-btn" class="btn btn-success">
                                    <i class="ti ti-plus"></i> Add Address
                                </button>
                            </div>
                        </div>

                        {{-- Added Addresses Display --}}
                        <div id="added-addresses-container" style="display: none;">
                            <hr class="my-4">
                            <h5 class="mb-3">Added Addresses</h5>
                            <div id="address-list">
                                <!-- Addresses will be appended here -->
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="d-md-flex justify-content-between align-items-center">
                                <a href="{{ route('customers.index') }}" class="btn btn-dark hstack gap-6">Back</a>
                                <button type="submit" class="btn btn-primary hstack gap-6">Create Customer</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            let addressIndex = 0;
            let addresses = []; // Store address objects

            // Initialize Select2
            $('#state, #district, #city, #pincode').select2({
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true
            });

            // Load States
            $.get('/states', function(states) {
                $('#state').empty().append('<option></option>');

                states.forEach(s => {
                    $('#state').append(new Option(s.name, s.id));
                });

                // now set default
                const tamilNadu = states.find(s => s.name === 'Tamil Nadu');
                if (tamilNadu) {
                    $('#state').val(tamilNadu.id).trigger('change');
                }
            });


            // State -> District
            $('#state').on('change', function() {
                const id = $(this).val();
                $('#district, #city, #pincode').empty().append('<option></option>').val(null).trigger(
                    'change.select2');
                if (id) {
                    $.get(`/districts/${id}`, function(districts) {
                        fillSelect('#district', districts);
                    });
                }
            });

            // District -> City
            $('#district').on('change', function() {
                const id = $(this).val();
                $('#city, #pincode').empty().append('<option></option>').val(null).trigger(
                    'change.select2');
                if (id) {
                    $.get(`/cities/${id}`, function(cities) {
                        fillSelect('#city', cities);
                    });
                }
            });

            // City -> Pincode
            $('#city').on('change', function() {
                const id = $(this).val();
                $('#pincode').empty().append('<option></option>').val(null).trigger('change.select2');
                if (id) {
                    $.get(`/pincodes/${id}`, function(pincodes) {
                        fillSelect('#pincode', pincodes, 'code');
                        if (pincodes.length > 0) {
                            $('#pincode').val(pincodes[0].id).trigger('change.select2');
                        }
                    });
                }
            });

            // Pincode async search
            $('#pincode').select2({
                width: '100%',
                placeholder: 'Type or select a pincode',
                allowClear: true,
                ajax: {
                    url: '/pincode/search',
                    dataType: 'json',
                    delay: 300,
                    data: params => ({
                        q: params.term,
                        district_id: $('#district').val() // <-- give the backend some brains
                    }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.code
                        }))
                    })
                }
            });

            $('#district').on('select2:clear', function(e) {
                const stateId = $('#state').val();
                if (!stateId) return;

                $.get(`/districts/${stateId}`, function(districts) {
                    fillSelect('#district', districts);
                });
            });

            // Pincode reverse lookup
            $('#pincode').on('select2:select', function(e) {
                const pincodeId = e.params.data.id;
                $.get(`/pincode/${pincodeId}`, function(response) {
                    if (response.state && response.district) {
                        $('#state').empty().append(new Option(response.state, response.state_id,
                            true, true)).trigger('change.select2');
                        $('#district').empty().append(new Option(response.district, response
                            .district_id, true, true)).trigger('change.select2');
                    }
                    const cities = response.cities || [];
                    fillSelect('#city', cities);
                    if (cities.length === 1) {
                        $('#city').val(cities[0].id).trigger('change.select2');
                    }
                });
            });

            // Add Address Button Click
            $('#add-address-btn').on('click', function() {
                const addressLine = $('#address_line').val().trim();
                const stateId = $('#state').val();
                const stateName = $('#state option:selected').text();
                const districtId = $('#district').val();
                const districtName = $('#district option:selected').text();
                const cityId = $('#city').val();
                const cityName = $('#city option:selected').text();
                const pincodeId = $('#pincode').val();
                const pincodeCode = $('#pincode option:selected').text();
                const addressType = $('#address_type').val();

                // Validation
                if (!addressLine) {
                    toastr.error('Please enter address line', 'Error');
                    $('#address_line').focus();
                    return;
                }
                if (!stateId || !districtId || !cityId || !pincodeId) {
                    toastr.error('Please select State, District, City, and Pincode', 'Error');
                    return;
                }

                // Create address object
                const address = {
                    index: addressIndex,
                    address_line: addressLine,
                    state_id: stateId,
                    state_name: stateName,
                    district_id: districtId,
                    district_name: districtName,
                    city_id: cityId,
                    city_name: cityName,
                    pincode_id: pincodeId,
                    pincode_code: pincodeCode,
                    type: addressType
                };

                // Store address
                addresses.push(address);

                // Display formatted address
                renderAddresses();

                // Clear form
                clearAddressForm();

                // Show addresses container
                $('#added-addresses-container').show();

                addressIndex++;
            });

            // Render all addresses
            function renderAddresses() {
                $('#address-list').empty();

                addresses.forEach((addr, idx) => {
                    const isDefault = idx === 0;
                    const formattedAddress =
                        `${addr.address_line}, ${addr.city_name}, ${addr.district_name}, ${addr.state_name} - ${addr.pincode_code}`;

                    const addressHtml = `
                        <div class="address-item" data-index="${addr.index}">
                            <input type="radio" name="default_address" value="${addr.index}" 
                                   ${isDefault ? 'checked' : ''} class="form-check-input">
                            
                            <span class="address-text">${formattedAddress}</span>
                            
                            <button type="button" class="btn btn-danger btn-sm remove-address" 
                                    data-index="${addr.index}">
                                <i class="ti ti-trash"></i> Delete
                            </button>

                            <!-- Hidden inputs for form submission -->
                            <input type="hidden" name="addresses[${addr.index}][address_line_1]" value="${addr.address_line}">
                            <input type="hidden" name="addresses[${addr.index}][state_id]" value="${addr.state_id}">
                            <input type="hidden" name="addresses[${addr.index}][district_id]" value="${addr.district_id}">
                            <input type="hidden" name="addresses[${addr.index}][city_id]" value="${addr.city_id}">
                            <input type="hidden" name="addresses[${addr.index}][pincode_id]" value="${addr.pincode_id}">
                            <input type="hidden" name="addresses[${addr.index}][type]" value="${addr.type}">
                        </div>
                    `;

                    $('#address-list').append(addressHtml);
                });
            }

            // Remove address
            $(document).on('click', '.remove-address', function() {
                const indexToRemove = $(this).data('index');

                if (addresses.length === 1) {
                    toastr.error('At least one address is required', 'Error');
                    return;
                }

                // Remove from array
                addresses = addresses.filter(addr => addr.index !== indexToRemove);

                // Re-render
                renderAddresses();

                // Hide container if no addresses
                if (addresses.length === 0) {
                    $('#added-addresses-container').hide();
                }

                toastr.success('Address removed', 'Success');
            });

            // Clear address form
            function clearAddressForm() {
                $('#address_line').val('');
                $('#state').val(null).trigger('change.select2');
                $('#district').empty().append('<option></option>').val(null).trigger('change.select2');
                $('#city').empty().append('<option></option>').val(null).trigger('change.select2');
                $('#pincode').empty().append('<option></option>').val(null).trigger('change.select2');
                $('#address_type').val('home');
            }

            // Form submission validation
            $('#customerForm').on('submit', function(e) {
                if (addresses.length === 0) {
                    e.preventDefault();
                    toastr.error('Please add at least one address', 'Error');
                    return false;
                }
            });

            // Helper function
            function fillSelect(selector, data, textKey = 'name') {
                const $el = $(selector);
                $el.empty().append('<option></option>');
                data.forEach(d => $el.append(new Option(d[textKey] || d.name, d.id)));
                $el.trigger('change.select2');
            }

            // Toastr config
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
        });
    </script>
@endsection
