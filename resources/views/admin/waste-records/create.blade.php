@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-xl mb-1">Tambah Waste Record</h3>
            <div class="text-gray-500 text-sm">Kurangi stock karena waste atau pemakaian non-penjualan</div>
        </div>

        <a href="{{ route('tenant.admin.waste-records.index', $currentTenant->slug) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5">

            <form method="POST"
                  action="{{ route('tenant.admin.waste-records.store', $currentTenant->slug) }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Reason</label>
                        <select name="reason" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Pilih Reason</option>
                            <option value="expired">Expired</option>
                            <option value="damaged">Damaged</option>
                            <option value="spillage">Spillage</option>
                            <option value="overcooked">Overcooked</option>
                            <option value="staff_meal">Staff Meal</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                        <input type="text"
                               name="notes"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Optional note waste">
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h5 class="font-bold text-lg mb-1">Item Waste</h5>
                        <div class="text-gray-500 text-sm">Tambah bahan yang akan dikurangi dari inventory</div>
                    </div>

                    <button type="button"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl transition-colors"
                            onclick="addRow()">
                        <i class="bi bi-plus-lg"></i>
                        Tambah Item
                    </button>
                </div>

                <div id="wasteRows"></div>

                <div class="flex justify-end mt-6">
                    <button class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                        <i class="bi bi-trash3"></i>
                        Simpan Waste
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
const materials = @json($materials);

function rowTemplate(index){
    return `
        <div class="border border-gray-200 rounded-xl p-4 mb-3 bg-gray-50 waste-row">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">

                <div class="md:col-span-7">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Bahan</label>
                    <select name="items[${index}][material_id]" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Pilih bahan</option>
                        ${materials.map(material => `
                            <option value="${material.id}">
                                ${material.name} (Stock: ${parseFloat(material.stock).toFixed(2)} ${material.unit})
                            </option>
                        `).join('')}
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Qty</label>
                    <input type="number"
                           step="0.001"
                           min="0.001"
                           name="items[${index}][qty]"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <div class="md:col-span-2">
                    <button type="button"
                            class="w-full px-3 py-2 text-sm font-medium rounded-xl border border-red-300 text-red-600 hover:bg-red-50 transition-colors"
                            onclick="removeRow(this)">
                        Hapus
                    </button>
                </div>

            </div>
        </div>
    `;
}

function addRow(){
    const container = document.getElementById('wasteRows');
    const index = container.querySelectorAll('.waste-row').length;

    container.insertAdjacentHTML('beforeend', rowTemplate(index));
}

function removeRow(button){
    button.closest('.waste-row').remove();
}

addRow();
</script>

@endsection
