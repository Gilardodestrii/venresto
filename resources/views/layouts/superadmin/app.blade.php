<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin | VenResto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased" x-data="{ sidebarOpen: false }">

    <!-- Topbar -->
    <header class="bg-blue-600 text-white shadow-md fixed w-full z-50">
        <div class="px-4 py-3 flex justify-between items-center h-14">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1.5 rounded-md hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex items-center gap-2">
                    <!-- Icon Dummy -->
                    <div class="w-7 h-7 bg-white/20 rounded flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <h1 class="text-xl font-bold tracking-tight">VenResto <span class="font-light text-blue-200 text-base">SUPER</span></h1>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden sm:block text-right">
                    <div class="text-sm font-bold leading-none">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-blue-200 uppercase tracking-wider font-semibold mt-1">System Admin</div>
                </div>
                <!-- Avatar Dummy -->
                <div class="w-8 h-8 rounded-full bg-blue-800 border-2 border-blue-400 flex items-center justify-center font-bold text-xs">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button class="bg-blue-700 hover:bg-blue-800 text-xs font-semibold px-3 py-1.5 rounded-md transition-colors shadow-sm border border-blue-600/50">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex pt-14 min-h-screen">
        <!-- Overlay untuk mobile -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 z-30 lg:hidden" style="display: none;"></div>

        <!-- Sidebar -->
        <aside class="bg-white w-64 border-r border-slate-200 fixed lg:sticky top-14 h-[calc(100vh-3.5rem)] z-40 transition-transform duration-300 transform lg:translate-x-0 overflow-y-auto"
               :class="{'translate-x-0 shadow-2xl': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            
            <div class="p-4">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 px-3">Menu Utama</div>
                <nav class="space-y-1">
                    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('superadmin.dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('superadmin.dashboard') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('superadmin.tenants.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('superadmin.tenants.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('superadmin.tenants.*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Manajemen Tenant
                    </a>
                </nav>
            </div>
            
            <div class="p-4 mt-4">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 px-3">Sistem</div>
                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-bold text-slate-700">All Systems Operational</span>
                    </div>
                    <div class="text-[10px] text-slate-500 font-medium">VenResto v1.0.0</div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-8 overflow-x-hidden z-0">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 text-emerald-800 p-4 rounded-xl border border-emerald-200 shadow-sm flex items-center gap-3 font-medium">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-rose-50 text-rose-800 p-4 rounded-xl border border-rose-200 shadow-sm flex items-center gap-3 font-medium">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>