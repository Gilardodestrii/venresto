@extends('layouts.superadmin.app')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Overview</h2>
    <p class="text-gray-500">Statistik global sistem VenResto.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Total Tenants (Resto)</h3>
        <p class="text-3xl font-bold text-indigo-600">{{ number_format($stats['total_tenants']) }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Total Outlets (Cabang)</h3>
        <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_outlets']) }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Total Users (Staff)</h3>
        <p class="text-3xl font-bold text-emerald-600">{{ number_format($stats['total_users']) }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Total Orders (Transaksi)</h3>
        <p class="text-3xl font-bold text-amber-600">{{ number_format($stats['total_orders']) }}</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
        <h3 class="font-semibold text-gray-800">Tenant Baru Mendaftar</h3>
        <a href="{{ route('superadmin.tenants.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua &rarr;</a>
    </div>
    <div class="divide-y divide-gray-200">
        @forelse($stats['recent_tenants'] as $tenant)
            <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                <div>
                    <h4 class="font-medium text-gray-900">{{ $tenant->name }}</h4>
                    <p class="text-sm text-gray-500">{{ url('/' . $tenant->slug . '/admin/dashboard') }}</p>
                </div>
                <div class="text-sm text-gray-400">
                    {{ $tenant->created_at ? $tenant->created_at->diffForHumans() : '-' }}
                </div>
            </div>
        @empty
            <div class="px-6 py-8 text-center text-gray-500">Belum ada data tenant.</div>
        @endforelse
    </div>
</div>
@endsection