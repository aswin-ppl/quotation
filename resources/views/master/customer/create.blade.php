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
        $page_title = 'Customers';
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
                        <a href="{{ route('customers.index') }}" class="text-primary">{{ $page_title }}</a>
                    </li>
                    <li class="breadcrumb-item active text-primary " aria-current="page">
                        Create
                    </li>
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
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    @error('name')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Mobile --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                        id="mobile" name="mobile" value="{{ old('mobile') }}" required>
                                    <label for="mobile">Mobile <span class="text-danger">*</span></label>
                                    @error('mobile')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-4">
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- State --}}
                            <div class="col-md-6 mb-4">
                                <label for="state">State <span class="text-danger">*</span></label>
                                <select id="state" name="state_id"
                                    class="form-select @error('state_id') is-invalid @enderror" required>
                                    <option value="">Select State</option>
                                </select>
                                @error('state_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- District --}}
                            <div class="col-md-6 mb-4">
                                <label for="district">District <span class="text-danger">*</span></label>
                                <select id="district" name="district_id"
                                    class="form-select @error('district_id') is-invalid @enderror" required>
                                    <option value="">Select District</option>
                                </select>
                                @error('district_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- City --}}
                            <div class="col-md-6 mb-4">
                                <label for="city">City <span class="text-danger">*</span></label>
                                <select id="city" name="city_id"
                                    class="form-selec @error('city_id') is-invalid @enderror" required>
                                    <option value="">Select City</option>
                                </select>
                                @error('city_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Pincode --}}
                            <div class="col-md-6 mb-4">
                                <label for="pincode">Pincode <span class="text-danger">*</span></label>
                                <select id="pincode" name="pincode_id"
                                    class="form-select @error('pincode_id') is-invalid @enderror" required>
                                    <option value="">Select Pincode</option>
                                </select>
                                @error('pincode_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Address 1 --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_line_1">Address Line 1 <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address_line_1') is-invalid @enderror" rows="3" name="address_line_1"
                                        required>{{ old('address_line_1') }}</textarea>
                                    @error('address_line_1')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Address 2 --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_line_2">Address Line 2</label>
                                    <textarea class="form-control" rows="3" name="address_line_2">{{ old('address_line_2') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 mt-5">
                                <div class="d-md-flex justify-content-between align-items-center">
                                    <a href="{{ route('customers.index') }}" class="btn btn-dark hstack gap-6">Back</a>
                                    <button type="submit" class="btn btn-primary hstack gap-6">Create</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $(document).ready(function() {

                // --- Initialize all Select2s ---
                $('#state, #district, #city, #pincode').select2({
                    width: '100%',
                    placeholder: 'Select an option',
                    allowClear: true
                });

                // --- Load States ---
                $.get('/states', function(states) {
                    $('#state').empty().append('<option></option>');
                    states.forEach(s => $('#state').append(new Option(s.name, s.id)));
                });

                // --- State -> District ---
                $('#state').on('change', function() {
                    const id = $(this).val();
                    resetBelow('#state');
                    if (!id) return;

                    $.get(`/districts/${id}`, function(districts) {
                        fillSelect('#district', districts);
                    });
                });

                // --- District -> City ---
                $('#district').on('change', function() {
                    const id = $(this).val();
                    resetBelow('#district');
                    if (!id) return;

                    $.get(`/cities/${id}`, function(cities) {
                        fillSelect('#city', cities);
                    });
                });

                // --- City -> Pincode (and auto-select first) ---
                $('#city').on('change', function() {
                    const id = $(this).val();
                    resetBelow('#city');
                    if (!id) return;

                    $.get(`/pincodes/${id}`, function(pincodes) {
                        fillSelect('#pincode', pincodes, 'code');

                        // auto-select first pincode if available
                        if (pincodes.length > 0) {
                            const firstPin = pincodes[0];
                            $('#pincode').val(firstPin.id).trigger('change.select2');
                        }
                    });
                });

                // --- Pincode async search (bottom-up entry) ---
                $('#pincode').select2({
                    width: '100%',
                    placeholder: 'Type or select a pincode',
                    allowClear: true,
                    ajax: {
                        url: '/pincode/search',
                        dataType: 'json',
                        delay: 300,
                        data: params => ({
                            q: params.term
                        }),
                        processResults: data => ({
                            results: data.map(item => ({
                                id: item.id,
                                text: item.code
                            }))
                        })
                    }
                });

                // --- When pincode selected manually ---
                $('#pincode').on('select2:select', function(e) {
                    const pincodeId = e.params.data.id;

                    $.get(`/pincode/${pincodeId}`, function(response) {
                        const cities = response.cities || [];

                        // Update District + State immediately
                        if (response.state && response.district) {
                            $('#state')
                                .empty()
                                .append(new Option(response.state, response.state_id, true,
                                    true))
                                .trigger('change.select2');

                            $('#district')
                                .empty()
                                .append(new Option(response.district, response.district_id,
                                    true, true))
                                .trigger('change.select2');
                        }

                        // Update city dropdown
                        fillSelect('#city', cities);

                        if (cities.length === 1) {
                            const c = cities[0];
                            $('#city')
                                .val(c.id)
                                .trigger('change.select2');
                        } else if (cities.length > 1) {
                            // Let user pick city if multiple
                            $('#city').off('change.auto').on('change.auto', function() {
                                const city = cities.find(x => x.id == $(this)
                                    .val());
                                if (city) selectChain(city);
                            });
                        }
                    });
                });

                // --- Helper: fill Select2 dropdown ---
                function fillSelect(selector, data, textKey = 'name') {
                    const $el = $(selector);
                    $el.empty().append('<option></option>');
                    data.forEach(d => $el.append(new Option(d[textKey] || d.name, d.id)));
                    $el.trigger('change.select2');
                }

                // --- Helper: reset all fields below a certain select ---
                function resetBelow(selector) {
                    const order = ['#state', '#district', '#city', '#pincode'];
                    const idx = order.indexOf(selector);
                    order.slice(idx + 1).forEach(sel => {
                        $(sel).empty().append('<option></option>').val(null).trigger(
                            'change.select2');
                    });
                }

                // --- Helper: select chain (state, district, city) ---
                function selectChain(city) {
                    $('#state').empty().append(new Option(city.state, city.state_id, true, true)).trigger(
                        'change.select2');
                    $('#district').empty().append(new Option(city.district, city.district_id, true, true))
                        .trigger('change.select2');
                    $('#city').empty().append(new Option(city.name, city.id, true, true)).trigger(
                        'change.select2');
                }
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
        });
    </script>
@endsection
