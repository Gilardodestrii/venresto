<!-- Stock Update Modal - Tailwind Native -->
<div id="stockModal"
     class="fixed inset-0 z-50 hidden"
     aria-labelledby="stockModalLabel"
     aria-hidden="true">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"
         onclick="closeStockModal()"></div>

    <!-- Modal Panel -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl border-0 w-full max-w-md pointer-events-auto"
             role="dialog"
             aria-modal="true">

            <form method="POST"
                  id="stockForm"
                  action="">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h5 class="font-bold text-gray-900 mb-1" id="stockModalLabel">Update Stock</h5>
                            <p class="text-sm text-gray-500" id="stockMaterialName">-</p>
                        </div>
                        <button type="button"
                                class="p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors"
                                onclick="closeStockModal()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-4">

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe</label>
                        <select name="type"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                id="stockTypeSelect">
                            <option value="add">Tambah Stock</option>
                            <option value="reduce">Kurangi Stock</option>
                            <option value="set">Set Stock (override)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="qty"
                               id="stockQtyInput"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="0"
                               required>
                        <div class="text-sm text-gray-500 mt-1">
                            Stock saat ini: <span id="currentStockDisplay" class="font-semibold text-gray-700">-</span>
                            <span id="stockUnitDisplay">-</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan</label>
                        <textarea name="notes"
                                  rows="2"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                  placeholder="Contoh: Pembelian dari supplier"></textarea>
                    </div>

                </div>

                <div class="border-t border-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button type="button"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors"
                            onclick="closeStockModal()">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors">
                        <i class="bi bi-check-circle mr-1"></i>
                        Update Stock
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
function openStockModal(id, name, stock, unit) {
    document.getElementById('stockMaterialName').textContent = name;
    document.getElementById('currentStockDisplay').textContent = parseFloat(stock).toFixed(2);
    document.getElementById('stockUnitDisplay').textContent = unit;
    document.getElementById('stockQtyInput').value = '';
    var slug = '{{ $currentTenant->slug }}';
    document.getElementById('stockForm').action = '/admin/tenants/' + slug + '/materials/' + id + '/stock';
    document.getElementById('stockModal').classList.remove('hidden');
}

function closeStockModal() {
    document.getElementById('stockModal').classList.add('hidden');
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('stockModal').classList.contains('hidden')) {
        closeStockModal();
    }
});
</script>
