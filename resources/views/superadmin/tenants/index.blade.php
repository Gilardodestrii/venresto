@extends('layouts.superadmin.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Tenant</h2>
        <p class="text-gray-500">Daftar semua resto/cafe yang menggunakan VenResto.</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ route('superadmin.tenants.index') }}" class="flex gap-2 max-w-md">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama tenant, slug, atau email owner..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">Cari</button>
            @if($search)
                <a href="{{ route('superadmin.tenants.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-200 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-3">Tenant Info</th>
                    <th class="px-6 py-3">Owner</th>
                    <th class="px-6 py-3">Bergabung Sejak</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($tenants as $tenant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $tenant->name }}</div>
                            <div class="text-gray-500">{{ $tenant->slug }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($tenant->owner)
                                <div class="text-gray-900">{{ $tenant->owner->name }}</div>
                                <div class="text-gray-500">{{ $tenant->owner->email }}</div>
                            @else
                                <span class="text-red-500 italic">No Owner</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $tenant->created_at ? $tenant->created_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('superadmin.tenants.show', $tenant->id) }}" class="inline-block px-3 py-1.5 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 font-medium text-xs">Detail</a>
                            
                            <form method="POST" action="{{ route('switch.tenant', $tenant->slug) }}" class="inline-block">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 font-medium text-xs">Login As</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada data tenant ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tenants->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $tenants->links() }}
        </div>
    @endif
</div>
@endsection