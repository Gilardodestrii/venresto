<!doctype html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title','VenResto Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    @php
        $lowStockMaterials = \App\Models\Material::query()
            ->where('tenant_id', $currentTenant->id)
            ->where('outlet_id', session('current_outlet_id'))
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->limit(5)
            ->get();
    @endphp

    @stack('styles')
</head>
<body>

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

        <a href="{{ url($currentTenant->slug.'/admin/cashier-sessions') }}" class="sidebar-link">
            <i class="bi bi-cash-stack"></i>
            <span class="text">Cashier Session</span>
        </a>

        <a href="{{ url($currentTenant->slug.'/admin/orders') }}" class="sidebar-link">
            <i class="bi bi-basket"></i>
            <span class="text">Pesanan</span>
        </a>

        <a href="{{ url($currentTenant->slug.'/admin/kitchen') }}" class="sidebar-link">
            <i class="bi bi-display"></i>
            <span class="text">Kitchen Display</span>
        </a>

        <a href="{{ route('tenant.admin.materials.index', $currentTenant->slug) }}"
        class="sidebar-link {{ request()->routeIs('tenant.admin.materials.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>Inventory</span>

            @if($lowStockMaterials->count())
                <span class="badge bg-danger rounded-pill ms-auto">
                    {{ $lowStockMaterials->count() }}
                </span>
            @endif
        </a>

        <a href="{{ route('tenant.admin.recipes.index', $currentTenant->slug) }}"
        class="sidebar-link {{ request()->routeIs('tenant.admin.recipes.*') ? 'active' : '' }}">
            <i class="bi bi-journal-check"></i>
            <span>Recipe</span>
        </a>

        <a href="{{ route('tenant.admin.stock-movements.index', $currentTenant->slug) }}"
        class="sidebar-link {{ request()->routeIs('tenant.admin.stock-movements.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Stock Movement</span>
        </a>

</div>

<div class="main">

    <div class="topbar d-flex justify-content-between align-items-center p-3 px-4">

        <div class="d-flex align-items-center gap-2">
            <h6 class="mb-0 fw-bold">@yield('page-title','Dashboard')</h6>
        </div>

        <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-light d-md-none" onclick="toggleSidebarMobile()">
            <i class="bi bi-list"></i>
        </button>

            @if($lowStockMaterials->count())
                <div class="dropdown">
                    <button class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Low Stock
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-3" style="width:320px;">
                        <div class="fw-bold mb-2">Low Stock Alert</div>

                        @foreach($lowStockMaterials as $material)
                            <div class="border rounded-3 p-2 mb-2">
                                <div class="fw-semibold">{{ $material->name }}</div>
                                <small class="text-danger">
                                    Stock: {{ number_format($material->stock, 2) }} {{ $material->unit }}
                                </small>
                            </div>
                        @endforeach

                        <a href="{{ route('tenant.admin.materials.index', $currentTenant->slug) }}"
                           class="btn btn-sm btn-dark w-100 rounded-3">
                            Buka Inventory
                        </a>
                    </div>
                </div>
            @endif

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