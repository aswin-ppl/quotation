    <aside class="side-mini-panel with-vertical">
        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="iconbar">
            <div>
                <div class="mini-nav">
                    <div class="brand-logo d-flex align-items-center justify-content-center">
                        <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                            <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-7"></iconify-icon>
                        </a>
                    </div>
                    <p>{{ $parent_title  }}</p>
                    <ul class="mini-nav-ul" data-simplebar>

                        <!-- --------------------------------------------------------------------------------------------------------- -->
                        <!-- Dashboards -->
                        <!-- --------------------------------------------------------------------------------------------------------- -->
                        <li class="mini-nav-item {{ request()->is('dashboard*') ? 'selected' : '' }}" id="mini-1">
                            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                                data-bs-placement="right" data-bs-title="Dashboards">
                                <iconify-icon icon="solar:layers-line-duotone" class="fs-7"></iconify-icon>
                            </a>
                        </li>

                        <!-- --------------------------------------------------------------------------------------------------------- -->
                        <!-- Master -->
                        <!-- --------------------------------------------------------------------------------------------------------- -->
                        <li class="mini-nav-item {{ request()->is('products*') ? 'selected' : '' }}" id="mini-7">
                            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                                data-bs-placement="right" data-bs-title="Master">
                                <iconify-icon icon="solar:widget-6-line-duotone" class="fs-7"></iconify-icon>
                            </a>
                        </li>
                </div>
                <div class="sidebarmenu">
                    <div class="brand-logo d-flex align-items-center nav-logo">
                        <a href="index.html" class="text-nowrap logo-img">
                            <img src="{{ asset('images/logos/logo.svg') }}" alt="Logo" />
                        </a>

                    </div>
                    <!-- ---------------------------------- -->
                    <!-- Dashboard -->
                    <!-- ---------------------------------- -->
                    <nav class="sidebar-nav" id="menu-right-mini-1" data-simplebar>
                        <ul class="sidebar-menu" id="sidebarnav">
                            <!-- ---------------------------------- -->
                            <!-- Home -->
                            <!-- ---------------------------------- -->
                            <li class="nav-small-cap">
                                <span class="hide-menu">Dashboards</span>
                            </li>
                            <!-- ---------------------------------- -->
                            <!-- Dashboard -->
                            <!-- ---------------------------------- -->
                            <li class="sidebar-item">
                                <a href="index.php" class="sidebar-link {{ ($parent_title ?? 'Dashboard') === 'Dashboard' ? 'active' : '' }}">
                                    <iconify-icon icon="solar:atom-line-duotone"></iconify-icon>
                                    <span class="hide-menu">Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- ---------------------------------- -->
                    <!-- Master -->
                    <!-- ---------------------------------- -->
                    <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-7" data-simplebar>
                        <ul class="sidebar-menu" id="sidebarnav">
                            <!-- ---------------------------------- -->
                            <!-- Home -->
                            <!-- ---------------------------------- -->
                            <li class="nav-small-cap">
                                <span class="hide-menu">Master</span>
                            </li>
                            <!-- ---------------------------------- -->
                            <!-- Dashboard -->
                            <!-- ---------------------------------- -->

                            <li class="sidebar-item">
                                <a href="/products" class="sidebar-link {{ ($parent_title ?? 'Dashboard') === 'Master' ? 'active' : '' }}">
                                    <iconify-icon icon="solar:waterdrops-line-duotone"></iconify-icon>
                                    <span class="hide-menu">Products</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </aside>
