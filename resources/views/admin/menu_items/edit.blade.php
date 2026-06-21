@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('tenant.admin.menu-items.index', $currentTenant->slug) }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Edit Menu</h3>
            <div class="text-sm text-gray-500">Perbarui informasi menu</div>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-sm">
            <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('tenant.admin.menu-items.update', [$currentTenant->slug, $menuItem->id]) }}">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $menuItem->name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $menuItem->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $menuItem->sku) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" step="1" name="price" value="{{ old('price', $menuItem->price) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('description', $menuItem->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Image URL</label>
                    <input type="url" name="image_url" value="{{ old('image_url', $menuItem->image_url) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="https://...">
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('tenant.admin.menu-items.index', $currentTenant->slug) }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors shadow-lg shadow-blue-200"><i class="bi bi-check-circle mr-1"></i>Update Menu</button>
                </div>
            </div>
        </form>
    </div>

</div>

@endsection
