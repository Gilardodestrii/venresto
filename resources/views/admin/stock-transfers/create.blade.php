@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold mb-1 text-xl">Buat Transfer Stock</h3>
            <div class="text-gray-500">Transfer bahan baku ke outlet lain</div>
        </div>

        <a href="{{ route('tenant.admin.stock-transfers.index', $currentTenant->slug) }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg font-medium transition-colors inline-flex items-center gap-1.5">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="bg-white border-0 shadow-sm rounded-xl p-5">

        <form method="POST"
              action="{{ route('tenant.admin.stock-transfers.store', $currentTenant->slug) }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block font-semibold mb-2 text-sm">Outlet Tujuan</label>
                    <select name="to_outlet_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow" required>
                        <option value="">Pilih Outlet</option>

                        @foreach($outlets as $outlet)
                            <option value="{{ $outlet->id }}">
                                {{ $outlet->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2 text-sm">Catatan</label>
                    <input type="text"
                           name="notes"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow"
                           placeholder="Optional note transfer">
                </div>
            </div>

            <div class="flex justify-between items-center mb-4">
                <div>
                    <h5 class="font-bold mb-1">Item Transfer</h5>
                    <div class="text-gray-500 text-sm">Tambah bahan yang akan dikirim</div>
                </div>

                <button type="button"
                        class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2.5 rounded-lg font-medium transition-colors inline-flex items-center gap-1.5"
                        onclick="addRow()">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Item
                </button>
            </div>

            <div id="transferRows"></div>

            <div class="text-end mt-6">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors inline-flex items-center gap-1.5">
                    <i class="bi bi-send-check"></i>
                    Simpan Transfer
                </button>
            </div>

        </form>

    </div>

</div>

<script>
const materials = @json($materials);

function rowTemplate(index){
    return `
        <div class="border border-gray-200 rounded-xl p-4 mb-3 bg-gray-50 transfer-row">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-7">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bahan</label>
                    <select name="items[${index}][material_id]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow" required>
                        <option value="">Pilih bahan</option>
                        ${materials.map(material => `
                            <option value="${material.id}">
                                ${material.name} (Stock: ${parseFloat(material.stock).toFixed(2)} ${material.unit})
                            </option>
                        `).join('')}
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                    <input type="number"
                           step="0.001"
                           min="0.001"
                           name="items[${index}][qty]"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow"
                           required>
                </div>

                <div class="md:col-span-2">
                    <button type="button"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg w-full font-medium transition-colors"
                            onclick="removeRow(this)">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    `;
}

function addRow(){
    const container = document.getElementById('transferRows');
    const index = container.querySelectorAll('.transfer-row').length;

    container.insertAdjacentHTML('beforeend', rowTemplate(index));
}

function removeRow(button){
    button.closest('.transfer-row').remove();
}

addRow();
</script>

@endsection
