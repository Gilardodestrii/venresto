@extends('layouts.admin')

@section('content')

<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Edit Kategori Menu</h4>
            <small class="text-muted">Perbarui data kategori menu</small>
        </div>

        <a href="{{ route('menu-categories.index', $currentTenant->slug) }}"
           class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('menu-categories.update', [$currentTenant->slug, $menu_category->id]) }}"
                  method="POST">

                @csrf
                @method('PUT')

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
                                   value="{{ $menu_category->name }}"
                                   required>

                            <small class="text-muted">
                                Nama kategori yang akan tampil di menu pelanggan
                            </small>
                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="col-md-4">

                        {{-- SORT --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Urutan Tampilan
                            </label>

                            <input type="number"
                                   name="seq"
                                   class="form-control form-control-lg"
                                   value="{{ $menu_category->seq }}"
                                   min="0">

                            <small class="text-muted">
                                Semakin kecil semakin atas
                            </small>
                        </div>

                        {{-- INFO --}}
                        <div class="alert alert-light border">
                            <small>
                                ⚡ Perubahan akan langsung mempengaruhi QR Menu & POS kasir.
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
                        💾 Update Kategori
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection