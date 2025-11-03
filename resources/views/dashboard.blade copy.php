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
                                        <a class="text-muted text-decoration-none d-flex" href="../main/index.html">
                                            <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                            Blog
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
                                    <div class="d-flex align-items-center gap-2">

                                        <a class="d-block my-4 fs-5 text-dark fw-semibold link-primary"
                                            href="../main/blog-detail.html">{{ $product->name }}</a>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center gap-2 fs-2 ms-auto">
                                        <a href="#" class="btn btn-sm btn-primary add-to-cart"
                                            data-product='@json($product)'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24">
                                                <path fill="none" stroke="currentColor" stroke-width="1.5"
                                                    d="M13 13v-2m0 0V9m0 2h2m-2 0h-2M2 3l.261.092c1.302.457 1.953.686 2.325 1.231s.372 1.268.372 2.715V9.76c0 2.942.063 3.912.93 4.826c.866.914 2.26.914 5.05.914H12" />
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

    {{-- <script>
        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            const badges = document.querySelectorAll('.cartCount');
            badges.forEach(badge => {
                badge.textContent = cart.length;
                badge.style.display = cart.length > 0 ? 'inline-block' : 'none';
            });
        }

        function addToCart(product) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Avoid duplicates
            if (!cart.some(item => item.id === product.id)) {
                cart.push(product);
            } else {
                toastr.warning("Item already added", "Already Exists!");
                return;
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            toastr.success("Item added to the cart", "Success");

            updateCartCount();
        }


        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const product = JSON.parse(btn.dataset.product);
                addToCart(product);
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            updateCartCount();

            // Step 2: Select your dropdown container
            const cartDropdown = document.getElementById('cartDropdown');

            // Step 3: Check if products exist
            if (cart.length === 0) {
                cartDropdown.innerHTML = `
                                <p class="text-center text-muted py-3 mb-0">No products added 💤</p>
                            `;
            } else {
                // Step 4: Loop and create product items
                let html = '';
                cart.forEach(product => {
                    html += `
                    <div class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                        <span
                            class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                            <iconify-icon icon="solar:bag-2-outline"></iconify-icon>
                        </span>

                        <div class="w-75">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="mb-1 fw-semibold">${product.name}</h6>
                            </div>
                            <span class="d-block text-truncate fs-11">₹${product.product_price}</span>
                        </div>

                        <button class="btn btn-sm btn-danger ms-auto remove-btn" data-id="${product.id}" data-name="${product.name}">
                            ✕
                        </button>
                    </div>

                            `;
                });

                // Step 5: Append all items
                cartDropdown.innerHTML = html;
            }




            function saveCart() {
                localStorage.setItem('cart', JSON.stringify(cart));
            }

            function updateCartUI(productId, container) {
                const productInCart = cart[productId];
                if (productInCart) {
                    container.innerHTML = `
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-outline-primary decrease" data-product="${JSON.stringify(cart[productId] || {})}" data-id="${productId}">-</button>
                    <button class="btn btn-sm btn-light px-3 disabled">${productInCart.qty}</button>
                    <button class="btn btn-sm btn-outline-primary increase" data-product="${JSON.stringify(cart[productId] || {})}" data-id="${productId}">+</button>
                </div>
            `;
                } else {
                    container.innerHTML = `
                <a href="#" class="btn btn-sm btn-primary add-to-cart" data-product="${JSON.stringify(cart[productId] || {})}" data-id="${productId}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-width="1.5" 
                              d="M13 13v-2m0 0V9m0 2h2m-2 0h-2M2 3l.261.092c1.302.457 1.953.686 2.325 1.231s.372 1.268.372 2.715V9.76c0 2.942.063 3.912.93 4.826c.866.914 2.26.914 5.05.914H12"/>
                    </svg>
                    &nbsp; Add to Cart
                </a>
            `;
                }
            }

            document.body.addEventListener('click', function(e) {
                const target = e.target.closest('.add-to-cart, .increase, .decrease');
                if (!target) return;

                e.preventDefault();

                const productId = target.dataset.id;
                const card = target.closest('div.d-flex');

                if (target.classList.contains('add-to-cart')) {
                    console.log(target.dataset);
                    const product = JSON.parse(target.dataset.product);
                    cart[productId] = {
                        ...product,
                        qty: 1
                    };
                    saveCart();
                    updateCartUI(productId, card);
                }

                if (target.classList.contains('increase')) {
                    cart[productId].qty++;
                    saveCart();
                    updateCartUI(productId, card);
                }

                if (target.classList.contains('decrease')) {
                    cart[productId].qty--;
                    if (cart[productId].qty <= 0) delete cart[productId];
                    saveCart();
                    updateCartUI(productId, card);
                }
            });

            // On load - render correct state
            document.querySelectorAll('.add-to-cart').forEach(btn => {
                const productId = JSON.parse(btn.dataset.product).id;
                const container = btn.closest('div.d-flex');
                updateCartUI(productId, container);
            });
        });

        // remove products
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-btn')) {
                let id = parseInt(e.target.getAttribute('data-id'));
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart = cart.filter(p => p.id !== id);
                localStorage.setItem('cart', JSON.stringify(cart));
                e.target.closest('.dropdown-item').remove();
                updateCartCount();
            }
        });

        // localStorage.removeItem('cart');
    </script> --}}

    <script>
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

            if (product && product.qty > 0) {
                // Show quantity controls
                container.innerHTML = `
            <div class="btn-group" role="group">
                <button class="btn btn-sm btn-outline-primary btn-decrease" data-id="${productId}">-</button>
                <button class="btn btn-sm btn-light px-3 disabled">${product.qty}</button>
                <button class="btn btn-sm btn-outline-primary btn-increase" data-id="${productId}">+</button>
            </div>
        `;
            } else {
                // Show add to cart button
                const productData = container.querySelector('[data-product]');
                const productJson = productData ? productData.getAttribute('data-product') : '{}';

                container.innerHTML = `
            <a href="#" class="btn btn-sm btn-primary add-to-cart" data-product='${productJson}'>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-width="1.5" 
                          d="M13 13v-2m0 0V9m0 2h2m-2 0h-2M2 3l.261.092c1.302.457 1.953.686 2.325 1.231s.372 1.268.372 2.715V9.76c0 2.942.063 3.912.93 4.826c.866.914 2.26.914 5.05.914H12"/>
                </svg>
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
