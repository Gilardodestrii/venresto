@extends('layouts.admin')

@section('content')

<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Edit Menu Item</h4>
            <small class="text-muted">Perbarui data menu</small>
        </div>

        <a href="{{ route('menu-items.index', $currentTenant->slug) }}"
           class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('menu-items.update', [$currentTenant->slug, $menu_item->id]) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- LEFT --}}
                    <div class="col-md-8">

                        {{-- NAME --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Menu</label>
                            <input type="text"
                                   name="name"
                                   class="form-control form-control-lg"
                                   value="{{ $menu_item->name }}"
                                   required>
                        </div>

                        {{-- CATEGORY --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>

                            <select name="category_id" class="form-select form-select-lg" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $menu_item->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PRICE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga</label>
                            <input type="number"
                                   name="price"
                                   class="form-control form-control-lg"
                                   value="{{ $menu_item->price }}"
                                   required>
                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="col-md-4">

                        {{-- SKU --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">SKU</label>
                            <input type="text"
                                   name="sku"
                                   class="form-control"
                                   value="{{ $menu_item->sku }}">
                        </div>

                        {{-- IMAGE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image URL</label>
                            <input type="text"
                                   name="image_url"
                                   class="form-control"
                                   value="{{ $menu_item->image_url }}">
                        </div>

                        {{-- STATUS --}}
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ $menu_item->is_active ? 'checked' : '' }}>
                            <label class="form-check-label">
                                Aktifkan Menu
                            </label>
                        </div>

                        <div class="alert alert-light border">
                            <small>
                                ⚡ Perubahan akan langsung terlihat di QR Menu & POS
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
                        💾 Update Menu
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection