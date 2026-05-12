@extends('layouts.admin')

@section('content')

<div class="container-fluid px-0">


        {{-- =========================
        HEADER
    ========================== --}}
    <div class="outlet-header-card mb-4">

        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">

            <div>

                <div class="d-flex align-items-center gap-3 mb-2">

                    <div class="header-icon">
                        <i class="bi bi-tags"></i>
                    </div>

                    <div>

                        <h2 class="fw-bold mb-1">
                            Tambah Menu Item
                        </h2>

                        <p class="text-muted mb-0">
                            Tambahkan makanan & minuman ke menu
                        </p>

                    </div>

                </div>

            </div>

                <div class="d-flex flex-wrap gap-2">



                <a href="{{ route('menu-items.index', $currentTenant->slug) }}" class="btn btn-outline-secondary">

                    <i class="bi bi-backspace"></i>

                    <span>Kembali</span>

                </a>

            </div>

        </div>

    </div>
    {{-- HEADER --}}


    {{-- CARD --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('menu-items.store', $currentTenant->slug) }}" method="POST">
                @csrf

                <div class="row">

                    {{-- LEFT --}}
                    <div class="col-md-8">

                        {{-- NAMA MENU --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Menu</label>
                            <input type="text"
                                   name="name"
                                   class="form-control form-control-lg"
                                   placeholder="Contoh: Nasi Goreng Spesial"
                                   required>
                        </div>

                        {{-- KATEGORI --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>

                            <select name="category_id" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- HARGA --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga</label>
                            <input type="number"
                                   name="price"
                                   class="form-control form-control-lg"
                                   placeholder="0"
                                   required>
                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="col-md-4">

                        {{-- SKU --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">SKU (Opsional)</label>
                            <input type="text"
                                   name="sku"
                                   class="form-control"
                                   placeholder="CTH: MKN-001">
                        </div>

                        {{-- IMAGE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image URL</label>
                            <input type="text"
                                   name="image_url"
                                   class="form-control"
                                   placeholder="https://...">
                        </div>

                        {{-- STATUS INFO --}}
                        <div class="alert alert-light border">
                            <small>
                                ⚡ Menu akan otomatis aktif setelah disimpan
                            </small>
                        </div>

                    </div>

                </div>

                <hr>

                {{-- BUTTON --}}
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('menu-items.index', $currentTenant->slug) }}"
                       class="btn btn-light border">
                        Batal
                    </a>

                    <button class="btn btn-primary px-4">
                        💾 Simpan Menu
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection