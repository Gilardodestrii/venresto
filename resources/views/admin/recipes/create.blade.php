@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Tambah Recipe</h3>
            <div class="text-muted">Tentukan bahan baku yang digunakan oleh menu</div>
        </div>

        <a href="{{ route('tenant.admin.recipes.index', $currentTenant->slug) }}"
           class="btn btn-light rounded-4 px-4">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            <div class="fw-semibold mb-1">Validasi gagal</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">

                    <form method="POST"
                          action="{{ route('tenant.admin.recipes.store', $currentTenant->slug) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Menu</label>
                            <select name="item_id"
                                    class="form-select rounded-4 @error('item_id') is-invalid @enderror"
                                    required>
                                <option value="">Pilih menu</option>

                                @foreach($menuItems as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bahan Baku</label>
                            <select name="material_id"
                                    class="form-select rounded-4 @error('material_id') is-invalid @enderror"
                                    required>
                                <option value="">Pilih bahan</option>

                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }}
                                        -
                                        Stok {{ number_format($material->stock, 2) }}
                                        {{ $material->unit }}
                                    </option>
                                @endforeach
                            </select>

                            @error('material_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Qty Pemakaian</label>
                            <input type="number"
                                   name="qty"
                                   step="0.001"
                                   min="0.001"
                                   value="{{ old('qty') }}"
                                   class="form-control rounded-4 @error('qty') is-invalid @enderror"
                                   placeholder="Contoh: 0.250"
                                   required>

                            <div class="form-text">
                                Jumlah bahan yang dipakai untuk 1 porsi/menu.
                            </div>

                            @error('qty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tenant.admin.recipes.index', $currentTenant->slug) }}"
                               class="btn btn-light rounded-4 px-4">
                                Batal
                            </a>

                            <button class="btn btn-primary rounded-4 px-4">
                                <i class="bi bi-save me-1"></i>
                                Simpan Recipe
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-4 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width:48px;height:48px;">
                            <i class="bi bi-info-circle fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Cara Kerja Recipe</h6>
                            <small class="text-muted">Auto deduct stock</small>
                        </div>
                    </div>

                    <div class="text-muted small lh-lg">
                        Recipe digunakan untuk menentukan bahan baku dari setiap menu.
                        Saat order dibayar, sistem akan mengurangi stok bahan sesuai qty recipe
                        dikalikan jumlah item order.
                    </div>

                    <hr>

                    <div class="small">
                        <div class="fw-semibold mb-2">Contoh:</div>
                        <div class="text-muted">
                            Menu: Es Kopi Susu<br>
                            Bahan: Susu<br>
                            Qty Recipe: 0.150 liter<br>
                            Order: 2 item<br>
                            Stok terpotong: 0.300 liter
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection