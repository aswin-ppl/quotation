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
                    <ul class="mini-nav-ul" data-simplebar>

                        <!-- --------------------------------------------------------------------------------------------------------- -->
                        <!-- Dashboards -->
                        <!-- --------------------------------------------------------------------------------------------------------- -->
                        <li class="mini-nav-item {{ ($parent_title ?? 'Dashboard') === 'Dashboard' ? 'selected' : '' }}" id="mini-1">
                            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                                data-bs-placement="right" data-bs-title="Dashboards">
                                <iconify-icon icon="solar:layers-line-duotone" class="fs-7"></iconify-icon>
                            </a>
                        </li>

                        <!-- --------------------------------------------------------------------------------------------------------- -->
                        <!-- Master -->
                        <!-- --------------------------------------------------------------------------------------------------------- -->
                        <li class="mini-nav-item {{ ($parent_title ?? 'Dashboard') === 'Master' ? 'selected' : '' }}" id="mini-2">
                            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                                data-bs-placement="right" data-bs-title="Master">
                                <iconify-icon icon="solar:widget-6-line-duotone" class="fs-7"></iconify-icon>
                            </a>
                        </li>

                        <!-- --------------------------------------------------------------------------------------------------------- -->
                        <!-- User & Permissions -->
                        <!-- --------------------------------------------------------------------------------------------------------- -->
                        <li class="mini-nav-item {{ ($parent_title ?? 'Dashboard') === 'User & Permissions' ? 'selected' : '' }}" id="mini-3">
                            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                                data-bs-placement="right" data-bs-title="User & Permissions">
                                <iconify-icon icon="solar:shield-user-line-duotone" class="fs-7"></iconify-icon>
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
                            <!-- Header -->
                            <!-- ---------------------------------- -->
                            <li class="nav-small-cap">
                                <span class="hide-menu">Dashboards</span>
                            </li>
                            <!-- ---------------------------------- -->
                            <!-- Side bar -->
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
                    <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-2" data-simplebar>
                        <ul class="sidebar-menu" id="sidebarnav">
                            <!-- ---------------------------------- -->
                            <!-- Header -->
                            <!-- ---------------------------------- -->
                            <li class="nav-small-cap">
                                <span class="hide-menu">Master</span>
                            </li>
                            <!-- ---------------------------------- -->
                            <!-- Side bar -->
                            <!-- ---------------------------------- -->

                            <li class="sidebar-item">
                                <a href="/products" class="sidebar-link {{ ($parent_title ?? 'Dashboard') === 'Master' ? 'active' : '' }}">
                                    <iconify-icon icon="solar:waterdrops-line-duotone"></iconify-icon>
                                    <span class="hide-menu">Products</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- ---------------------------------- -->
                    <!-- User & Permissions -->
                    <!-- ---------------------------------- -->
                    <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-3" data-simplebar>
                        <ul class="sidebar-menu" id="sidebarnav">
                            <!-- ---------------------------------- -->
                            <!-- Header -->
                            <!-- ---------------------------------- -->
                            <li class="nav-small-cap">
                                <span class="hide-menu">User & Permissions</span>
                            </li>
                            <!-- ---------------------------------- -->
                            <!-- Side bar -->
                            <!-- ---------------------------------- -->

                            <li class="sidebar-item">
                                <a href="/users" class="sidebar-link {{ ($parent_title ?? 'Dashboard') === 'Users' ? 'active' : '' }}">
                                    <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                                    <span class="hide-menu">Users</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/roles" class="sidebar-link {{ ($parent_title ?? 'Dashboard') === 'Users' ? 'active' : '' }}">
                                    <iconify-icon icon="solar:clipboard-text-bold-duotone"></iconify-icon>
                                    <span class="hide-menu">Roles & Permissions</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </aside>
