@extends('layouts.app')
@section('styles')
    <style>
        .card-img-top {
            width: 100%;
            height: 220px;
            object-fit: cover;
            object-position: center;
            border-radius: 0;
        }

        @media (min-width: 768px) {
            .card-img-top {
                height: 250px;
            }
        }

        @media (max-width: 767px) {
            .card-img-top {
                height: 180px;
            }
        }

        .search-wrapper {
            max-width: 425px;
            margin: 0 auto;
        }

        .search-wrapper input.form-control {
            transition: all 0.3s ease;
        }

        .search-wrapper input.form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            outline: none;
        }

        .search-wrapper .search-icon {
            pointer-events: none;
            z-index: 10;
        }

        .search-wrapper .clear-btn {
            z-index: 10;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .search-wrapper .clear-btn:hover {
            color: #dc3545 !important;
        }

        .search-wrapper .clear-btn:focus {
            box-shadow: none;
        }
    </style>
@endsection
@section('content')
    @php
        $parent_title = 'Dashboard';
        $page_title = 'Dashboard';
    @endphp
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="card card-body py-3">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="d-sm-flex align-items-center justify-space-between">
                            <h4 class="mb-4 mb-sm-0 card-title">Products</h4>
                            <nav aria-label="breadcrumb" class="ms-auto">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item d-flex align-items-center">
                                        <a class="text-muted text-decoration-none d-flex" href="/">
                                            <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                            View
                                        </span>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quotations summary cards --}}
            <div class="row my-4 g-3">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-muted">Total Quotations</h6>
                                    <h3 class="mb-0">{{ $quotationCounts['total'] ?? 0 }}</h3>
                                </div>
                                <div class="ms-3">
                                    <span class="badge bg-primary p-3 rounded-circle">
                                        <i class="ti ti-file-text fs-5 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-muted">Today</h6>
                                    <h3 class="mb-0">{{ $quotationCounts['today'] ?? 0 }}</h3>
                                </div>
                                <div class="ms-3">
                                    <span class="badge bg-success p-3 rounded-circle">
                                        <i class="ti ti-calendar-event fs-5 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-muted">This Week</h6>
                                    <h3 class="mb-0">{{ $quotationCounts['week'] ?? 0 }}</h3>
                                </div>
                                <div class="ms-3">
                                    <span class="badge bg-warning p-3 rounded-circle">
                                        <i class="ti ti-calendar-time fs-5 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-muted">This Month</h6>
                                    <h3 class="mb-0">{{ $quotationCounts['month'] ?? 0 }}</h3>
                                </div>
                                <div class="ms-3">
                                    <span class="badge bg-info p-3 rounded-circle">
                                        <i class="ti ti-calendar fs-5 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search form for products (live search) --}}
            <div class="row my-5">
                <div class="col-12">
                    <div class="search-wrapper position-relative">
                        <span class="search-icon position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <defs>
                                    <mask id="SVGcfiNQVot">
                                        <g fill="none" stroke-width="1.5">
                                            <circle cx="11.5" cy="11.5" r="9.5" stroke="#808080" />
                                            <path stroke="#fff" stroke-linecap="round" d="M18.5 18.5L22 22" />
                                        </g>
                                    </mask>
                                </defs>
                                <path fill="currentColor" d="M0 0h24v24H0z" mask="url(#SVGcfiNQVot)" />
                            </svg>
                        </span>
                        <input id="product-search" name="q" value="{{ request('q') }}" style="font-size: initial;"
                            class="form-control form-control-lg ps-5 pe-5 rounded-pill shadow-sm"
                            placeholder="Search products by name..." type="search" autocomplete="off">
                        <button id="product-search-clear"
                            class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-muted clear-btn"
                            type="button" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>


            <div id="products-container" class="row">
                @include('dashboard._products', ['products' => $products])
            </div>

            {{-- note: products partial includes its own pagination links --}}
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/plugins/toastr-init.js') }}"></script>
    <script>
        // toaster
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "timeOut": "3000"
        };

        function getCart() {
            return JSON.parse(localStorage.getItem('cart')) || [];
        }

        function saveCart(cart) {
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        function updateCartCount() {
            let cart = getCart();
            const badges = document.querySelectorAll('.cartCount');
            badges.forEach(badge => {
                badge.textContent = cart.length;
                badge.style.display = cart.length > 0 ? 'inline-block' : 'none';
            });
        }

        function updateCartDropdown() {
            const cart = getCart();
            const cartDropdown = document.getElementById('cartDropdown');

            if (!cartDropdown) return;

            if (cart.length === 0) {
                cartDropdown.innerHTML = `
            <p class="text-center text-muted py-3 mb-0">No products added 💤</p>
            `;
            } else {
                let html = '';
                cart.forEach(product => {
                    html += `
                <div class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                    <span class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                        <iconify-icon icon="solar:bag-2-outline"></iconify-icon>
                    </span>
                    <div class="w-75">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-1 fw-semibold">${product.name}</h6>
                        </div>
                        <span class="d-block text-truncate fs-11">₹${product.product_price} × ${product.qty || 1}</span>
                    </div>
                    <div>
                        <span>Qty : ${product.qty}</span>
                    </div>
                    <button class="btn btn-sm btn-danger ms-auto remove-btn" data-id="${product.id}">
                        ✕
                    </button>
                </div>
            `;
                });
                cartDropdown.innerHTML = html;
            }
        }

        function findProductInCart(productId) {
            const cart = getCart();
            return cart.find(item => item.id === productId);
        }

        function updateProductUI(productId, container) {
            const product = findProductInCart(productId);

            // Show add to cart button
            const productData = container.querySelector('[data-product]');
            const productJson = productData ? productData.getAttribute('data-product') : '{}';


            if (product && product.qty > 0) {
                // Show quantity controls
                container.innerHTML = `
            <div class="btn-group" role="group">
                <button class="btn btn-sm btn-outline-primary btn-decrease" data-id="${productId}">-</button>
                <button class="btn btn-sm btn-light px-3 disabled" data-product='${productJson}'>${product.qty}</button>
                <button class="btn btn-sm btn-primary btn-increase" data-id="${productId}">+</button>
            </div>
        `;
            } else {

                container.innerHTML = `
            <a href="#" class="btn btn-sm btn-primary add-to-cart" data-product='${productJson}'>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7.5 18a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3Zm9 0a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3Z"/><path stroke-linecap="round" d="M13 13v-2m0 0V9m0 2h2m-2 0h-2M2 3l.261.092c1.302.457 1.953.686 2.325 1.231s.372 1.268.372 2.715V9.76c0 2.942.063 3.912.93 4.826c.866.914 2.26.914 5.05.914H12m4.24 0c1.561 0 2.342 0 2.894-.45c.551-.45.709-1.214 1.024-2.743l.5-2.424c.347-1.74.52-2.609.076-3.186c-.443-.577-1.96-.577-3.645-.577h-6.065m-6.066 0H7"/></g></svg>
                &nbsp; Add to Cart
            </a>
        `;
            }
        }

        function addToCart(product) {
            let cart = getCart();

            // Check if product already exists
            const existingProduct = cart.find(item => item.id === product.id);

            if (existingProduct) {
                existingProduct.qty = (existingProduct.qty || 1) + 1;
                toastr.info("Quantity increased", "Cart Updated");
            } else {
                cart.push({
                    ...product,
                    qty: 1
                });
                toastr.success("Item added to the cart", "Success");
            }

            saveCart(cart);
            updateCartCount();
            updateCartDropdown();
        }

        function increaseQuantity(productId) {
            let cart = getCart();
            const product = cart.find(item => item.id === productId);

            if (product) {
                product.qty = (product.qty || 1) + 1;
                saveCart(cart);
                updateCartCount();
                updateCartDropdown();
                return true;
            }
            return false;
        }

        function decreaseQuantity(productId) {
            let cart = getCart();
            const productIndex = cart.findIndex(item => item.id === productId);

            if (productIndex !== -1) {
                cart[productIndex].qty = (cart[productIndex].qty || 1) - 1;

                if (cart[productIndex].qty <= 0) {
                    cart.splice(productIndex, 1);
                    toastr.info("Item removed from cart", "Cart Updated");
                }

                saveCart(cart);
                updateCartCount();
                updateCartDropdown();
                return true;
            }
            return false;
        }

        function removeFromCart(productId) {
            let cart = getCart();
            cart = cart.filter(item => item.id !== productId);
            saveCart(cart);
            updateCartCount();
            updateCartDropdown();
            toastr.info("Item removed from cart", "Removed");
        }

        // Event delegation for all cart actions
        document.addEventListener('click', function(e) {
            // Add to cart
            if (e.target.closest('.add-to-cart')) {
                e.preventDefault();
                const btn = e.target.closest('.add-to-cart');
                const product = JSON.parse(btn.getAttribute('data-product'));
                const container = btn.closest('.d-flex.justify-content-center, .d-flex.align-items-center');

                addToCart(product);
                if (container) {
                    updateProductUI(product.id, container);
                }
            }

            // Increase quantity
            if (e.target.closest('.btn-increase')) {
                e.preventDefault();
                const btn = e.target.closest('.btn-increase');
                const productId = parseInt(btn.getAttribute('data-id'));
                const container = btn.closest('.d-flex.justify-content-center, .d-flex.align-items-center');

                if (increaseQuantity(productId) && container) {
                    updateProductUI(productId, container);
                }
            }

            // Decrease quantity
            if (e.target.closest('.btn-decrease')) {
                e.preventDefault();
                const btn = e.target.closest('.btn-decrease');
                const productId = parseInt(btn.getAttribute('data-id'));
                const container = btn.closest('.d-flex.justify-content-center, .d-flex.align-items-center');

                if (decreaseQuantity(productId) && container) {
                    updateProductUI(productId, container);
                }
            }

            // Remove from dropdown
            if (e.target.closest('.remove-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.remove-btn');
                const productId = parseInt(btn.getAttribute('data-id'));

                removeFromCart(productId);

                // Update all product UIs
                document.querySelectorAll('.add-to-cart, .btn-increase, .btn-decrease').forEach(element => {
                    const id = element.getAttribute('data-id') ||
                        (element.getAttribute('data-product') ? JSON.parse(element.getAttribute(
                            'data-product')).id : null);
                    if (id == productId) {
                        const container = element.closest(
                            '.d-flex.justify-content-center, .d-flex.align-items-center');
                        if (container) {
                            updateProductUI(productId, container);
                        }
                    }
                });
            }

            //empty cart items
            if (e.target.closest('#empty-cart-items')) {
                localStorage.removeItem('cart');
                updateCartCount();
                updateCartDropdown();

                // Reset product buttons to default "Add to Cart" state
                // Find each product card and re-render its UI based on the now-empty cart
                document.querySelectorAll('.product-card').forEach(card => {
                    const productEl = card.querySelector('[data-product]');
                    if (!productEl) return;

                    // Prefer the closest container that wraps the add-to-cart button; fallback to parentElement
                    const container = productEl.closest('.d-flex.justify-content-center.align-items-center') || productEl.parentElement;
                    if (!container) return;

                    try {
                        const prod = JSON.parse(productEl.getAttribute('data-product'));
                        if (prod && prod.id) {
                            updateProductUI(prod.id, container);
                        }
                    } catch (err) {
                        // ignore parse errors
                    }
                });
            };
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            updateCartDropdown();

            // Initialize all product buttons with correct state
            document.querySelectorAll('.add-to-cart').forEach(btn => {
                const product = JSON.parse(btn.getAttribute('data-product'));
                const container = btn.closest('.d-flex.justify-content-center, .d-flex.align-items-center');
                if (container) {
                    updateProductUI(product.id, container);
                }
            });
        });

        // Live product search (debounced) -------------------------------------------------
        (function() {
            const input = document.getElementById('product-search');
            const clearBtn = document.getElementById('product-search-clear');
            const container = document.getElementById('products-container');
            if (!input || !container) return;

            let timeout = null;

            function setClearVisibility() {
                if (input.value && input.value.trim() !== '') {
                    clearBtn.style.display = 'inline-block';
                } else {
                    clearBtn.style.display = 'none';
                }
            }

            function fetchProducts(q, page = 1, pushHistory = true) {
                const url = new URL("{{ route('dashboard.products') }}", window.location.origin);
                if (q) url.searchParams.set('q', q);
                if (page) url.searchParams.set('page', page);

                fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(resp => resp.json())
                    .then(json => {
                        if (json && json.html !== undefined) {
                            container.innerHTML = json.html;
                            // re-run initialization for add-to-cart buttons (delegated handlers remain)
                            if (pushHistory) {
                                const stateUrl = new URL(window.location.href);
                                if (q) stateUrl.searchParams.set('q', q); else stateUrl.searchParams.delete('q');
                                if (page && page > 1) stateUrl.searchParams.set('page', page); else stateUrl.searchParams.delete('page');
                                history.pushState(null, '', stateUrl.toString());
                            }
                        }
                    }).catch(err => {
                        console.error('Failed to fetch products partial', err);
                    });
            }

            input.addEventListener('input', function(e) {
                setClearVisibility();
                const q = e.target.value;
                if (timeout) clearTimeout(timeout);
                timeout = setTimeout(() => fetchProducts(q, 1), 300);
            });

            clearBtn.addEventListener('click', function() {
                input.value = '';
                setClearVisibility();
                fetchProducts('', 1);
                history.replaceState(null, '', '{{ route('dashboard') }}');
            });

            // initial visibility
            setClearVisibility();

            // AJAX pagination: intercept clicks on pagination links inside products container
            container.addEventListener('click', function(ev) {
                const a = ev.target.closest('a');
                if (!a) return;
                const wrapper = a.closest('.pagination-wrapper');
                if (!wrapper) return;
                ev.preventDefault();
                try {
                    const linkUrl = new URL(a.href);
                    const page = linkUrl.searchParams.get('page') || 1;
                    const qParam = linkUrl.searchParams.get('q') || input.value || '';
                    fetchProducts(qParam, page);
                } catch (e) {
                    // fallback: follow link
                    window.location.href = a.href;
                }
            });
        })();
    </script>
@endsection
