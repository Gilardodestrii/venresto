<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Bahan</label>
        <input type="text"
               name="name"
               value="{{ old('name', $material?->name) }}"
               class="form-control rounded-4"
               required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Unit</label>
        <input type="text"
               name="unit"
               value="{{ old('unit', $material?->unit) }}"
               class="form-control rounded-4"
               placeholder="gram / ml / pcs"
               required>
    </div>

    @if(!$material)
        <div class="col-md-3">
            <label class="form-label">Stok Awal</label>
            <input type="number"
                   step="0.001"
                   name="stock"
                   value="{{ old('stock', 0) }}"
                   class="form-control rounded-4">
        </div>
    @endif

    <div class="col-md-3">
        <label class="form-label">Minimum Stok</label>
        <input type="number"
               step="0.001"
               name="min_stock"
               value="{{ old('min_stock', $material?->min_stock ?? 0) }}"
               class="form-control rounded-4">
    </div>
</div>