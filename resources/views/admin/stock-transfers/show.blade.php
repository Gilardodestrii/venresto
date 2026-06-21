@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold mb-1 text-xl">Detail Transfer Stock</h3>
            <div class="text-gray-500">{{ $stockTransfer->code }}</div>
        </div>

        <a href="{{ route('tenant.admin.stock-transfers.index', $currentTenant->slug) }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg font-medium transition-colors inline-flex items-center gap-1.5">
            <i class="bi bi-arrow-left"></i>
            Kembali
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

    @if($errors->any())
        <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm mb-4">
            <ul class="mb-0 ps-3 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white border-0 shadow-sm rounded-xl overflow-hidden">
                <div class="bg-white border-b border-gray-100 p-5">
                    <h5 class="font-bold mb-1">Item Transfer</h5>
                    <div class="text-gray-500 text-sm">Daftar bahan yang dipindahkan antar outlet</div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Bahan</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Unit</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse($stockTransfer->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-semibold text-sm">{{ $item->material?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item->material?->unit ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-bold">{{ number_format($item->qty, 3) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-12 text-gray-400">
                                    Belum ada item transfer.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white border-0 shadow-sm rounded-xl p-5">
                <h5 class="font-bold mb-4">Informasi Transfer</h5>

                <div class="mb-4">
                    <small class="text-gray-500 block">Kode</small>
                    <div class="font-bold">{{ $stockTransfer->code }}</div>
                </div>

                <div class="mb-4">
                    <small class="text-gray-500 block">Status</small>
                    @if($stockTransfer->status === 'pending')
                        <span class="bg-amber-100 text-amber-700 rounded-full px-3 py-1.5 text-sm font-medium inline-block mt-1">Pending</span>
                    @elseif($stockTransfer->status === 'completed')
                        <span class="bg-green-100 text-green-700 rounded-full px-3 py-1.5 text-sm font-medium inline-block mt-1">Completed</span>
                    @else
                        <span class="bg-gray-100 text-gray-600 rounded-full px-3 py-1.5 text-sm font-medium inline-block mt-1">Cancelled</span>
                    @endif
                </div>

                <div class="mb-4">
                    <small class="text-gray-500 block">Outlet Asal</small>
                    <div class="font-semibold">{{ $stockTransfer->fromOutlet?->name ?? '-' }}</div>
                </div>

                <div class="mb-4">
                    <small class="text-gray-500 block">Outlet Tujuan</small>
                    <div class="font-semibold">{{ $stockTransfer->toOutlet?->name ?? '-' }}</div>
                </div>

                <div class="mb-4">
                    <small class="text-gray-500 block">Catatan</small>
                    <div>{{ $stockTransfer->notes ?? '-' }}</div>
                </div>

                <div class="mb-4">
                    <small class="text-gray-500 block">Dibuat</small>
                    <div>{{ $stockTransfer->created_at?->format('d M Y H:i') }}</div>
                </div>

                @if($stockTransfer->completed_at)
                    <div class="mb-4">
                        <small class="text-gray-500 block">Diselesaikan</small>
                        <div>{{ $stockTransfer->completed_at?->format('d M Y H:i') }}</div>
                    </div>
                @endif

                @if($stockTransfer->status === 'pending')
                    <hr class="my-5 border-gray-200">

                    <form method="POST"
                          action="{{ route('tenant.admin.stock-transfers.complete', [$currentTenant->slug, $stockTransfer->id]) }}"
                          onsubmit="return confirm('Selesaikan transfer ini? Stock asal akan berkurang dan stock tujuan akan bertambah.')"
                          class="mb-3">
                        @csrf
                        <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg w-full font-medium transition-colors inline-flex items-center justify-center gap-1.5">
                            <i class="bi bi-check-circle"></i>
                            Complete Transfer
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('tenant.admin.stock-transfers.cancel', [$currentTenant->slug, $stockTransfer->id]) }}"
                          onsubmit="return confirm('Batalkan transfer ini?')">
                        @csrf
                        <button class="border border-red-500 text-red-500 hover:bg-red-50 px-5 py-2.5 rounded-lg w-full font-medium transition-colors inline-flex items-center justify-center gap-1.5">
                            <i class="bi bi-x-circle"></i>
                            Cancel Transfer
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
