@extends('layouts.superadmin.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <a href="{{ route('superadmin.tenants.index') }}" class="p-2 rounded-full hover:bg-slate-200 text-slate-500 transition-colors bg-white border border-slate-200 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $tenant->name }}</h2>
                @if($tenant->status === 'active')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">ACTIVE</span>
                @elseif($tenant->status === 'trialing')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">TRIALING</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">{{ strtoupper($tenant->status) }}</span>
                @endif
            </div>
            <p class="text-slate-500 text-sm mt-1 font-medium">Slug: <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-700 border border-slate-200">{{ $tenant->slug }}</span></p>
        </div>
    </div>
    
    <div>
        <form method="POST" action="{{ route('switch.tenant', $tenant->slug) }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold flex items-center gap-2 shadow-sm transition-colors w-full sm:w-auto justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Login Sebagai Tenant Ini
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        </div>
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Outlets</h3>
            <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_outlets']) }}</p>
        </div>
    </div>
    
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Menu Items</h3>
            <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_items']) }}</p>
        </div>
    </div>
    
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Staff / Users</h3>
            <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_users']) }}</p>
        </div>
    </div>
    
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Orders</h3>
            <p class="text-2xl font-black text-slate-800">{{ number_format($stats['total_orders']) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Info Owner -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <h3 class="font-bold text-slate-800">Informasi Owner & Sistem</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Owner</p>
                <p class="font-bold text-slate-800">{{ $tenant->owner ? $tenant->owner->name : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Email Owner</p>
                @if($tenant->owner)
                    <a href="mailto:{{ $tenant->owner->email }}" class="font-bold text-blue-600 hover:underline">{{ $tenant->owner->email }}</a>
                @else
                    <p class="font-bold text-slate-800">N/A</p>
                @endif
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Daftar</p>
                <p class="font-bold text-slate-800">
                    @if($tenant->created_at)
                        {{ $tenant->created_at->format('d M Y, H:i') }}
                    @else
                        -
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">System ID</p>
                <p class="font-mono text-sm font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded border border-slate-200 inline-block">#{{ $tenant->id }}</p>
            </div>
        </div>
    </div>

    <!-- Info Billing & Plan -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            <h3 class="font-bold text-slate-800">Billing & Langganan</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-100">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Paket Saat Ini</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xl font-black text-slate-800">
                            {{ $tenant->plan_id == 1 ? 'Starter Plan' : ($tenant->plan_id == 2 ? 'Pro Plan' : 'Plan ID: ' . $tenant->plan_id) }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status</p>
                    @if($tenant->status === 'active')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">ACTIVE</span>
                    @elseif($tenant->status === 'trialing')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">TRIALING</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">{{ strtoupper($tenant->status) }}</span>
                    @endif
                </div>
            </div>
            
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Masa Aktif / Trial</p>
                @if($tenant->trial_ends_at)
                    @php
                        $daysLeft = now()->diffInDays($tenant->trial_ends_at, false);
                    @endphp
                    
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-semibold text-slate-700">Berakhir pada: {{ $tenant->trial_ends_at->format('d M Y') }}</span>
                        @if($daysLeft > 0)
                            <span class="text-sm font-bold text-blue-600">{{ $daysLeft }} hari lagi</span>
                        @else
                            <span class="text-sm font-bold text-rose-600">Expired</span>
                        @endif
                    </div>
                    
                    <!-- Progress Bar (dummy visual) -->
                    <div class="w-full bg-slate-100 rounded-full h-2.5 mb-1 overflow-hidden">
                        @if($daysLeft > 0)
                            @php $pct = min(100, max(5, ($daysLeft / 14) * 100)); @endphp
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                        @else
                            <div class="bg-rose-500 h-2.5 rounded-full" style="width: 100%"></div>
                        @endif
                    </div>
                @else
                    <p class="text-sm font-semibold text-slate-700">Tidak ada batas trial aktif.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection