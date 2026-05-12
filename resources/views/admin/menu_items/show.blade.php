@extends('layouts.admin')

@section('content')

<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Detail Menu</h4>
            <small class="text-muted">Informasi lengkap menu item</small>
        </div>

        <a href="{{ route('menu-items.index', $currentTenant->slug) }}"
           class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <div class="row">

                {{-- IMAGE --}}
                <div class="col-md-4 text-center">
                    @if($menu_item->image_url)
                        <img src="{{ $menu_item->image_url }}"
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 220px;">
                    @else
                        <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                             style="height: 220px;">
                            <span class="text-muted">No Image</span>
                        </div>
                    @endif
                </div>

                {{-- INFO --}}
                <div class="col-md-8">

                    <h4 class="fw-bold">{{ $menu_item->name }}</h4>

                    <p class="mb-2 text-muted">
                        Kategori:
                        <span class="badge bg-light text-dark border">
                            {{ $menu_item->category->name ?? '-' }}
                        </span>
                    </p>

                    <p class="mb-2">
                        Harga:
                        <span class="fw-bold text-success">
                            Rp {{ number_format($menu_item->price,0,',','.') }}
                        </span>
                    </p>

                    <p class="mb-2">
                        SKU: {{ $menu_item->sku ?? '-' }}
                    </p>

                    <p class="mb-2">
                        Status:
                        @if($menu_item->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </p>

                    <hr>

                    <a href="{{ route('menu-items.edit', [$currentTenant->slug, $menu_item->id]) }}"
                       class="btn btn-warning">
                        Edit Menu
                    </a>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection