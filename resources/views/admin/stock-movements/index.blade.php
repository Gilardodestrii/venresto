@extends('layouts.admin')

@section('content')

<div class="container-fluid mx-auto px-4">

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
        <div>
            <h3 class="font-bold mb-1">Stock Movement</h3>
            <div class="text-gray-500">
                Riwayat keluar masuk stok bahan baku
            </div>
        </div>

        <a href="{{ route('tenant.admin.materials.index', $currentTenant->slug) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors">
            <i class="bi bi-box-seam"></i>
            Inventory
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl shadow-sm bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="text-gray-500 text-sm mb-1">Total Movement</div>
            <h4 class="font-bold mb-0">{{ $movements->total() }}</h4>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="text-gray-500 text-sm mb-1">Stok Masuk</div>
            <h4 class="font-bold text-green-600 mb-0">
                {{ $movements->where('type', 'in')->count() }}
            </h4>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="text-gray-500 text-sm mb-1">Stok Keluar</div>
            <h4 class="font-bold text-red-600 mb-0">
                {{ $movements->where('type', 'out')->count() }}
            </h4>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-white border-b border-gray-100 p-4">
            <div class="flex flex-wrap justify-between items-center gap-3">
                <div>
                    <h5 class="font-bold mb-1">Riwayat Pergerakan Stok</h5>
                    <div class="text-gray-500 text-sm">
                        Semua transaksi stok masuk dan stok keluar
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bahan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ref</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($movements as $movement)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="font-semibold">
                                    {{ $movement->created_at?->format('d M Y') }}
                                </div>
                                <small class="text-gray-400">
                                    {{ $movement->created_at?->format('H:i') }}
                                </small>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-semibold">
                                    {{ $movement->material?->name ?? '-' }}
                                </div>
                                <small class="text-gray-400">
                                    Unit: {{ $movement->material?->unit ?? '-' }}
                                </small>
                            </td>

                            <td class="px-4 py-3">
                                @if($movement->type === 'in')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                        <i class="bi bi-arrow-down-circle"></i>
                                        Masuk
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                        <i class="bi bi-arrow-up-circle"></i>
                                        Keluar
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <span class="font-bold {{ $movement->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $movement->type === 'in' ? '+' : '-' }}
                                    {{ number_format($movement->qty, 3) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $movement->ref ?? '-' }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-semibold">
                                    {{ $movement->creator?->name ?? 'System' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="mb-2">
                                    <i class="bi bi-clock-history text-4xl text-gray-300"></i>
                                </div>
                                <div class="font-semibold">Belum ada stock movement</div>
                                <div class="text-gray-400 text-sm">
                                    Riwayat stok akan muncul setelah ada stok masuk atau stok keluar.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movements->hasPages())
            <div class="bg-white border-t border-gray-100 px-4 py-3">
                {{ $movements->links() }}
            </div>
        @endif
    </div>

</div>

@endsection