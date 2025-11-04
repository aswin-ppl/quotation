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

            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-2 overflow-hidden hover-img">
                            <div class="position-relative">
                                <a href="">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png') }}"
                                        class="card-img-top rounded-0" alt="{{ $product->name }}">
                                </a>
                            </div>
                            <div class="card-body p-4">
                                <span class="badge text-bg-light fs-2 py-1 px-2 lh-sm  mt-3">{{ $product->size_mm }}
                                    mm</span>
                                <div class="d-flex align-items-center gap-4">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <a class="d-block my-4 fs-5 text-dark fw-semibold link-primary"
                                            href="/">{{ $product->name }}</a>
                                        {{-- <span class="d-block fs-5 text-mute fw-semibold link-primary"
                                            href="/">{{ $product->product_price }}</span> --}}
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center gap-2 fs-2 ms-auto">
                                        <a href="#" class="btn btn-sm btn-primary add-to-cart"
                                            data-product='@json($product)'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24">
                                                <g fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path
                                                        d="M7.5 18a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3Zm9 0a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3Z" />
                                                    <path stroke-linecap="round"
                                                        d="M13 13v-2m0 0V9m0 2h2m-2 0h-2M2 3l.261.092c1.302.457 1.953.686 2.325 1.231s.372 1.268.372 2.715V9.76c0 2.942.063 3.912.93 4.826c.866.914 2.26.914 5.05.914H12m4.24 0c1.561 0 2.342 0 2.894-.45c.551-.45.709-1.214 1.024-2.743l.5-2.424c.347-1.74.52-2.609.076-3.186c-.443-.577-1.96-.577-3.645-.577h-6.065m-6.066 0H7" />
                                                </g>
                                            </svg>
                                            &nbsp; Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
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
    </script>
@endsection
