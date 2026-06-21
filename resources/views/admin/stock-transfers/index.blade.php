@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold mb-1 text-xl">Transfer Stock</h3>
            <div class="text-gray-500">Kelola perpindahan bahan baku antar outlet</div>
        </div>

        <a href="{{ route('tenant.admin.stock-transfers.create', $currentTenant->slug) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-colors inline-flex items-center gap-1.5">
            <i class="bi bi-plus-lg"></i>
            Buat Transfer
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border-0 shadow-sm rounded-xl p-5">
            <div class="text-gray-500 text-sm mb-1">Total Transfer</div>
            <h4 class="font-bold mb-0 text-lg">{{ $transfers->total() }}</h4>
        </div>

        <div class="bg-white border-0 shadow-sm rounded-xl p-5">
            <div class="text-gray-500 text-sm mb-1">Pending</div>
            <h4 class="font-bold text-amber-500 mb-0">
                {{ $transfers->where('status', 'pending')->count() }}
            </h4>
        </div>

        <div class="bg-white border-0 shadow-sm rounded-xl p-5">
            <div class="text-gray-500 text-sm mb-1">Completed</div>
            <h4 class="font-bold text-green-600 mb-0">
                {{ $transfers->where('status', 'completed')->count() }}
            </h4>
        </div>
    </div>

    <div class="bg-white border-0 shadow-sm rounded-xl overflow-hidden">
        <div class="bg-white border-b border-gray-100 p-5">
            <h5 class="font-bold mb-1">Riwayat Transfer</h5>
            <div class="text-gray-500 text-sm">Semua transaksi transfer stock antar outlet</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Kode</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Dari Outlet</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Ke Outlet</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Item</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transfers as $transfer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold text-sm">{{ $transfer->code }}</td>
                            <td class="px-4 py-3 text-sm">{{ $transfer->fromOutlet?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $transfer->toOutlet?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-gray-100 text-gray-800 border border-gray-200 rounded-full px-3 py-1.5 text-sm font-medium">
                                    {{ $transfer->items->count() }} item
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($transfer->status === 'pending')
                                    <span class="bg-amber-100 text-amber-700 rounded-full px-3 py-1.5 text-sm font-medium">Pending</span>
                                @elseif($transfer->status === 'completed')
                                    <span class="bg-green-100 text-green-700 rounded-full px-3 py-1.5 text-sm font-medium">Completed</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 rounded-full px-3 py-1.5 text-sm font-medium">Cancelled</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-sm">{{ $transfer->created_at?->format('d M Y') }}</div>
                                <small class="text-gray-400">{{ $transfer->created_at?->format('H:i') }}</small>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('tenant.admin.stock-transfers.show', [$currentTenant->slug, $transfer->id]) }}"
                                   class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-1.5 rounded-full text-sm font-medium transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-400">
                                <i class="bi bi-arrow-left-right fs-1 d-block mb-2"></i>
                                Belum ada transfer stock.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transfers->hasPages())
            <div class="bg-white border-t border-gray-100 px-5 py-4">
                {{ $transfers->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
