@extends('layouts.app')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="{{ asset('libs/quill/dist/quill.snow.css') }}"> --}}
    <style>
        .ql-container {
            height: 150px !important;
        }

        .select2-selection__arrow {
            display: none !important;
        }

        #to-address {
            white-space: pre-line;
        }

        .quotation-preview-wrapper {
            border: 1px solid #1e40af !important;
            border-radius: 10px;
            background: white;
        }
    </style>
@endsection
@section('content')
    @php
        $parent_title = 'Dashboard';
        $page_title = 'Quotation';
    @endphp
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="card card-body py-3">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="d-sm-flex align-items-center justify-space-between">
                            <h4 class="mb-4 mb-sm-0 card-title">Quotation</h4>
                            <nav aria-label="breadcrumb" class="ms-auto">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item d-flex align-items-center">
                                        <a class="text-muted text-decoration-none d-flex" href="/">
                                            <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">view</span>
                                        {{-- <a href="{{ url('/quotation/' . $quotation->id . '/pdf') }}"
                                            class="btn btn-primary">
                                            Download PDF
                                        </a> --}}

                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
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
                                        <label for="addressSelect" class="form-label">Select Address</label>
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

                    <button class="btn btn-primary mt-3" id="generatePreview">Generate Preview</button>
                </div>
            </div>
            <div class="card">
                <div class="table-responsive border rounded-4">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Product</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">R/Units</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Price</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Qty</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Action</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="card overflow-auto text-dark" id="pdf_content" style="display: none;color: black !important;"></div>
            <!-- Hide till ready -->

            <div class="preview-container d-none">
                <div class="row text-center my-2">
                    <h2>Quotation Preview</h2>
                </div>
                <div class="quotation-preview-wrapper" style="width:100%; height:80vh; border:none;">
                    <iframe id="quotation-preview-container" style="width:100%; height:100%; border:none;"></iframe>
                </div>

                {{-- <div id="quotation-preview-container"></div>    --}}
                <div class="row align-items-center mt-3 justify-content-center">
                    <div class="col-md-6 text-center">
                        <button id="downloadPdf" data-id="" data-address="" class="btn btn-primary">Download PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/plugins/toastr-init.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const STORAGE_URL = '{{ asset('storage/') }}/';
        window.jsPDF = window.jspdf.jsPDF;

        const iframe = document.getElementById('quotation-preview-container');

        iframe.onload = function() {
            // Get iframe's document safely
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            // Make sure the iframe is same-origin, otherwise you can’t touch it
            if (!iframeDoc) {
                console.error("Iframe content not accessible (different origin)");
                return;
            }

            // Select all images *inside the iframe*
            const imgs = iframeDoc.querySelectorAll("img");

            // Remove that cursed absolute path
            imgs.forEach(img => {
                img.src = img.src.replace(/^.*\/storage\//, '/storage/');
            });
        };

        $(function() {

            /* ------------------------------------
               CUSTOMER & ADDRESS HANDLING
            ------------------------------------ */
            const $customer = $('#customer');
            const $addressSelect = $('#addressSelect');
            const $wrapper = $('#address-select-wrapper');
            const $textarea = $('#to-address');

            // Track which address line is selected (1 or 2)
            let defaultAddress = 1;

            // Initialize customer dropdown
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

                    // Check if we need to show address line selector
                    let needsAddressLineChoice = false;
                    let addressData = addresses[0]; // default to first address

                    if (addresses.length === 1) {
                        // Single address record - check if it has both lines
                        const addr = addresses[0];
                        if (addr.address_line_1 && addr.address_line_2 && addr.address_line_2
                            .trim() !== '') {
                            needsAddressLineChoice = true;
                            addressData = addr;
                        } else {
                            // Only one line available, display directly
                            const lineType = addr.address_line_1 ? 'line1' : 'line2';
                            defaultAddress = lineType === 'line1' ? 1 : 2;
                            $textarea.val(formatAddress(addr, lineType));
                        }
                    } else {
                        // Multiple address records - let user choose which record first
                        $wrapper.removeClass('d-none');
                        $addressSelect.append('<option value="">Select an address</option>');
                        addresses.forEach((a, index) => {
                            const text =
                                `${a.address_line_1 || a.address_line_2}, ${a.city}`;
                            $addressSelect.append(new Option(text, index));
                        });
                        $addressSelect.data('addresses', addresses);
                        return;
                    }

                    // Show address line chooser if needed
                    if (needsAddressLineChoice) {
                        $wrapper.removeClass('d-none');
                        $addressSelect.append('<option value="">Select address line</option>');
                        $addressSelect.append(new Option(`Line 1: ${addressData.address_line_1}`,
                            'line1'));
                        $addressSelect.append(new Option(`Line 2: ${addressData.address_line_2}`,
                            'line2'));
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
                    // Choosing between address_line_1 or address_line_2
                    const currentAddress = $(this).data('currentAddress');
                    if (value && currentAddress) {
                        defaultAddress = value === 'line1' ? 1 : 2;
                        $textarea.val(formatAddress(currentAddress, value));
                    } else {
                        $textarea.val('');
                    }
                } else {
                    // Choosing between multiple address records
                    const addresses = $(this).data('addresses') || [];
                    const selectedAddress = addresses[value];

                    if (selectedAddress) {
                        // Check if this address has both lines
                        if (selectedAddress.address_line_1 && selectedAddress.address_line_2 &&
                            selectedAddress.address_line_2.trim() !== '') {
                            // Show line chooser for this address
                            $addressSelect.empty();
                            $addressSelect.append('<option value="">Select address line</option>');
                            $addressSelect.append(new Option(`Line 1: ${selectedAddress.address_line_1}`,
                                'line1'));
                            $addressSelect.append(new Option(`Line 2: ${selectedAddress.address_line_2}`,
                                'line2'));
                            $addressSelect.data('currentAddress', selectedAddress);
                            $addressSelect.data('mode', 'lineChoice');
                        } else {
                            // Only one line, display directly
                            const lineType = selectedAddress.address_line_1 ? 'line1' : 'line2';
                            defaultAddress = lineType === 'line1' ? 1 : 2;
                            $textarea.val(formatAddress(selectedAddress, lineType));
                        }
                    } else {
                        $textarea.val('');
                    }
                }
            });

            // Format address for textarea - uses selected line
            function formatAddress(a, lineChoice = 'line1') {
                // Choose which address line to use
                const addressLine = lineChoice === 'line2' ? (a.address_line_2 || a.address_line_1) : (a
                    .address_line_1 || a.address_line_2);

                let formattedAddress = `   ${addressLine || ''}`;

                formattedAddress += `,\n   ${a.city || ''}`;

                // Add district, state, and pincode
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

            /* ------------------------------------
               CART MANAGEMENT
            ------------------------------------ */
            const tableBody = document.getElementById('cartTableBody');

            function getCart() {
                return JSON.parse(localStorage.getItem('cart')) || [];
            }

            function saveCart(cart) {
                localStorage.setItem('cart', JSON.stringify(cart));
            }

            function updateCartCount(count) {
                document.querySelectorAll('.cartCount').forEach(el => {
                    el.textContent = count;
                    el.style.display = count > 0 ? 'inline-block' : 'none';
                });
            }

            async function renderCart() {
                const cart = getCart();
                tableBody.innerHTML = '';

                if (cart.length === 0) {
                    tableBody.innerHTML =
                        `<tr><td colspan="5" class="text-center text-muted py-4">Your cart is empty 🛒</td></tr>`;
                    return;
                }

                // Extract all IDs
                const ids = cart.map(item => item.id);

                try {
                    // Fetch product data from backend
                    const response = await fetch(`/cart/products?ids[]=${ids.join('&ids[]=')}`);
                    const products = await response.json();

                    if (products.length === 0) {
                        tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4">
            Some products no longer exist 
        </td></tr>`;
                        localStorage.removeItem('cart');
                        updateCartCount(0);
                        return;
                    }

                    // Render rows
                    products.forEach(product => {
                        const cartItem = cart.find(p => p.id == product.id);
                        const qty = cartItem ? cartItem.qty : 1;
                        const row = document.createElement('tr');
                        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <img src="/storage/${product.image || 'images/no-image.png'}"
                         class="rounded-2" width="42" height="42" alt="${product.name}">
                    <div class="ms-3">
                        <h6 class="fw-semibold mb-1 text-capitalize">${product.name}</h6>
                        <span class="fw-normal">${product.size_mm || ''} mm</span>
                    </div>
                </div>
            </td>
            <td><span class="fw-normal">${parseFloat(product.r_units || 0).toFixed(2)}</span></td>
            <td><span class="fw-normal">₹${parseFloat(product.product_price || 0).toFixed(2)}</span></td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-primary btn-qty-decrease" data-id="${product.id}">-</button>
                    <span class="fw-semibold px-2 fs-4">${qty}</span>
                    <button class="btn btn-sm btn-outline-primary btn-qty-increase" data-id="${product.id}">+</button>
                </div>
            </td>
            <td>
                <button class="btn btn-sm bg-danger-subtle text-danger btn-delete" data-id="${product.id}">✕</button>
            </td>`;
                        tableBody.appendChild(row);
                    });

                    updateCartCount(cart.length);

                } catch (err) {
                    console.error('Error fetching cart data:', err);
                    tableBody.innerHTML =
                        `<tr><td colspan="5" class="text-center text-danger py-4">Failed to load cart </td></tr>`;
                }
            }


            function changeQuantity(productId, delta) {
                let cart = getCart();
                const product = cart.find(p => p.id == productId);
                if (!product) return;

                product.qty = Math.max(1, (product.qty || 1) + delta);
                saveCart(cart);
                renderCart();
            }

            function deleteProduct(productId) {
                let cart = getCart().filter(item => item.id != productId);
                saveCart(cart);
                renderCart();
                if (typeof toastr !== 'undefined') toastr.success("Product removed from cart", "Removed");
            }

            tableBody.addEventListener('click', function(e) {
                const btn = e.target.closest('button');
                if (!btn) return;
                const id = btn.dataset.id;
                if (btn.classList.contains('btn-qty-increase')) changeQuantity(id, 1);
                else if (btn.classList.contains('btn-qty-decrease')) changeQuantity(id, -1);
                else if (btn.classList.contains('btn-delete')) deleteProduct(id);
            });

            renderCart();

            $('#generatePreview').on('click', async function() {
                const customerId = $('#customer').val();
                const cart = JSON.parse(localStorage.getItem('cart')) || [];

                if (!customerId) {
                    toastr.error("Please select a customer", "Missing Data");
                    return;
                }
                if (cart.length === 0) {
                    toastr.error("Your cart is empty", "Missing Items");
                    return;
                }

                // Prepare items data
                const items = cart.map(item => {
                    // Convert descriptions array → { key: value }
                    const description = {};
                    if (Array.isArray(item.descriptions)) {
                        item.descriptions.forEach(desc => {
                            description[desc.key] = desc.value;
                        });
                    }

                    return {
                        product_id: item.id,
                        product_name: item.name,
                        size_mm: item.size_mm || '',
                        r_units: item.r_units || '',
                        description: description, // proper JSON object now
                        quantity: item.qty || 1,
                        unit_price: parseFloat(item.product_price) || 0,
                        total: ((item.qty || 1) * parseFloat(item.product_price || 0))
                    };
                });

                try {
                    const response = await fetch("{{ route('quotation.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            customer_id: customerId,
                            items: items,
                            defaultAddress: defaultAddress
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to store quotation');
                    }

                    const result = await response.json();
                    const quotationId = result.id;

                    // Now hit the preview route
                    const previewUrl = `/quotation/${quotationId}/${defaultAddress}/preview`;

                    const previewResponse = await fetch(previewUrl);
                    if (!previewResponse.ok) {
                        throw new Error('Failed to fetch quotation preview');
                    }

                    const previewHTML = await previewResponse.text();

                    document.getElementById('downloadPdf').setAttribute('data-id', quotationId);
                    document.getElementById('downloadPdf').setAttribute('data-address', defaultAddress);
                    document.getElementById('quotation-preview-container').src = previewUrl;
                    document.querySelector('.preview-container').classList.remove('d-none');
                } catch (err) {
                    console.error(err);
                    toastr.error("Failed to generate quotation", "Error");
                }

            });

            $('#downloadPdf').on('click', async function() {
                let quotationId = this.getAttribute('data-id');
                let defaultAddress = this.getAttribute('data-address');

                window.location.href = `/quotation/${quotationId}/${defaultAddress}/download`;
                localStorage.removeItem('cart');
            });

        });
    </script>
@endsection
