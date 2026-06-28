@extends('layouts.superadmin.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Tenant</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola dan monitor daftar resto klien yang menggunakan VenResto.</p>
    </div>
    <div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-sm text-sm font-medium transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Tenant Manual
        </button>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden mb-6">
    <!-- Filter Bar -->
    <div class="px-6 py-5 border-b border-gray-100 bg-white">
        <form method="GET" action="{{ route('superadmin.tenants.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1 max-w-lg">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari tenant, slug, atau nama owner..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow">
            </div>
            
            <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white rounded-xl hover:bg-gray-800 font-medium text-sm transition-colors shadow-sm">
                Cari Data
            </button>
            
            @if($search)
                <a href="{{ route('superadmin.tenants.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-medium text-sm transition-colors inline-flex items-center justify-center">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50/50 text-gray-500 font-semibold border-b border-gray-100 text-xs tracking-wider uppercase">
                <tr>
                    <th class="px-6 py-4">Tenant Info</th>
                    <th class="px-6 py-4">Informasi Owner</th>
                    <th class="px-6 py-4">Status & Bergabung</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($tenants as $tenant)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 font-bold border border-gray-200 shadow-sm">
                                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-base">{{ $tenant->name }}</div>
                                    <a href="{{ url('/' . $tenant->slug . '/admin/dashboard') }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1 mt-0.5">
                                        /{{ $tenant->slug }}
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($tenant->owner)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="font-medium text-gray-900">{{ $tenant->owner->name }}</span>
                                </div>
                                <div class="flex items-center gap-2 mt-1 text-gray-500">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $tenant->owner->email }}
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Belum Di-assign
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="mb-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    Active
                                </span>
                            </div>
                            <div class="text-gray-500 text-xs font-medium">
                                Sejak {{ $tenant->created_at ? $tenant->created_at->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('superadmin.tenants.show', $tenant->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors tooltip" title="Detail Tenant">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                
                                <form method="POST" action="{{ route('switch.tenant', $tenant->slug) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors tooltip" title="Login Sebagai Tenant">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="inline-flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <p class="text-gray-500 font-medium text-lg">Tidak ada tenant ditemukan</p>
                                <p class="text-gray-400 text-sm mt-1">Coba gunakan kata kunci lain untuk mencari.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tenants->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $tenants->links() }}
        </div>
    @endif
</div>
@endsection