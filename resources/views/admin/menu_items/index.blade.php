@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Menu Items</h3>
            <div class="text-sm text-gray-500">Kelola daftar menu dan harga</div>
        </div>

        <a href="{{ route('tenant.admin.menu-items.create', $currentTenant->slug) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-blue-200">
            <i class="bi bi-plus-circle"></i>
            Tambah Menu
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-3">
            <div>
                <h5 class="font-bold text-gray-900 mb-1">Daftar Menu</h5>
                <div class="text-sm text-gray-500">{{ $menuItems->total() }} menu</div>
            </div>
            <form method="GET" class="flex flex-wrap gap-2 items-end">
                <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <button type="submit" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Menu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($menuItems as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($item->image_url)
                                        <img src="{{ $item->image_url }}" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400"><i class="bi bi-cup-hot"></i></div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $item->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->sku ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $item->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($item->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tenant.admin.menu-items.show', [$currentTenant->slug, $item->id]) }}" class="p-2 rounded-lg hover:bg-blue-50 text-blue-600 transition-colors"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('tenant.admin.menu-items.edit', [$currentTenant->slug, $item->id]) }}" class="p-2 rounded-lg hover:bg-yellow-50 text-yellow-600 transition-colors"><i class="bi bi-pencil"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-cup-hot text-4xl text-gray-300 block mb-2"></i>
                                Belum ada menu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($menuItems->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-white">{{ $menuItems->withQueryString()->links() }}</div>
        @endif
    </div>

</div>

@endsection
