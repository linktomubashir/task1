<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="/dashboard" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="brand-image opacity-100 shadow">
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Task 1</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <!-- Dashboard Menu Item -->
                <li class="nav-item {{ request()->is('dashboard') ? 'active menu-open' : '' }}">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon bi bi-house-door"></i> <!-- Updated to Bootstrap icon -->
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Components Header -->
                <li class="nav-header">COMPONENTS</li>

                <!-- Home Page Menu -->
                <li class="nav-item {{ request()->is('item') ? 'active menu-open' : '' }}">
                    <a href="{{ url('item') }}" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i> <!-- Font Awesome icon (can be customized) -->
                        <p>Item Page</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('brands') ? 'active menu-open' : '' }}">
                    <a href="{{ url('brands') }}" class="nav-link">
                        <i class="nav-icon fas fa-building"></i> <!-- Font Awesome icon (can be customized) -->
                        <p>Brands Page</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('models') ? 'active menu-open' : '' }}">
                    <a href="{{ url('models') }}" class="nav-link">
                        <i class="nav-icon fas fa-box"></i> 
                        <p>Models Page</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('email') ? 'active menu-open' : '' }}">
                    <a href="{{ url('email') }}" class="nav-link">
                        <i class="fas fa-envelope"></i>
                        <p>Email</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
