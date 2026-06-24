<form method="POST"
      action="{{ $material ? route('tenant.admin.materials.update', [$currentTenant->slug, $material->id]) : route('tenant.admin.materials.store', $currentTenant->slug) }}">
    @csrf
    @if($material)
        @method('PUT')
    @endif

    <div class="space-y-4">

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Bahan <span class="text-red-500">*</span></label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $material->name ?? '') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                   placeholder="Contoh: Beras Premium"
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Outlet</label>
            <select name="outlet_id"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('outlet_id') border-red-500 @enderror">
                <option value="">Pilih Outlet</option>
                @foreach($outlets as $outlet)
                    <option value="{{ $outlet->id }}"
                            {{ old('outlet_id', $material->outlet_id ?? '') == $outlet->id ? 'selected' : '' }}>
                        {{ $outlet->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                <input type="text"
                       name="unit"
                       value="{{ old('unit', $material->unit ?? '') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('unit') border-red-500 @enderror"
                       placeholder="kg, liter, pcs"
                       required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Stock</label>
                <input type="number"
                       step="0.01"
                       name="stock"
                       value="{{ old('stock', $material->stock ?? '0') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="0">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Min. Stock</label>
                <input type="number"
                       step="0.01"
                       name="min_stock"
                       value="{{ old('min_stock', $material->min_stock ?? '0') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="0">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Harga per Unit (Rp) <span class="text-red-500">*</span></label>
                <input type="number"
                       step="1"
                       name="cost_per_unit"
                       value="{{ old('cost_per_unit', $material->cost_per_unit ?? '') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="0"
                       required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">SKU</label>
                <input type="text"
                       name="sku"
                       value="{{ old('sku', $material->sku ?? '') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                       placeholder="SKU-001">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
            <textarea name="description"
                      rows="3"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                      placeholder="Optional note">{{ old('description', $material->description ?? '') }}</textarea>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <a href="{{ route('tenant.admin.materials.index', $currentTenant->slug) }}"
               class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors shadow-lg shadow-blue-200">
                <i class="bi bi-check-circle mr-1"></i>
                {{ $material ? 'Update' : 'Simpan' }} Bahan
            </button>
        </div>

    </div>
</form>
