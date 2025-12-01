@extends('layouts.app')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .other-image-preview {
            height: 80px;
        }

        .select2-selection__arrow {
            display: none !important;
        }

        .wizard-container {
            border-radius: 15px;
            padding: 40px;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .progress-container {
            margin-bottom: 30px;
        }

        .step-indicator {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #6c757d;
            position: relative;
            z-index: 1;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .step-circle:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .step-circle.active {
            background: var(--bs-primary);
            color: white;
        }

        .step-circle.completed {
            background: var(--bs-secondary-color);
            color: white;
        }

        .step-circle.disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 10px;
        }

        .progress-bar {
            background-color: var(--bs-primary);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
        }

        .delete-step-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #dc3545;
            color: white;
            border: 2px solid white;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            z-index: 7;
        }

        .delete-step-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }

        .step-circle-wrapper {
            position: relative;
        }
    </style>
@endsection
@section('content')
    @php
        $parent_title = 'Master';
        $page_title = 'Product';
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
                        <a href="{{ route('products.index') }}" class="text-primary">{{ $page_title }}</a>
                    </li>
                    <li class="breadcrumb-item active text-primary " aria-current="page">
                        Create
                    </li>
                </ol>
            </nav>

            <div class="card p-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="customer">Customer</label>
                            <select id="customer" name="customer" class="form-select">
                                <option value="">Select a customer</option>
                                @foreach ($customers as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div id="address-container">
                                <label for="to-address">Enter To Address</label>
                                {{-- optional address dropdown (shown only if >1 address) --}}
                                <div id="address-select-wrapper" class="mb-3 d-none">
                                    <select id="addressSelect" class="form-select"></select>
                                </div>

                                {{-- textarea that will be filled automatically --}}
                                <div class="form-group">
                                    <textarea id="to-address" class="form-control" rows="3" placeholder="To," readonly></textarea>
                                </div>
                                <span class="text-danger d-none txt-to-error">Please enter the recipient’s
                                    address.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card wizard-container">
                <!-- Step Indicator -->
                <div class="step-indicator" id="stepIndicator"></div>

                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress">
                        <div class="progress-bar" id="progressBar" role="progressbar" style="width: 100%">
                        </div>
                    </div>
                </div>

                <form id="wizardForm" action="{{ route('products.store') }}" method="POST" class="validation-wizard wizard-circle">
                    @csrf
                    <input type="hidden" id="hidden_customer" name="customer">
                    <input type="hidden" id="hidden_addressSelect" name="addressSelect">
                    <div id="stepsContainer"></div>

                    <!-- Navigation Buttons -->
                    <!-- Navigation Buttons -->
                    <div class="button-container">
                        <button type="button" class="btn btn-light" id="prevBtn" onclick="changeStep(-1)"
                            style="display: none;">Previous</button>
                        <div>
                            <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)"
                                style="display: none;">Next</button>
                            <button type="button" class="btn btn-primary addRowBtn" id="addRowBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 16 16" style="margin-right: 5px;">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                </svg>
                                Add Row
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn">Submit All</button>
                        </div>
                    </div>


                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="button" class="btn bg-primary-subtle text-primary" onclick="addProduct()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16" style="margin-right: 5px;">
                                <path
                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                            </svg>
                            Add Product
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('js/plugins/toastr-init.js') }}"></script>

    <script>
        // ========================================
        // WIZARD NAVIGATION & STEP MANAGEMENT
        // ========================================
        let currentStep = 1;
        let totalSteps = 0;
        let completedSteps = new Set([1]);
        let productCounter = 0;
        let products = [];

        // Initialize first product
        function init() {
            addProduct();
            showStep(currentStep);
        }

        function addProduct() {
            productCounter++;
            totalSteps++;

            const stepsContainer = document.getElementById('stepsContainer');
            const stepNumber = totalSteps;
            const productId = productCounter;

            const stepDiv = document.createElement('div');
            stepDiv.className = 'step';
            stepDiv.dataset.step = stepNumber;
            stepDiv.dataset.productId = productId;

            stepDiv.innerHTML = `
            <h4 class="mb-4">Product ${productId} Information</h4>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="size_mm_${productId}">Size (mm) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="size_mm_${productId}" name="products[${productId}][size_mm]" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="cost_per_units_${productId}">Cost/Units <span class="text-danger">*</span></label>
                        <input type="number" class="form-control cost-input" id="cost_per_units_${productId}" 
                               name="products[${productId}][cost_per_units]" min="0" step="0.01" data-product-id="${productId}" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="quantity_${productId}">Qty <span class="text-danger">*</span></label>
                        <input type="number" class="form-control qty-input" id="quantity_${productId}" 
                               name="products[${productId}][quantity]" step="1" min="0" data-product-id="${productId}" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="product_price_${productId}">Product Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control price-output" id="product_price_${productId}" 
                               name="products[${productId}][product_price]" step="0.01" min="0" readonly required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="autocad_image_${productId}" class="form-label fw-semibold">AutoCAD Image <span class="text-danger">*</span></label>
                        <input type="file" name="products[${productId}][autocad_image]" id="autocad_image_${productId}"
                               class="form-control autocad-input" accept="image/*" data-product-id="${productId}" required>
                        <div id="autocad_preview_${productId}" class="mt-3" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body p-3 text-center">
                                    <img id="autocad_img_${productId}" src="" alt="AutoCAD Preview" class="img-fluid"
                                         style="max-height: 250px; object-fit: contain;">
                                    <p class="mt-2 mb-0 small text-muted" id="autocad_filename_${productId}"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="extra_images_${productId}" class="form-label fw-semibold">Extra Images <span class="text-danger">*</span></label>
                        <input type="file" name="products[${productId}][extra_images][]" id="extra_images_${productId}" 
                               class="form-control extra-images-input" multiple accept="image/*" data-product-id="${productId}" required>
                        <div id="extra_images_preview_${productId}" class="mt-3" style="display: none;">
                            <div class="row g-2" id="extra_images_list_${productId}"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <div class="table-responsive border rounded-1 bg-light">
                            <table class="table align-middle" id="descriptionTable_${productId}">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">Key</th>
                                        <th style="width: 50%">Value</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="descBody_${productId}">
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][0][key]" tabindex="-1" class="form-control" value="Series" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][0][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][1][key]" tabindex="-1" class="form-control" value="BRAND" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][1][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][2][key]" tabindex="-1" class="form-control" value="Material" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][2][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][3][key]" tabindex="-1" class="form-control" value="Design" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][3][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][4][key]" tabindex="-1" class="form-control" value="Color" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][4][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light" colspan="3"><strong>Sections</strong></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][5][key]" tabindex="-1" class="form-control" value="Section Color" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][5][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light" colspan="3"><strong>Hardware</strong></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][6][key]" tabindex="-1" class="form-control" value="Handle" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][6][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][7][key]" tabindex="-1" class="form-control" value="Handle Color" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][7][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][8][key]" tabindex="-1" class="form-control" value="Glass" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][8][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][9][key]" tabindex="-1" class="form-control" value="Sealant" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][9][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="products[${productId}][descriptions][10][key]" tabindex="-1" class="form-control" value="Beadery" readonly></td>
                                        <td><input type="text" name="products[${productId}][descriptions][10][value]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm removeRow" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;

            stepsContainer.appendChild(stepDiv);

            // Add to products tracking array
            products.push({
                stepNumber,
                productId
            });

            // If not the first product, navigate to it
            if (totalSteps > 1) {
                currentStep = stepNumber;
                completedSteps.add(stepNumber);
            }

            updateIndicators();
            showStep(currentStep);
        }

        function deleteProduct(stepNumber) {
            if (totalSteps <= 1) {
                alert('You must have at least one product!');
                return;
            }

            const steps = document.querySelectorAll('.step');
            const stepToDelete = Array.from(steps).find(s => parseInt(s.dataset.step) === stepNumber);

            if (!stepToDelete) return;

            // Remove from products array
            const productIndex = products.findIndex(p => p.stepNumber === stepNumber);
            if (productIndex !== -1) {
                products.splice(productIndex, 1);
            }

            // Remove the DOM element
            stepToDelete.remove();

            // Update step numbers for remaining steps
            const remainingSteps = document.querySelectorAll('.step');
            products = [];
            remainingSteps.forEach((step, index) => {
                const newStepNumber = index + 1;
                const productId = parseInt(step.dataset.productId);
                step.dataset.step = newStepNumber;
                products.push({
                    stepNumber: newStepNumber,
                    productId
                });
            });

            totalSteps--;

            // Update completed steps
            const newCompletedSteps = new Set();
            completedSteps.forEach(s => {
                if (s < stepNumber) {
                    newCompletedSteps.add(s);
                } else if (s > stepNumber) {
                    newCompletedSteps.add(s - 1);
                }
            });
            completedSteps = newCompletedSteps;

            // Adjust current step
            if (currentStep > totalSteps) {
                currentStep = totalSteps;
            } else if (currentStep >= stepNumber) {
                currentStep = Math.max(1, currentStep - 1);
            }

            // Ensure current step is in completed set
            completedSteps.add(currentStep);

            updateIndicators();
            showStep(currentStep);
        }

        function updateIndicators() {
            const indicator = document.getElementById('stepIndicator');
            indicator.innerHTML = '';

            for (let i = 1; i <= totalSteps; i++) {
                const wrapper = document.createElement('div');
                wrapper.className = 'step-circle-wrapper';

                const circle = document.createElement('div');
                circle.className = 'step-circle';
                circle.id = `indicator-${i}`;
                circle.textContent = i;
                circle.onclick = () => goToStep(i);

                if (totalSteps > 1) {
                    const deleteBtn = document.createElement('div');
                    deleteBtn.className = 'delete-step-btn';
                    deleteBtn.innerHTML = '×';
                    deleteBtn.onclick = (e) => {
                        e.stopPropagation();
                        deleteProduct(i);
                    };
                    wrapper.appendChild(deleteBtn);
                }

                wrapper.appendChild(circle);
                indicator.appendChild(wrapper);
            }
        }

        function goToStep(targetStep) {
            // Allow navigation to completed steps or the next uncompleted step
            if (!completedSteps.has(targetStep) && targetStep !== Math.max(...completedSteps) + 1) {
                return;
            }

            const steps = document.querySelectorAll('.step');
            const currentStepElement = steps[currentStep - 1];

            // Validate before moving forward
            if (targetStep > currentStep) {
                if (!validateCurrentStep(currentStepElement)) {
                    return;
                }
            }

            currentStep = targetStep;
            completedSteps.add(targetStep);
            showStep(currentStep);
        }

        function validateCurrentStep(stepElement) {
            const inputs = stepElement.querySelectorAll('input[required], select[required]');
            let valid = true;

            inputs.forEach(input => {
                // Skip file inputs with existing preview
                if (input.type === 'file') {
                    const productId = input.dataset.productId;
                    if (input.classList.contains('autocad-input')) {
                        const preview = document.getElementById(`autocad_preview_${productId}`);
                        if (preview && preview.style.display !== 'none') {
                            input.classList.remove('is-invalid');
                            return;
                        }
                    } else if (input.classList.contains('extra-images-input')) {
                        const preview = document.getElementById(`extra_images_preview_${productId}`);
                        if (preview && preview.style.display !== 'none') {
                            input.classList.remove('is-invalid');
                            return;
                        }
                    }
                }

                if (!input.checkValidity()) {
                    input.classList.add('is-invalid');
                    valid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            return valid;
        }

        function changeStep(direction) {
            const steps = document.querySelectorAll('.step');
            const currentStepElement = steps[currentStep - 1];

            // Validate before moving forward
            if (direction === 1) {
                if (!validateCurrentStep(currentStepElement)) {
                    return;
                }
            }

            const newStep = currentStep + direction;

            if (newStep < 1 || newStep > totalSteps) return;

            currentStep = newStep;
            completedSteps.add(newStep);
            showStep(currentStep);
        }

        function showStep(step) {
            const steps = document.querySelectorAll('.step');
            const indicators = document.querySelectorAll('.step-circle');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const progressBar = document.getElementById('progressBar');

            // Show current step
            steps.forEach(s => s.classList.remove('active'));
            if (steps[step - 1]) {
                steps[step - 1].classList.add('active');
            }

            // Update indicators
            indicators.forEach((indicator, index) => {
                indicator.classList.remove('active', 'completed', 'disabled');
                const stepNum = index + 1;

                if (completedSteps.has(stepNum) && stepNum !== step) {
                    indicator.classList.add('completed');
                } else if (stepNum === step) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.add('disabled');
                }
            });

            // Update progress bar
            const progress = (step / totalSteps) * 100;
            progressBar.style.width = progress + '%';

            // Update navigation buttons
            prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
            nextBtn.style.display = step === totalSteps ? 'none' : 'inline-block';
        }

        document.getElementById('wizardForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate customer selection
            const customerId = document.getElementById('customer').value;
            if (!customerId) {
                toastr.error('Please select a customer before submitting.');
                return;
            }

            // Validate address selection
            const addressId = document.getElementById('addressSelect').value;
            const addressWrapper = document.getElementById('address-select-wrapper');
            
            // If address dropdown is visible, ensure an address is selected
            if (!addressWrapper.classList.contains('d-none') && !addressId) {
                toastr.error('Please select an address before submitting.');
                return;
            }

            // Validate all steps
            const steps = document.querySelectorAll('.step');
            let allValid = true;

            steps.forEach((step, index) => {
                if (!validateCurrentStep(step)) {
                    allValid = false;
                    if (allValid === false && currentStep !== index + 1) {
                        toastr.error(`Please complete Product ${index + 1} before submitting!`);
                        currentStep = index + 1;
                        showStep(currentStep);
                    }
                }
            });

            if (!allValid) return;

            // Set hidden input values before submission
            document.getElementById('hidden_customer').value = customerId;
            document.getElementById('hidden_addressSelect').value = addressId;

            // Submit the form
            this.submit();
        });

        // Real-time validation feedback
        document.addEventListener('input', function(e) {
            if (e.target.matches('input, select, textarea')) {
                e.target.classList.remove('is-invalid');
            }
        });

        // Initialize on page load
        init();
    </script>

    <script>
        // ========================================
        // JQUERY-BASED FORM INTERACTIONS
        // ========================================
        $(document).ready(function() {
            let rowIdxCounters = {}; // Track row indices per product

            // ========================================
            // AUTO-CALCULATE PRODUCT PRICE
            // ========================================
            $(document).on('input', '.cost-input, .qty-input', function() {
                const productId = $(this).data('product-id');
                const cost = parseFloat($(`#cost_per_units_${productId}`).val()) || 0;
                const qty = parseFloat($(`#quantity_${productId}`).val()) || 0;
                $(`#product_price_${productId}`).val((cost * qty).toFixed(2));
            });

            // ========================================
            // AUTOCAD IMAGE PREVIEW
            // ========================================
            const csrfToken = $('meta[name="csrf-token"]').attr('content') || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function uploadTempFile(file, productId, type) {
                const fd = new FormData();
                fd.append('file', file);
                fd.append('productId', productId);
                fd.append('type', type);

                return fetch('/uploads/temp-image', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: fd
                }).then(async res => {
                    if (!res.ok) {
                        let text = await res.text();
                        const err = new Error(res.status + ' ' + (res.statusText || 'error'));
                        err.status = res.status;
                        err.body = text;
                        throw err;
                    }
                    return res.json();
                });
            }

            function deleteTempFile(filename) {
                return fetch('/uploads/temp-image', {
                    method: 'DELETE',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ filename })
                }).then(async res => {
                    if (!res.ok) {
                        let text = await res.text();
                        const err = new Error(res.status + ' ' + (res.statusText || 'error'));
                        err.status = res.status;
                        err.body = text;
                        throw err;
                    }
                    return res.json();
                });
            }

            $(document).on('change', '.autocad-input', function(e) {
                const productId = $(this).data('product-id');
                const file = e.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $(`#autocad_img_${productId}`).attr('src', event.target.result);
                        $(`#autocad_filename_${productId}`).text(file.name);
                        $(`#autocad_preview_${productId}`).show();
                    };
                    reader.readAsDataURL(file);

                    // upload and store mapping as hidden inputs
                    uploadTempFile(file, productId, 'autocad').then(data => {
                        const stepEl = $(`#stepsContainer .step[data-product-id="${productId}"]`);
                        // remove previous hidden inputs for autocad if any
                        stepEl.find('input[data-field="autocad-stored"]').remove();
                        stepEl.find('input[data-field="autocad-original"]').remove();

                        stepEl.append(`<input type="hidden" name="products[${productId}][autocad_uploaded_name]" data-field="autocad-stored" value="${data.storedName}">`);
                        stepEl.append(`<input type="hidden" name="products[${productId}][autocad_original_name]" data-field="autocad-original" value="${data.originalName}">`);
                    }).catch(() => {
                        toastr.error('Failed to upload AutoCAD image.');
                    });
                } else {
                    $(`#autocad_preview_${productId}`).hide();
                    // remove hidden inputs
                    const stepEl = $(`#stepsContainer .step[data-product-id="${productId}"]`);
                    stepEl.find('input[data-field="autocad-stored"]').remove();
                    stepEl.find('input[data-field="autocad-original"]').remove();
                }
            });

            // ========================================
            // EXTRA IMAGES PREVIEW
            // ========================================
            $(document).on('change', '.extra-images-input', function(e) {
                const productId = $(this).data('product-id');
                const files = Array.from(e.target.files);
                const previewContainer = $(`#extra_images_list_${productId}`);
                previewContainer.empty();

                // remove any previous hidden inputs for extras
                const stepEl = $(`#stepsContainer .step[data-product-id="${productId}"]`);
                stepEl.find('input[data-field="extra-stored"]').remove();
                stepEl.find('input[data-field="extra-original"]').remove();

                if (files.length > 0) {
                    $(`#extra_images_preview_${productId}`).show();

                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        const uid = Date.now() + '_' + Math.random().toString(36).substr(2, 5);
                        reader.onload = function(event) {
                            const imageCard = $(`
                            <div class="col-sm-6 col-md-4 col-lg-3 mb-3" data-temp-uid="${uid}">
                                <div class="card border-0 shadow-sm h-100 position-relative">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 deleteImage" 
                                            style="z-index: 10;" title="Delete image" data-stored-name="" data-temp-uid="${uid}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                            <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                        </svg>
                                    </button>
                                    <div class="card-body p-2">
                                        <img src="${event.target.result}" alt="Image ${index + 1}" class="img-fluid rounded other-image-preview" 
                                             style="max-height: 120px; width: 100%; object-fit: cover;">
                                        <p class="mt-2 mb-0 small text-truncate" title="${file.name}" style="font-size: 0.75rem; word-break: break-word;">
                                            <i class="ti ti-file-text"></i> ${file.name}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `);

                            previewContainer.append(imageCard);

                            // upload each file and append hidden inputs on success
                            uploadTempFile(file, productId, 'extra').then(data => {
                                // set stored name on delete button for this preview
                                previewContainer.find(`[data-temp-uid="${uid}"] .deleteImage`).attr('data-stored-name', data.storedName);

                                // append hidden inputs for the uploaded file mapping
                                stepEl.append(`<input type="hidden" name="products[${productId}][extra_uploaded_names][]" value="${data.storedName}" data-field="extra-stored" data-stored-name="${data.storedName}">`);
                                stepEl.append(`<input type="hidden" name="products[${productId}][extra_original_names][]" value="${data.originalName}" data-field="extra-original" data-stored-name="${data.storedName}">`);
                            }).catch(() => {
                                toastr.error('Failed to upload ' + file.name);
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                } else {
                    $(`#extra_images_preview_${productId}`).hide();
                }
            });

            // Delete image from preview and remove hidden mappings (and delete temp file on server)
            $(document).on('click', '.deleteImage', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const storedName = $btn.attr('data-stored-name');
                const tempUid = $btn.attr('data-temp-uid');
                // The structure is: button -> div.card -> div.col-[data-temp-uid]
                const $colWrapper = $btn.closest('.col-sm-6'); // This is the outer wrapper with data-temp-uid
                const $step = $colWrapper.closest('.step');
                const productId = $step.data('product-id');

                console.log('Delete clicked', { storedName, tempUid, productId, colExists: $colWrapper.length });

                // Always remove the card from UI immediately
                const $container = $(`#extra_images_list_${productId}`);
                
                if (storedName) {
                    // File was already uploaded, delete from server
                    deleteTempFile(storedName).then(() => {
                        console.log('Server deleted:', storedName);
                        // remove hidden inputs that map to this stored file
                        $(`#stepsContainer input[data-stored-name="${storedName}"]`).remove();
                    }).catch((err) => {
                        console.error('Server delete failed:', err);
                        // even if server delete fails, still remove the hidden inputs
                        $(`#stepsContainer input[data-stored-name="${storedName}"]`).remove();
                    }).finally(() => {
                        // Remove the card UI
                        $colWrapper.remove();
                        // Hide preview container if empty
                        if ($container.children().length === 0) {
                            $container.closest('[id^="extra_images_preview_"]').hide();
                        }
                    });
                } else {
                    // File not yet uploaded, just remove from UI
                    console.log('No stored name, removing card only');
                    $colWrapper.remove();
                    if ($container.children().length === 0) {
                        $container.closest('[id^="extra_images_preview_"]').hide();
                    }
                }
            });

            // ========================================
            // DESCRIPTION TABLE ROW MANAGEMENT
            // ========================================

            // Global "Add Row" button - adds row to current active step's table
            $(document).on('click', '.addRowBtn, #addRowBtn', function(e) {
                e.preventDefault();

                // Find the active step
                const activeStep = $('.step.active');
                if (!activeStep.length) {
                    toastr.error('No active product step found!', 'Error');
                    return;
                }

                // Get product ID from the active step
                const productId = activeStep.data('product-id');
                const tbody = $(`#descBody_${productId}`);

                if (!tbody.length) {
                    toastr.error('Description table not found!', 'Error');
                    return;
                }

                // Initialize counter if doesn't exist
                if (!rowIdxCounters[productId]) {
                    rowIdxCounters[productId] = 10; // Start after predefined rows
                }

                const rowIdx = rowIdxCounters[productId]++;

                tbody.append(`
                    <tr>
                        <td><input type="text" name="products[${productId}][descriptions][${rowIdx}][key]" class="form-control" placeholder="e.g. Thickness"></td>
                        <td><input type="text" name="products[${productId}][descriptions][${rowIdx}][value]" class="form-control" placeholder="e.g. 5mm"></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm removeRow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M2.75 6.167c0-.46.345-.834.771-.834h2.665c.529-.015.996-.378 1.176-.916l.03-.095l.115-.372c.07-.228.131-.427.217-.605c.338-.702.964-1.189 1.687-1.314c.184-.031.377-.031.6-.031h3.478c.223 0 .417 0 .6.031c.723.125 1.35.612 1.687 1.314c.086.178.147.377.217.605l.115.372l.03.095c.18.538.74.902 1.27.916h2.57c.427 0 .772.373.772.834S20.405 7 19.979 7H3.52c-.426 0-.771-.373-.771-.833"/>
                                    <path fill="currentColor" d="M11.607 22h.787c2.707 0 4.06 0 4.941-.863c.88-.864.97-2.28 1.15-5.111l.26-4.081c.098-1.537.147-2.305-.295-2.792s-1.187-.487-2.679-.487H8.23c-1.491 0-2.237 0-2.679.487s-.392 1.255-.295 2.792l.26 4.08c.18 2.833.27 4.248 1.15 5.112S8.9 22 11.607 22" opacity="0.5"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                `);

            });

            // Remove row
            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });


            // Remove row
            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            // ========================================
            // CUSTOMER & ADDRESS HANDLING
            // ========================================
            const $customer = $('#customer');
            const $addressSelect = $('#addressSelect');
            const $wrapper = $('#address-select-wrapper');
            const $textarea = $('#to-address');

            let defaultAddress = 1;

            // Initialize customer dropdown with Select2
            if ($customer.length) {
                $customer.select2({
                    width: '100%',
                    placeholder: 'Select a customer',
                    allowClear: true
                });

                // When a customer is selected
                $customer.on('change', function() {
                    const customerId = $(this).val();
                    $textarea.val('');
                    $wrapper.addClass('d-none');
                    $addressSelect.empty();

                    if (!customerId) return;

                    $.get(`/customers/${customerId}/addresses`, function(addresses) {
                        if (!addresses || addresses.length === 0) {
                            $textarea.val('No address found.');
                            return;
                        }

                        let needsAddressLineChoice = false;
                        let addressData = addresses[0];

                        if (addresses.length === 1) {
                            const addr = addresses[0];
                            // Set the single address ID to the hidden input
                            $addressSelect.val(addr.id);
                            
                            if (addr.address_line_1 && addr.address_line_2 && addr.address_line_2
                                .trim() !== '') {
                                needsAddressLineChoice = true;
                                addressData = addr;
                            } else {
                                const lineType = addr.address_line_1 ? 'line1' : 'line2';
                                defaultAddress = lineType === 'line1' ? 1 : 2;
                                $textarea.val(formatAddress(addr, lineType));
                            }
                        } else {
                            $wrapper.removeClass('d-none');
                            $addressSelect.append('<option value="">Select an address</option>');

                            addresses.forEach((a) => {
                                const text =
                                    `${a.address_line_1 || a.address_line_2}, ${a.city}`;
                                const option = new Option(text, a.id, a.is_default == 1, a
                                    .is_default == 1);
                                $addressSelect.append(option);
                            });

                            $addressSelect.data('addresses', addresses);
                            // Auto-select if one address has is_default = 1
                            if ($addressSelect.find(':selected').val()) {
                                $addressSelect.trigger('change');
                            }
                        }

                        if (needsAddressLineChoice) {
                            $wrapper.removeClass('d-none');
                            $addressSelect.append('<option value="">Select address line</option>');
                            $addressSelect.append(new Option(
                                `Line 1: ${addressData.address_line_1}`, 'line1'));
                            $addressSelect.append(new Option(
                                `Line 2: ${addressData.address_line_2}`, 'line2'));
                            $addressSelect.data('currentAddress', addressData);
                            $addressSelect.data('mode', 'lineChoice');
                        }
                    });
                });

                // Address dropdown change handler
                $addressSelect.on('change', function() {
                    const value = $(this).val();
                    const mode = $(this).data('mode');

                    if (mode === 'lineChoice') {
                        const currentAddress = $(this).data('currentAddress');
                        if (value && currentAddress) {
                            defaultAddress = value === 'line1' ? 1 : 2;
                            $textarea.val(formatAddress(currentAddress, value));
                        } else {
                            $textarea.val('');
                        }
                    } else {
                        const addresses = $(this).data('addresses') || [];
                        const selectedAddress = addresses.find(a => a.id == value);

                        if (selectedAddress) {
                            if (selectedAddress.address_line_1 && selectedAddress.address_line_2 &&
                                selectedAddress.address_line_2.trim() !== '') {
                                $addressSelect.empty();
                                $addressSelect.append('<option value="">Select address line</option>');
                                $addressSelect.append(new Option(
                                    `Line 1: ${selectedAddress.address_line_1}`, 'line1'));
                                $addressSelect.append(new Option(
                                    `Line 2: ${selectedAddress.address_line_2}`, 'line2'));
                                $addressSelect.data('currentAddress', selectedAddress);
                                $addressSelect.data('mode', 'lineChoice');
                            } else {
                                const lineType = selectedAddress.address_line_1 ? 'line1' : 'line2';
                                defaultAddress = lineType === 'line1' ? 1 : 2;
                                $textarea.val(formatAddress(selectedAddress, lineType));
                            }
                        } else {
                            $textarea.val('');
                        }
                    }
                });

                // Format address for textarea
                function formatAddress(a, lineChoice = 'line1') {
                    const addressLine = lineChoice === 'line2' ? (a.address_line_2 || a.address_line_1) : (a
                        .address_line_1 || a.address_line_2);
                    let formattedAddress = `   ${addressLine || ''}`;
                    formattedAddress += `,\n   ${a.city || ''}`;

                    if (a.district) {
                        formattedAddress += `,\n   ${a.district}`;
                    }
                    if (a.state) {
                        formattedAddress += `${a.district ? ', ' : ',\n   '}${a.state}`;
                    }
                    if (a.pincode) {
                        formattedAddress += ` - ${a.pincode}`;
                    }

                    formattedAddress += '.';
                    return formattedAddress;
                }
            }

            // ========================================
            // TOASTER NOTIFICATIONS
            // ========================================
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
