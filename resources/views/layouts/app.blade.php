<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logos/favicon.png') }}" />

    <!-- Core Css -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />

    <title>Sherbrooke</title>

    @yield('styles')
</head>

<body>
    <!-- Toast -->
    {{-- <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <i class="ti ti-alert-circle fs-6"></i>
            <div>
                <h5 class="text-white fs-3 mb-1">Welcome back {{ auth()->user()->name ?? '' }}!!</h5>
                <h6 class="text-white fs-2 mb-0">Easy to costomize the Template!!!</h6>
            </div>
            <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none"
                data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div> --}}
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ asset('images/logos/favicon.png') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper">
        <!-- Sidebar Start -->
        {{-- <x-sidebar /> --}}
        @include('components.sidebar')
        <!--  Sidebar End -->
        <div class="page-wrapper">
            <!--  Header Start -->
            <x-header />
            <!--  Header End -->
            @include('components.horizontal-sidebar')

            @yield('content')

            <!--  Theme start Start -->
            <x-theme />
            <!--  Theme end Start -->

            <!--  Search Modal Start -->
            <x-search-modal />
            <!--  Search Modal End -->

        </div>

    </div>
    <div class="dark-transparent sidebartoggler"></div>
    <!-- Import Js Files -->
    <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('js/theme/app.init.js') }}"></script>
    <script src="{{ asset('js/theme/theme.js') }}"></script>
    <script src="{{ asset('js/theme/app.min.js') }}"></script>
    <script src="{{ asset('js/theme/sidebarmenu.js') }}"></script>

    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <!-- highlight.js (code view) -->
    <script src="{{ asset('js/highlights/highlight.min.js') }}"></script>
    <script>
        hljs.initHighlightingOnLoad();


        document.querySelectorAll("pre.code-view > code").forEach((codeBlock) => {
            codeBlock.textContent = codeBlock.innerHTML;
        });
    </script>
    {{-- <script src="{{ asset('libs/apexcharts/dist/apexcharts.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/dashboards/dashboard1.js') }}"></script> --}}
    <script src="{{ asset('libs/fullcalendar/index.global.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            const badges = document.querySelectorAll('.cartCount');
            badges.forEach(badge => {
                badge.textContent = cart.length;
                badge.style.display = cart.length > 0 ? 'inline-block' : 'none';
            });
        }

        function updateCardDropDown() {
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

        document.addEventListener('DOMContentLoaded', () => {
            updateCartCount();
            updateCardDropDown();
        });

        //remove cart items
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
    </script>
    @yield('scripts')
</body>

</html>
