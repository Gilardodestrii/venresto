@extends('layouts.superadmin.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Tenant</h2>
        <p class="text-slate-500 font-medium">Daftar semua resto/cafe yang menggunakan VenResto.</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200 bg-slate-50/50">
        <form method="GET" action="{{ route('superadmin.tenants.index') }}" class="flex flex-col sm:flex-row gap-2 max-w-xl">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama tenant, slug, atau email owner..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-slate-700">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 font-bold shadow-sm transition-colors flex-1 sm:flex-none">Cari</button>
                @if($search)
                    <a href="{{ route('superadmin.tenants.index') }}" class="px-5 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 font-bold transition-colors flex items-center justify-center">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Tenant Info</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Owner</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($tenants as $tenant)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-700 flex items-center justify-center font-black text-lg border border-blue-200 shadow-sm shrink-0">
                                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-base">{{ $tenant->name }}</div>
                                    <div class="text-slate-500 text-xs font-mono mt-0.5">{{ $tenant->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($tenant->owner)
                                <div class="font-bold text-slate-700">{{ $tenant->owner->name }}</div>
                                <div class="text-slate-500">{{ $tenant->owner->email }}</div>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-500">No Owner</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($tenant->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">ACTIVE</span>
                            @elseif($tenant->status === 'trialing')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">TRIALING</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">{{ strtoupper($tenant->status) }}</span>
                            @endif
                            <div class="text-xs text-slate-400 mt-1 font-medium">Sejak {{ $tenant->created_at->format('M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 font-bold text-xs transition-colors border border-blue-200">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Detail
                                </a>
                                
                                <form method="POST" action="{{ route('switch.tenant', $tenant->slug) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-700 rounded-md hover:bg-slate-200 font-bold text-xs transition-colors border border-slate-200">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                        Login As
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <div class="text-slate-500 font-medium">Tidak ada data tenant ditemukan.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tenants->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-white">
            {{ $tenants->links() }}
        </div>
    @endif
</div>
@endsection