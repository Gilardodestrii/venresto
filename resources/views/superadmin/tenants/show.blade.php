@extends('layouts.superadmin.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('superadmin.tenants.index') }}" class="p-2 rounded-full hover:bg-gray-200 text-gray-500 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $tenant->name }}</h2>
            <p class="text-gray-500">Slug: {{ $tenant->slug }}</p>
        </div>
    </div>
    
    <div>
        <form method="POST" action="{{ route('switch.tenant', $tenant->slug) }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Login Sebagai Tenant Ini
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Total Outlets</h3>
        <p class="text-3xl font-bold text-indigo-600">{{ number_format($stats['total_outlets']) }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Menu Items</h3>
        <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_items']) }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Staff / Users</h3>
        <p class="text-3xl font-bold text-emerald-600">{{ number_format($stats['total_users']) }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Total Transaksi</h3>
        <p class="text-3xl font-bold text-amber-600">{{ number_format($stats['total_orders']) }}</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h3 class="font-semibold text-gray-800">Informasi Owner</h3>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-sm text-gray-500 mb-1">Nama Owner</p>
            <p class="font-medium text-gray-900">{{ $tenant->owner ? $tenant->owner->name : 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Email Owner</p>
            <p class="font-medium text-gray-900">{{ $tenant->owner ? $tenant->owner->email : 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Tanggal Bergabung</p>
            <p class="font-medium text-gray-900">{{ $tenant->created_at ? $tenant->created_at->format('d M Y, H:i') : '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500 mb-1">Tenant ID</p>
            <p class="font-medium text-gray-900 font-mono text-sm bg-gray-100 px-2 py-1 rounded inline-block">{{ $tenant->id }}</p>
        </div>
    </div>
</div>
@endsection