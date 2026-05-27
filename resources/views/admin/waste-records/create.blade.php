@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Tambah Waste Record</h3>
            <div class="text-muted">Kurangi stock karena waste atau pemakaian non-penjualan</div>
        </div>

        <a href="{{ route('tenant.admin.waste-records.index', $currentTenant->slug) }}"
           class="btn btn-light rounded-4 px-4">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-5">
        <div class="card-body p-4">

            <form method="POST"
                  action="{{ route('tenant.admin.waste-records.store', $currentTenant->slug) }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Reason</label>
                        <select name="reason" class="form-select rounded-4" required>
                            <option value="">Pilih Reason</option>
                            <option value="expired">Expired</option>
                            <option value="damaged">Damaged</option>
                            <option value="spillage">Spillage</option>
                            <option value="overcooked">Overcooked</option>
                            <option value="staff_meal">Staff Meal</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Catatan</label>
                        <input type="text"
                               name="notes"
                               class="form-control rounded-4"
                               placeholder="Optional note waste">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Item Waste</h5>
                        <div class="text-muted small">Tambah bahan yang akan dikurangi dari inventory</div>
                    </div>

                    <button type="button"
                            class="btn btn-dark rounded-4"
                            onclick="addRow()">
                        <i class="bi bi-plus-lg"></i>
                        Tambah Item
                    </button>
                </div>

                <div id="wasteRows"></div>

                <div class="text-end mt-4">
                    <button class="btn btn-danger rounded-4 px-5">
                        <i class="bi bi-trash3 me-1"></i>
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
        <div class="border rounded-4 p-3 mb-3 waste-row">
            <div class="row g-3 align-items-end">

                <div class="col-md-7">
                    <label class="form-label">Bahan</label>
                    <select name="items[${index}][material_id]" class="form-select rounded-4" required>
                        <option value="">Pilih bahan</option>
                        ${materials.map(material => `
                            <option value="${material.id}">
                                ${material.name} (Stock: ${parseFloat(material.stock).toFixed(2)} ${material.unit})
                            </option>
                        `).join('')}
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Qty</label>
                    <input type="number"
                           step="0.001"
                           min="0.001"
                           name="items[${index}][qty]"
                           class="form-control rounded-4"
                           required>
                </div>

                <div class="col-md-2">
                    <button type="button"
                            class="btn btn-outline-danger rounded-4 w-100"
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
