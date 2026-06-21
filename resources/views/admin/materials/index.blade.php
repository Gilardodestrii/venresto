@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Bahan Baku</h3>
            <div class="text-sm text-gray-500">Kelola seluruh bahan baku inventory</div>
        </div>

        <a href="{{ route('tenant.admin.materials.create', $currentTenant->slug) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-blue-200">
            <i class="bi bi-plus-circle"></i>
            Tambah Bahan
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
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-3">
            <div>
                <h5 class="font-bold text-gray-900 mb-1">Daftar Bahan</h5>
                <div class="text-sm text-gray-500">{{ $materials->total() }} bahan tercatat</div>
            </div>
            <form method="GET" class="flex flex-wrap gap-2 items-end">
                <select name="outlet_filter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" onchange="this.form.submit()">
                    <option value="">Semua Outlet</option>
                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}" {{ request('outlet_filter') == $outlet->id ? 'selected' : '' }}>
                            {{ $outlet->name }}
                        </option>
                    @endforeach
                </select>
                <select name="category_filter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_filter') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bahan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Min. Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Outlet</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($materials as $material)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $material->name }}</div>
                                <div class="text-xs text-gray-500">{{ $material->sku ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $material->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $material->unit }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ number_format($material->stock, 2) }}</div>
                                @if($material->stock <= $material->min_stock)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <i class="bi bi-exclamation-triangle mr-1"></i> Low Stock
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ number_format($material->min_stock, 2) }}</td>
                            <td class="px-6 py-4 text-gray-700">Rp {{ number_format($material->price_per_unit, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $material->outlet->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                            class="p-2 rounded-lg hover:bg-blue-50 text-blue-600 transition-colors stock-btn"
                                            data-id="{{ $material->id }}"
                                            data-name="{{ $material->name }}"
                                            data-stock="{{ $material->stock }}"
                                            data-unit="{{ $material->unit }}"
                                            title="Update Stock">
                                        <i class="bi bi-box-seam"></i>
                                    </button>
                                    <a href="{{ route('tenant.admin.materials.edit', [$currentTenant->slug, $material->id]) }}"
                                       class="p-2 rounded-lg hover:bg-yellow-50 text-yellow-600 transition-colors" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('tenant.admin.materials.destroy', [$currentTenant->slug, $material->id]) }}"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Hapus bahan ini?')"
                                                class="p-2 rounded-lg hover:bg-red-50 text-red-600 transition-colors" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-box-seam text-4xl text-gray-300 block mb-2"></i>
                                Belum ada data bahan baku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($materials->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-white">
                {{ $materials->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>

@include('admin.materials.partials.stock-modal')

@endsection
