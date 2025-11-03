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
            {{-- <nav aria-label="...">
                <ul class="pagination justify-content-center mb-0 mt-4">
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle text-dark round-32 d-flex align-items-center justify-content-center"
                            href="javascript:void(0)">
                            <i class="ti ti-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active" aria-current="page">
                        <a class="page-link border-0 rounded-circle round-32 mx-1 d-flex align-items-center justify-content-center"
                            href="javascript:void(0)">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle text-dark round-32 mx-1 d-flex align-items-center justify-content-center"
                            href="javascript:void(0)">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle text-dark round-32 mx-1 d-flex align-items-center justify-content-center"
                            href="javascript:void(0)">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle text-dark round-32 mx-1 d-flex align-items-center justify-content-center"
                            href="javascript:void(0)">4</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle text-dark round-32 mx-1 d-flex align-items-center justify-content-center"
                            href="javascript:void(0)">5</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle text-dark round-32 mx-1 d-flex align-items-center justify-content-center"
                            href="javascript:void(0)">...</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle text-dark round-32 mx-1 d-flex align-items-center justify-content-center"
                            href="javascript:void(0)">10</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link border-0 rounded-circle text-dark round-32 d-flex align-items-center justify-content-center"
                            href="javascript:void(0)">
                            <i class="ti ti-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav> --}}
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

        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            const badges = document.querySelectorAll('.cartCount');
            badges.forEach(badge => {
                badge.textContent = cart.length;
                badge.style.display = cart.length > 0 ? 'inline-block' : 'none';
            });
        }

        function updateCardDropDown(){
            const cart = JSON.parse(localStorage.getItem('cart')) || [];

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
            updateCardDropDown();
        }


        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const product = JSON.parse(btn.dataset.product);
                addToCart(product);
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            // updateCartCount();
            // updateCardDropDown();
        });

        // remove products
        // document.addEventListener('click', function(e) {
        //     if (e.target.classList.contains('remove-btn')) {
        //         let id = parseInt(e.target.getAttribute('data-id'));
        //         let cart = JSON.parse(localStorage.getItem('cart')) || [];
        //         cart = cart.filter(p => p.id !== id);
        //         localStorage.setItem('cart', JSON.stringify(cart));
        //         e.target.closest('.dropdown-item').remove();
        //         updateCartCount();
        //     }
        // });

        // localStorage.removeItem('cart');
    </script>
@endsection
