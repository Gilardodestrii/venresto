@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold text-xl mb-1">Detail Waste Record</h3>
            <div class="text-gray-500">{{ $wasteRecord->code }}</div>
        </div>

        <a href="{{ route('tenant.admin.waste-records.index', $currentTenant->slug) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-white border-b border-gray-100 p-5">
                    <h5 class="font-bold text-lg mb-1">Item Waste</h5>
                    <div class="text-gray-500 text-sm">Bahan yang dikurangi dari inventory</div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bahan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty Waste</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($wasteRecord->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 font-semibold text-gray-900">{{ $item->material?->name ?? '-' }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ $item->material?->unit ?? '-' }}</td>
                                    <td class="px-4 py-4">
                                        <span class="font-bold text-red-600">
                                            -{{ number_format($item->qty, 3) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-12 text-center text-gray-500">
                                        Tidak ada item waste.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="p-5">
                    <h5 class="font-bold text-lg mb-4">Informasi Waste</h5>

                    <div class="mb-4">
                        <small class="text-gray-500 text-xs uppercase tracking-wide">Kode</small>
                        <div class="font-bold text-gray-900">{{ $wasteRecord->code }}</div>
                    </div>

                    <div class="mb-4">
                        <small class="text-gray-500 text-xs uppercase tracking-wide">Reason</small><br>
                        <span class="inline-flex px-3 py-1.5 text-sm font-medium rounded-full bg-red-100 text-red-700 mt-1">
                            {{ ucwords(str_replace('_', ' ', $wasteRecord->reason)) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <small class="text-gray-500 text-xs uppercase tracking-wide">Outlet</small>
                        <div class="font-semibold text-gray-900">{{ $wasteRecord->outlet?->name ?? '-' }}</div>
                    </div>

                    <div class="mb-4">
                        <small class="text-gray-500 text-xs uppercase tracking-wide">Catatan</small>
                        <div class="text-gray-700">{{ $wasteRecord->notes ?? '-' }}</div>
                    </div>

                    <div class="mb-4">
                        <small class="text-gray-500 text-xs uppercase tracking-wide">Tanggal</small>
                        <div class="text-gray-900">{{ $wasteRecord->created_at?->format('d M Y H:i') }}</div>
                    </div>

                    <hr class="my-5 border-gray-200">

                    <a href="{{ route('tenant.admin.stock-movements.index', $currentTenant->slug) }}"
                       class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-colors">
                        <i class="bi bi-clock-history"></i>
                        Lihat Stock Movement
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
