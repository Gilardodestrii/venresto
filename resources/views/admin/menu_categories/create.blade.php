@extends('layouts.admin')

@section('content')

<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Tambah Kategori Menu</h4>
            <small class="text-muted">Kelola kategori menu untuk outlet Anda</small>
        </div>

        <a href="{{ route('menu-categories.index', $currentTenant->slug) }}"
           class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    {{-- CARD FORM --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('menu-categories.store', $currentTenant->slug) }}" method="POST">
                @csrf

                <div class="row">

                    {{-- LEFT --}}
                    <div class="col-md-8">

                        {{-- NAME --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Nama Kategori
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control form-control-lg"
                                   placeholder="Contoh: Makanan, Minuman, Dessert"
                                   required>

                            <small class="text-muted">
                                Gunakan nama yang mudah dipahami pelanggan
                            </small>
                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="col-md-4">

                        {{-- SORT --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Urutan Tampil
                            </label>

                            <input type="number"
                                   name="seq"
                                   class="form-control form-control-lg"
                                   value="0"
                                   min="0">

                            <small class="text-muted">
                                Semakin kecil semakin atas
                            </small>
                        </div>

                        {{-- INFO BOX --}}
                        <div class="alert alert-light border">
                            <small>
                                💡 Kategori akan digunakan di QR Menu dan POS kasir.
                            </small>
                        </div>

                    </div>

                </div>

                <hr>

                {{-- ACTION --}}
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('menu-categories.index', $currentTenant->slug) }}"
                       class="btn btn-light border">
                        Batal
                    </a>

                    <button class="btn btn-primary px-4">
                        💾 Simpan Kategori
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection