@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold text-xl mb-1">Waste Management</h3>
            <div class="text-gray-500 text-sm">Catat bahan terbuang, expired, rusak, atau pemakaian non-penjualan</div>
        </div>

        <a href="{{ route('tenant.admin.waste-records.create', $currentTenant->slug) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
            <i class="bi bi-plus-lg"></i>
            Tambah Waste
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-white border-b border-gray-100 p-5">
            <div class="flex flex-wrap justify-between items-center gap-3">
                <div>
                    <h5 class="font-bold text-lg mb-1">Riwayat Waste</h5>
                    <div class="text-gray-500 text-sm">Semua pengurangan stock karena waste tercatat di sini</div>
                </div>

                <form method="GET" class="flex gap-2">
                    <select name="reason" class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                        <option value="">Semua Reason</option>
                        @foreach(['expired','damaged','spillage','overcooked','staff_meal','other'] as $reason)
                            <option value="{{ $reason }}" {{ request('reason') === $reason ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $reason)) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Outlet</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($wastes as $waste)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 font-semibold text-gray-900">{{ $waste->code }}</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-3 py-1.5 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                {{ ucwords(str_replace('_', ' ', $waste->reason)) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-3 py-1.5 text-xs font-medium rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                {{ $waste->items->count() }} item
                            </span>
                        </td>
                        <td class="px-4 py-4 text-gray-600">{{ $waste->outlet?->name ?? '-' }}</td>
                        <td class="px-4 py-4">
                            <div class="font-semibold text-gray-900">{{ $waste->created_at?->format('d M Y') }}</div>
                            <small class="text-gray-500">{{ $waste->created_at?->format('H:i') }}</small>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('tenant.admin.waste-records.show', [$currentTenant->slug, $waste->id]) }}"
                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-full border border-blue-300 text-blue-600 hover:bg-blue-50 transition-colors">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                            <i class="bi bi-trash3 text-4xl block mb-2"></i>
                            Belum ada waste record.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($wastes->hasPages())
            <div class="bg-white border-t border-gray-100 px-5 py-3">
                {{ $wastes->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
