<!doctype html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title','VenResto Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">


    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<div id="sidebar" class="sidebar p-3">

    <div class="sidebar-header">
        <strong class="text-primary-soft">VenResto</strong>
    </div>
        <button class="btn btn-sm btn-light" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <a href="{{ url($currentTenant->slug.'/admin/dashboard') }}" class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>
            <span class="text">Dashboard</span>
        </a>

        <a href="{{ route('tenant.admin.outlets.index',$currentTenant->slug) }}"
        class="sidebar-link {{ request()->routeIs('tenant.admin.outlets.*') ? 'active' : '' }}">
            <i class="bi bi-shop"></i>
            <span class="text">Outlet</span>
        </a>

        {{-- QR MENU --}}
        <a href="{{ url($currentTenant->slug.'/admin/outlets/'.$currentOutlet->id.'/qr') }}"
        class="sidebar-link">
            <i class="bi bi-qr-code"></i>
            <span class="text">QR Menu</span>
        </a>

        <a href="{{ url($currentTenant->slug.'/admin/menu-categories') }}" class="sidebar-link">
            <i class="bi bi-collection"></i>
            <span class="text">Kategori Menu</span>
        </a>

        <a href="{{ url($currentTenant->slug.'/admin/menu-items') }}" class="sidebar-link">
            <i class="bi bi-cup-straw"></i>
            <span class="text">Menu Item</span>
        </a>

        <a href="{{ url($currentTenant->slug.'/admin/pos') }}" class="sidebar-link">
            <i class="bi bi-cart-plus"></i>
            <span class="text">POS Kasir</span>
        </a>

        {{-- order --}}
        <a href="{{ url($currentTenant->slug.'/admin/orders') }}" class="sidebar-link">
            <i class="bi bi-basket"></i>
            <span class="text">Pesanan</span>
        </a>

        <a href="{{ url($currentTenant->slug.'/admin/kitchen') }}" class="sidebar-link">
            <i class="bi bi-display"></i>
            <span class="text">Kitchen Display</span>
        </a>

        <a href="{{ url('inventory') }}" class="sidebar-link">
            <i class="bi bi-box-seam"></i>
            <span class="text">Inventory</span>
        </a>

        <a href="{{ url('users') }}" class="sidebar-link">
            <i class="bi bi-shield-lock"></i>
            <span class="text">Role & Access</span>
        </a>

        <a href="{{ url('printer') }}" class="sidebar-link">
            <i class="bi bi-printer"></i>
            <span class="text">Printer</span>
        </a>

        <a href="{{ url('reports') }}" class="sidebar-link">
            <i class="bi bi-graph-up"></i>
            <span class="text">Reports</span>
        </a>

</div>

{{-- MAIN --}}
<div class="main">

    {{-- TOPBAR --}}
    <div class="topbar d-flex justify-content-between align-items-center p-3 px-4">

        <div class="d-flex align-items-center gap-2">
            <h6 class="mb-0 fw-bold">@yield('page-title','Dashboard')</h6>
        </div>

        <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-light d-md-none" onclick="toggleSidebarMobile()">
            <i class="bi bi-list"></i>
        </button>
            {{-- search fake SaaS --}}
            <input class="form-control form-control-sm" placeholder="Search...">
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleTheme()">
                <i class="bi bi-moon-stars"></i>
            </button>
            <div class="dropdown">
                <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    Admin
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ url('logout') }}">
                            @csrf
                            <button class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>

    </div>

    <div class="p-4">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>


@stack('scripts')

</body>
</html>