@extends('layouts.admin')

@section('page-title', 'Inventory Report')

@section('content')

@php
    $startDate = $startDate ?? now()->startOfMonth();
    $endDate = $endDate ?? now();
    $stockInQty = $stockInQty ?? 0;
    $stockOutQty = $stockOutQty ?? 0;
    $wasteQty = $wasteQty ?? 0;
    $lowStockCount = $lowStockCount ?? 0;
    $transferInQty = $transferInQty ?? 0;
    $transferOutQty = $transferOutQty ?? 0;
    $topUsedMaterials = $topUsedMaterials ?? collect();
    $movements = $movements ?? new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
@endphp

<div class="container-fluid px-4 mx-auto">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold text-gray-900 mb-1">Inventory Report</h3>
            <div class="text-gray-500 text-sm">Analisa pergerakan stok dan pemakaian bahan</div>
        </div>

        <form method="GET" class="flex flex-wrap gap-2 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Dari</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sampai</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="text-gray-500 text-xs mb-1">Stock In</div>
            <h4 class="font-bold text-green-600 mb-0">{{ number_format($stockInQty, 3) }}</h4>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="text-gray-500 text-xs mb-1">Stock Out</div>
            <h4 class="font-bold text-red-600 mb-0">{{ number_format($stockOutQty, 3) }}</h4>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="text-gray-500 text-xs mb-1">Waste</div>
            <h4 class="font-bold text-yellow-600 mb-0">{{ number_format($wasteQty, 3) }}</h4>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="text-gray-500 text-xs mb-1">Low Stock</div>
            <h4 class="font-bold text-red-600 mb-0">{{ $lowStockCount }}</h4>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="border-b border-gray-100 px-5 py-4">
            <h5 class="font-bold text-gray-900 mb-1">Movement History</h5>
            <div class="text-gray-500 text-xs">Detail pergerakan stok sesuai filter tanggal</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium">Bahan</th>
                        <th class="px-4 py-3 font-medium">Type</th>
                        <th class="px-4 py-3 font-medium">Qty</th>
                        <th class="px-4 py-3 font-medium">Ref</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($movements as $movement)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <div class="font-semibold text-gray-900">{{ $movement->created_at?->format('d M Y') }}</div>
                                <small class="text-gray-400">{{ $movement->created_at?->format('H:i') }}</small>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $movement->material?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($movement->type === 'in')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">IN</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">OUT</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-bold text-gray-900">{{ number_format($movement->qty, 3) }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $movement->ref }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400">
                                <i class="bi bi-graph-up text-4xl d-block mb-2"></i>
                                Modul reports sedang disiapkan. Data movement akan tampil setelah controller report diaktifkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($movements, 'hasPages') && $movements->hasPages())
            <div class="border-t border-gray-100 px-5 py-3">
                {{ $movements->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
