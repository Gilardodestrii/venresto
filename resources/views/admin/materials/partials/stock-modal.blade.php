@php
    $isIn = $type === 'in';
    $isAdjustment = $type === 'adjustment';

    if ($isAdjustment) {
        $modalId = 'adjustment' . $material->id;
        $routeName = 'tenant.admin.materials.adjustment';
    } else {
        $modalId = $isIn ? 'stockIn' . $material->id : 'stockOut' . $material->id;
        $routeName = $isIn
            ? 'tenant.admin.materials.stock-in'
            : 'tenant.admin.materials.stock-out';
    }
@endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST"
              action="{{ route($routeName, [$currentTenant->slug, $material->id]) }}"
              class="modal-content border-0 rounded-5 shadow">
            @csrf

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    @if($isAdjustment)
                        Stock Adjustment
                    @else
                        {{ $isIn ? 'Stok Masuk' : 'Stok Keluar' }}
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Bahan</label>
                    <input type="text"
                           class="form-control rounded-4"
                           value="{{ $material->name }}"
                           disabled>
                </div>

                @if($isAdjustment)
                    <div class="mb-3">
                        <label class="form-label">Stock Saat Ini</label>
                        <input type="text"
                               class="form-control rounded-4"
                               value="{{ number_format($material->stock, 2) }} {{ $material->unit }}"
                               disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock Baru</label>
                        <input type="number"
                               step="0.001"
                               min="0"
                               name="stock"
                               class="form-control rounded-4"
                               required>
                    </div>
                @else
                    <div class="mb-3">
                        <label class="form-label">Qty</label>
                        <input type="number"
                               step="0.001"
                               name="qty"
                               class="form-control rounded-4"
                               required>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Referensi</label>
                    <input type="text"
                           name="ref"
                           class="form-control rounded-4"
                           placeholder="{{ $isAdjustment ? 'STOCK OPNAME' : ($isIn ? 'BELI / RESTOCK' : 'WASTE / MANUAL') }}">
                </div>
            </div>

            <div class="modal-footer border-0">
                <button class="btn {{ $isAdjustment ? 'btn-dark' : ($isIn ? 'btn-success' : 'btn-danger') }} rounded-4 px-4">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>