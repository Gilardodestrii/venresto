<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin | VenResto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js (kalau belum include di app.js) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <!-- Topbar (z-50 agar selalu di atas) -->
    <header class="bg-slate-900 text-white shadow-lg fixed top-0 w-full z-50">
        <div class="px-4 h-16 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -ml-2 rounded-md hover:bg-slate-800 text-slate-300 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-xl font-bold tracking-tight bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">VenResto <span class="font-light text-slate-300">Superadmin</span></h1>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-sm font-bold text-white shadow-inner">
                        {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'S' }}
                    </div>
                    <span class="text-sm font-medium text-slate-200">{{ auth()->user()->name ?? 'Superadmin' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-slate-800 hover:bg-red-500 hover:text-white text-slate-300 text-sm px-4 py-2 rounded-lg transition-all shadow-sm">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Overlay transparan untuk nutup sidebar di mobile -->
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden" style="display: none;"></div>

    <div class="flex pt-16 min-h-screen">
        <!-- Sidebar (z-40 agar di bawah header tapi di atas konten) -->
        <aside class="bg-white w-64 border-r border-slate-200 fixed lg:sticky top-16 h-[calc(100vh-4rem)] z-40 transition-transform duration-300 transform lg:translate-x-0 overflow-y-auto"
               :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <nav class="p-4 space-y-2">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('superadmin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('superadmin.dashboard') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                
                <a href="{{ route('superadmin.tenants.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('superadmin.tenants.*') ? 'bg-indigo-50 text-indigo-700 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('superadmin.tenants.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Manajemen Tenant
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full overflow-hidden relative z-0">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200 shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-rose-50 text-rose-700 p-4 rounded-xl border border-rose-200 shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>