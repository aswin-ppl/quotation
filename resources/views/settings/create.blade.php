@extends('layouts.app')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-selection__arrow {
            display: none !important;
        }
    </style>
@endsection
@section('content')
    @php
        $parent_title = 'Settings';
        $page_title = 'Settings';
    @endphp
    <div class="body-wrapper">
        <div class="container-fluid">

            <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '&gt;'" class="mb-3">
                <ol class="breadcrumb bg-primary-subtle px-3 py-2 rounded">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)" class="text-primary d-flex align-items-center"><i
                                class="ti ti-home fs-4 mt-1"></i></a>
                    </li>
                    <li class="breadcrumb-item active text-primary " aria-current="page">
                        <a href="{{ route('settings.index') }}" class="text-primary">{{ $page_title }}</a>
                    </li>
                    <li class="breadcrumb-item active text-primary " aria-current="page">
                        Update
                    </li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Create Settings</h4>
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" name="company_name" id="company_name" class="form-control"
                                    value="{{ $settings['company_name'] ?? '' }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company_mobile" class="form-label">Mobile</label>
                                <input type="text" name="company_mobile" id="company_mobile" class="form-control"
                                    value="{{ $settings['company_mobile'] ?? '' }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company_email" class="form-label">Email</label>
                                <input type="email" name="company_email" id="company_email" class="form-control"
                                    value="{{ $settings['company_email'] ?? '' }}">
                            </div>

                            {{-- Pincode --}}
                            <div class="col-md-6 mb-4">
                                <label for="pincode">Pincode</label>
                                <select id="pincode" name="company_pincode" class="form-select">
                                    @if (!empty($settings['company_pincode']))
                                        <option value="{{ $settings['company_pincode'] }}" selected>
                                            {{ $settings['company_pincode_value'] ?? 'Current Pincode' }}
                                        </option>
                                    @endif
                                </select>
                            </div>

                            {{-- City --}}
                            <div class="col-md-6 mb-4">
                                <label for="city">City</label>

                                <select id="city" name="company_city" class="form-select">
                                    @if (!empty($settings['company_city']))
                                        <option value="{{ $settings['company_city'] }}" selected>
                                            {{ $settings['company_city_name'] ?? 'Current City' }}
                                        </option>
                                    @endif
                                </select>
                            </div>

                            {{-- District --}}
                            <div class="col-md-6 mb-4">
                                <label for="district">District</label>
                                <select id="district" name="company_district" class="form-select">
                                    @if (!empty($settings['company_district']))
                                        <option value="{{ $settings['company_district'] }}" selected>
                                            {{ $settings['company_district_name'] ?? 'Current District' }}
                                        </option>
                                    @endif
                                </select>
                            </div>

                            {{-- State --}}
                            <div class="col-md-6 mb-4">
                                <label for="state">State</label>
                                <select id="state" name="company_state" class="form-select">
                                    @if (!empty($settings['company_state']))
                                        <option value="{{ $settings['company_state'] }}" selected>
                                            {{ $settings['company_state_name'] ?? 'Current State' }}
                                        </option>
                                    @endif
                                </select>

                            </div>

                            {{-- Address 1 --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" name="company_address" placeholder="Door No, Street name"
                                        >{{ $settings['company_address'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 mt-5">
                                <div class="d-md-flex justify-content-between align-items-center flex-row-reverse">
                                    <button type="submit" class="btn btn-primary hstack gap-6">Save Settings</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            // --- Initialize all Select2s ---
            $('#state, #district, #city, #pincode').select2({
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true
            });

            // --- Load States ---
            const savedStateId = "{{ $settings['company_state'] ?? '' }}";

            $.get('/states', function(states) {
                const $state = $('#state');
                $state.empty().append('<option></option>');

                states.forEach(s => {
                    const isSelected = savedStateId && s.id == savedStateId;
                    $state.append(new Option(s.name, s.id, isSelected, isSelected));
                });

                // Refresh Select2 to reflect the selected item
                $state.trigger('change.select2');
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

            // --- Pincode async search ---
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
                    processResults: data => {
                        console.log(data);
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.code
                            }))
                        };
                    }
                }
            });

            // --- District async search ---
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

            // pincode onchange
            $('#pincode').on('select2:select', function(e) {
                const pincodeId = e.params.data.id;
                console.log('Selected pincode:', pincodeId);

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
    </script>
@endsection
