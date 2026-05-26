@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Recipe Builder</h3>
            <div class="text-muted">Tambah banyak bahan sekaligus untuk 1 menu</div>
        </div>

        <a href="{{ route('tenant.admin.recipes.index', $currentTenant->slug) }}"
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
                  action="{{ route('tenant.admin.recipes.store', $currentTenant->slug) }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold">Menu</label>
                    <select name="item_id" class="form-select rounded-4" required>
                        <option value="">Pilih Menu</option>

                        @foreach($menuItems as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0">Bahan Recipe</h6>
                        <small class="text-muted">Tambah banyak bahan untuk menu</small>
                    </div>

                    <button type="button"
                            class="btn btn-dark rounded-4"
                            onclick="addRow()">
                        <i class="bi bi-plus-lg"></i>
                        Tambah Bahan
                    </button>
                </div>

                <div id="recipeRows">
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-primary rounded-4 px-4">
                        <i class="bi bi-save me-1"></i>
                        Simpan Recipe
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
        <div class="border rounded-4 p-3 mb-3 recipe-row">
            <div class="row g-3 align-items-end">

                <div class="col-md-6">
                    <label class="form-label">Bahan</label>
                    <select name="recipes[${index}][material_id]" class="form-select rounded-4" required>
                        <option value="">Pilih bahan</option>

                        ${materials.map(material => `
                            <option value="${material.id}">
                                ${material.name} - Stock ${parseFloat(material.stock).toFixed(2)} ${material.unit}
                            </option>
                        `).join('')}
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Qty Pemakaian</label>
                    <input type="number"
                           step="0.001"
                           min="0.001"
                           name="recipes[${index}][qty]"
                           class="form-control rounded-4"
                           placeholder="0.250"
                           required>
                </div>

                <div class="col-md-2">
                    <button type="button"
                            class="btn btn-danger rounded-4 w-100"
                            onclick="removeRow(this)">
                        Hapus
                    </button>
                </div>

            </div>
        </div>
    `;
}

function addRow(){
    const container = document.getElementById('recipeRows');
    const index = container.querySelectorAll('.recipe-row').length;

    container.insertAdjacentHTML('beforeend', rowTemplate(index));
}

function removeRow(button){
    button.closest('.recipe-row').remove();
}

addRow();
</script>

@endsection