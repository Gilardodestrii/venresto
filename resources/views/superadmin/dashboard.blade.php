@extends('layouts.superadmin.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Overview</h2>
        <p class="text-sm text-gray-500 mt-1">Statistik global dan performa sistem VenResto.</p>
    </div>
    <div class="flex items-center gap-2">
        <button class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg shadow-sm text-sm font-medium transition-all flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export Report
        </button>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-lg transition-shadow duration-300 relative overflow-hidden group">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full z-0 group-hover:scale-150 transition-transform duration-500 ease-in-out"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-1 uppercase tracking-wider">Total Tenants</h3>
                <p class="text-4xl font-black text-gray-900">{{ number_format($stats['total_tenants']) }}</p>
            </div>
            <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>
        <div class="relative z-10 mt-4 flex items-center text-sm">
            <span class="text-green-500 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                12%
            </span>
            <span class="text-gray-400 ml-2">dari bulan lalu</span>
        </div>
    </div>
    
    <!-- Stat Card 2 -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-lg transition-shadow duration-300 relative overflow-hidden group">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full z-0 group-hover:scale-150 transition-transform duration-500 ease-in-out"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-1 uppercase tracking-wider">Total Cabang</h3>
                <p class="text-4xl font-black text-gray-900">{{ number_format($stats['total_outlets']) }}</p>
            </div>
            <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
        </div>
        <div class="relative z-10 mt-4 flex items-center text-sm">
            <span class="text-green-500 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                8%
            </span>
            <span class="text-gray-400 ml-2">dari bulan lalu</span>
        </div>
    </div>
    
    <!-- Stat Card 3 -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-lg transition-shadow duration-300 relative overflow-hidden group">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-emerald-50 rounded-full z-0 group-hover:scale-150 transition-transform duration-500 ease-in-out"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-1 uppercase tracking-wider">Total Users</h3>
                <p class="text-4xl font-black text-gray-900">{{ number_format($stats['total_users']) }}</p>
            </div>
            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
        <div class="relative z-10 mt-4 flex items-center text-sm">
            <span class="text-gray-500 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                0%
            </span>
            <span class="text-gray-400 ml-2">Stagnan</span>
        </div>
    </div>
    
    <!-- Stat Card 4 -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-lg transition-shadow duration-300 relative overflow-hidden group">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-amber-50 rounded-full z-0 group-hover:scale-150 transition-transform duration-500 ease-in-out"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 mb-1 uppercase tracking-wider">Total Orders</h3>
                <p class="text-4xl font-black text-gray-900">{{ number_format($stats['total_orders']) }}</p>
            </div>
            <div class="p-3 bg-amber-100 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
        </div>
        <div class="relative z-10 mt-4 flex items-center text-sm">
            <span class="text-green-500 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                24%
            </span>
            <span class="text-gray-400 ml-2">dari bulan lalu</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Tenants List -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Tenant Terbaru</h3>
            <a href="{{ route('superadmin.tenants.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($stats['recent_tenants'] as $tenant)
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between hover:bg-gray-50 transition-colors gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-inner">
                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $tenant->name }}</h4>
                            <a href="{{ url('/' . $tenant->slug . '/admin/dashboard') }}" target="_blank" class="text-sm text-indigo-500 hover:underline flex items-center gap-1">
                                {{ $tenant->slug }}
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                        <span class="text-gray-400 font-medium min-w-[100px] text-right">
                            {{ $tenant->created_at ? $tenant->created_at->diffForHumans() : '-' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada tenant mendaftar bulan ini.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- System Status Widget -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-white">
            <h3 class="text-lg font-bold text-gray-900">Sistem Status</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm font-semibold text-gray-700">Database Storage</span>
                        <span class="text-xs font-bold text-gray-500">23%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 23%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm font-semibold text-gray-700">Server Load</span>
                        <span class="text-xs font-bold text-gray-500">45%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sm font-semibold text-gray-700">API Usage (Limit)</span>
                        <span class="text-xs font-bold text-red-500">85%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <p class="text-sm font-medium text-gray-700">Semua sistem berjalan normal</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection