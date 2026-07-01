@extends('layouts.app')

@section('page-title', isset($pageTitle) ? $pageTitle : 'Admin Panel')



@section('layout-body')
<div class="flex min-h-screen bg-gray-50" x-data="{ sidebarOpen: false }">

    {{-- =========================
        SIDEBAR (mobile-responsive with Alpine.js)
    ========================== --}}
    <aside class="w-64 min-h-screen bg-gray-900 text-white flex flex-col fixed left-0 top-0 bottom-0 z-40
                  -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out"
           id="sidebar"
           :class="{ 'translate-x-0': sidebarOpen, 'shadow-2xl': sidebarOpen }">

        {{-- LOGO --}}
        <div class="px-6 py-5 border-b border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                    V
                </div>
                <div>
                    <div class="font-bold text-base leading-tight">VenResto</div>
                    <div class="text-xs text-gray-400 leading-tight">Restaurant POS</div>
                </div>
            </div>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2">Main</div>

            <a href="{{ route('tenant.admin.dashboard', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.dashboard*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-grid-1x2 text-base"></i>
                Dashboard
            </a>

            <a href="{{ route('tenant.admin.pos.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.pos*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-upc-scan text-base"></i>
                Point of Sale
            </a>

            <a href="{{ route('tenant.admin.orders.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.orders*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-receipt text-base"></i>
                Orders
            </a>

            <a href="{{ route('tenant.admin.kitchen.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.kitchen*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-fire text-base"></i>
                Kitchen Display
            </a>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mt-5 mb-2">Inventory</div>

            <a href="{{ route('tenant.admin.materials.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.materials*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-box-seam text-base"></i>
                Materials
            </a>

            <a href="{{ route('tenant.admin.recipes.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.recipes*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-book text-base"></i>
                Recipes
            </a>

            <a href="{{ route('tenant.admin.stock-movements.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.stock-movements*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-arrow-left-right text-base"></i>
                Stock Movements
            </a>

            <a href="{{ route('tenant.admin.waste-records.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.waste-records*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-trash3 text-base"></i>
                Waste Records
            </a>

            <a href="{{ route('tenant.admin.stock-transfers.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.stock-transfers*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-truck text-base"></i>
                Stock Transfers
            </a>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mt-5 mb-2">Catalog</div>

            <a href="{{ route('tenant.admin.menu-items.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.menu-items*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-cup-hot text-base"></i>
                Menu Items
            </a>

            <a href="{{ route('tenant.admin.menu-categories.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.menu-categories*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-tag text-base"></i>
                Categories
            </a>

            <a href="{{ route('tenant.admin.menu-costing.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.menu-costing*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-calculator text-base"></i>
                Menu Costing
            </a>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mt-5 mb-2">Management</div>

            <a href="{{ route('tenant.admin.outlets.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.outlets*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-shop text-base"></i>
                Outlets
            </a>

            <a href="{{ route('tenant.admin.cashier-sessions.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.cashier-sessions*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-cash-register text-base"></i>
                Cashier Sessions
            </a>

            @can('users.manage')
            <a href="{{ route('tenant.admin.roles.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.roles*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-people text-base"></i>
                Staff Management
            </a>
            @endcan

            @can('reports.view')
            <div x-data="{ reportsOpen: {{ Request::routeIs('tenant.admin.reports*') ? 'true' : 'false' }} }">
                <button @click="reportsOpen = !reportsOpen"
                   class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
                   {{ Request::routeIs('tenant.admin.reports*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <i class="bi bi-bar-chart text-base"></i>
                        Reports
                    </span>
                    <i class="bi text-xs transition-transform duration-200" :class="reportsOpen ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                </button>
                <div x-show="reportsOpen" x-transition class="ml-7 mt-1 space-y-0.5">
                    <a href="{{ route('tenant.admin.reports.sales', $currentTenant->slug) }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition-colors duration-200
                       {{ Request::routeIs('tenant.admin.reports.sales*') ? 'text-white bg-white/5' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i class="bi bi-graph-up text-xs"></i>
                        Sales Report
                    </a>
                    <a href="{{ route('tenant.admin.reports.profit', $currentTenant->slug) }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition-colors duration-200
                       {{ Request::routeIs('tenant.admin.reports.profit*') ? 'text-white bg-white/5' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i class="bi bi-cash-coin text-xs"></i>
                        Profit Report
                    </a>
                    <a href="{{ route('tenant.admin.reports.inventory', $currentTenant->slug) }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition-colors duration-200
                       {{ Request::routeIs('tenant.admin.reports.inventory*') ? 'text-white bg-white/5' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i class="bi bi-boxes text-xs"></i>
                        Inventory Report
                    </a>
                </div>
            </div>
            @endcan

            @can('settings.manage')
            <a href="{{ route('tenant.admin.settings.index', $currentTenant->slug) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('tenant.admin.settings*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-gear text-base"></i>
                Settings
            </a>
            @endcan

            <a href="{{ route('admin.qr.index', [$currentTenant->slug, $currentOutlet?->id ?? 0]) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
               {{ Request::routeIs('admin.qr*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-qr-code text-base"></i>
                QR Menu
            </a>

        </nav>

        {{-- USER FOOTER --}}
        <div class="px-4 py-4 border-t border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-400 truncate">{{ Auth::user()->getRoleNames()->first() ?? 'Staff' }}</div>
                </div>
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="p-1 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div x-show="open"
                         x-transition
                         class="absolute right-0 bottom-full mb-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50">
                        <a href="{{ route('tenant.admin.settings.index', $currentTenant->slug) }}"
                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="bi bi-gear"></i> Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </aside>

    {{-- BACKDROP for mobile sidebar --}}
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 z-30 lg:hidden">
    </div>

    {{-- =========================
        MAIN CONTENT
    ========================== --}}
    <div class="flex-1 lg:ml-64 flex flex-col min-w-0">

        {{-- TOPBAR --}}
        <header class="bg-white shadow-sm sticky top-0 z-30 px-3 sm:px-4 md:px-6 py-3 md:py-4">
            <div class="flex items-center justify-between gap-2 sm:gap-3">

                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors lg:hidden">
                        <i class="bi bi-list text-xl" x-show="!sidebarOpen"></i>
                        <i class="bi bi-x text-xl" x-show="sidebarOpen"></i>
                    </button>

                    {{-- Breadcrumb --}}
                    @hasSection('breadcrumbs')
                        @yield('breadcrumbs')
                    @else
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i class="bi bi-house"></i>
                            <span>/</span>
                            <span class="text-gray-700 font-medium">@yield('page-title', 'Dashboard')</span>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3">

                    @if(isset($currentOutlet) && $currentOutlet)
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-blue-50 rounded-lg text-sm">
                            <i class="bi bi-shop text-blue-600"></i>
                            <span class="font-medium text-blue-800">{{ $currentOutlet->name }}</span>
                        </div>
                    @endif

                    {{-- NOTIFICATION --}}
                    @php
                        $pendingOrdersCount = \App\Models\Order::where('status', 'pending')
                            ->when(isset($currentOutlet), fn($q) => $q->where('outlet_id', $currentOutlet->id))
                            ->when(Auth::user()->hasRole('cashier'), fn($q) => $q->where('cashier_id', Auth::id()))
                            ->count();
                    @endphp
                    <a href="{{ route('tenant.admin.orders.index', $currentTenant->slug) }}"
                       class="relative p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                        <i class="bi bi-bell text-xl"></i>
                        @if($pendingOrdersCount > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                {{ $pendingOrdersCount > 9 ? '9+' : $pendingOrdersCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Tenant Switcher --}}
                    @if(Auth::user()->hasRole('superadmin') && isset($tenants) && $tenants->count() > 1)
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                    class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium transition-colors">
                                <i class="bi bi-grid-3x3"></i>
                                <span class="hidden sm:inline">{{ $currentTenant->name }}</span>
                                <i class="bi bi-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open"
                                 x-transition
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50">
                                @foreach($tenants as $tenant)
                                    <form method="POST" action="{{ route('switch.tenant', $tenant->slug) }}" class="block">
                                        @csrf
                                        <button type="submit"
                                           class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-left {{ $tenant->id === $currentTenant->id ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                                            <i class="bi bi-building {{ $tenant->id === $currentTenant->id ? 'text-blue-600' : 'text-gray-400' }}"></i>
                                            {{ $tenant->name }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-3 sm:p-4 md:p-6">
            @yield('content')
        </main>

    </div>

</div>
@endsection

@push('scripts')
{{-- Sidebar toggle handled by Alpine.js (sidebarOpen state in parent div) --}}
@endpush
