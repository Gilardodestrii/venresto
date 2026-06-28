@extends('layouts.superadmin.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Ikhtisar Sistem</h2>
        <p class="text-slate-500 text-sm mt-1">Statistik global dari seluruh jaringan VenResto.</p>
    </div>
    <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm text-sm font-medium text-slate-600">
        <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></div>
        System Operational
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
    <div class="bg-white p-5 lg:p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform duration-500 ease-out"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Tenants</h3>
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ number_format($stats['total_tenants']) }}</p>
            <p class="text-xs text-slate-400 mt-2 font-medium">Resto/Brand terdaftar</p>
        </div>
    </div>
    
    <div class="bg-white p-5 lg:p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-110 transition-transform duration-500 ease-out"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Cabang</h3>
                <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ number_format($stats['total_outlets']) }}</p>
            <p class="text-xs text-slate-400 mt-2 font-medium">Outlet beroperasi</p>
        </div>
    </div>
    
    <div class="bg-white p-5 lg:p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform duration-500 ease-out"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Staff & Users</h3>
                <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ number_format($stats['total_users']) }}</p>
            <p class="text-xs text-slate-400 mt-2 font-medium">Akun pekerja aktif</p>
        </div>
    </div>
    
    <div class="bg-white p-5 lg:p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform duration-500 ease-out"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Transaksi</h3>
                <div class="p-2 bg-amber-100 text-amber-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ number_format($stats['total_orders']) }}</p>
            <p class="text-xs text-slate-400 mt-2 font-medium">Order diproses sistem</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Tenants -->
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Tenant Baru Mendaftar</h3>
            <a href="{{ route('superadmin.tenants.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                Lihat Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        <div class="divide-y divide-slate-100 flex-1">
            @forelse($stats['recent_tenants'] as $tenant)
                <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center hover:bg-slate-50 transition-colors gap-3">
                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex h-10 w-10 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 border border-blue-300 items-center justify-center text-blue-700 font-bold text-lg uppercase shadow-sm">
                            {{ substr($tenant->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-slate-900">{{ $tenant->name }}</h4>
                                @if($tenant->status === 'active')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Active</span>
                                @elseif($tenant->status === 'trialing')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">Trial</span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-500 font-medium">{{ url('/' . $tenant->slug . '/admin/dashboard') }}</p>
                        </div>
                    </div>
                    <div class="text-xs font-medium text-slate-400 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200 w-full sm:w-auto text-center">
                        @if($tenant->created_at)
                            {{ $tenant->created_at->diffForHumans() }}
                        @else
                            -
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center flex flex-col items-center">
                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <p class="text-slate-500 font-medium">Belum ada data tenant.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Status & Akses</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50 flex items-start gap-3">
                <div class="mt-0.5 w-2 h-2 rounded-full bg-emerald-500"></div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Database Connection</h4>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Connected to Primary DB</p>
                </div>
            </div>
            <div class="p-4 rounded-lg border border-slate-200 bg-slate-50 flex items-start gap-3">
                <div class="mt-0.5 w-2 h-2 rounded-full bg-emerald-500"></div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Storage & Assets</h4>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Write permissions OK</p>
                </div>
            </div>
            
            <hr class="border-slate-200 my-4">
            
            <a href="{{ url('/') }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Lihat Landing Page
            </a>
        </div>
    </div>
</div>
@endsection