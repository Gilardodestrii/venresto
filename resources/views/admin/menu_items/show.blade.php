@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h4 class="text-xl font-bold text-gray-900 mb-1">Detail Menu</h4>
            <small class="text-gray-500">Informasi lengkap menu item</small>
        </div>
        <a href="{{ route('tenant.admin.menu-items.index', $currentTenant->slug) }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-gray-400 text-gray-600 hover:bg-gray-50 rounded-lg text-sm font-medium transition-colors">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-1 text-center">
                @if($menu_item->image_url)
                    <img src="{{ $menu_item->image_url }}" class="w-full max-h-56 object-cover rounded-xl shadow-sm">
                @else
                    <div class="h-56 bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center text-gray-400">
                        <i class="bi bi-image text-4xl"></i>
                    </div>
                @endif
            </div>

            <div class="md:col-span-2">
                <h4 class="text-2xl font-bold text-gray-900 mb-3">{{ $menu_item->name }}</h4>

                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Kategori:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $menu_item->category->name ?? '-' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">Harga:</span>
                        <span class="ml-2 text-xl font-bold text-green-600">
                            Rp {{ number_format($menu_item->price,0,',','.') }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-500">SKU: {{ $menu_item->sku ?? '-' }}</div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Status:</span>
                        @if($menu_item->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>
                        @endif
                    </div>
                </div>

                <hr class="my-4 border-gray-100">

                <a href="{{ route('tenant.admin.menu-items.edit', [$currentTenant->slug, $menu_item->id]) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-medium text-sm transition-colors">
                    <i class="bi bi-pencil"></i> Edit Menu
                </a>
            </div>

        </div>

    </div>

</div>

@endsection
