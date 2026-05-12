@extends('layouts.admin')
@section('page-title', 'Menu Item')
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
                            Menu Item
                        </h2>

                        <p class="text-muted mb-0">
                            Kelola menu makanan & minuman
                        </p>

                    </div>

                </div>

            </div>

                <div class="d-flex flex-wrap gap-2">



                <a href="{{ route('menu-items.create', $currentTenant->slug) }}" class="btn btn-primary-premium">

                    <i class="bi bi-plus-circle me-1"></i>
                    Tambah Menu

                </a>

            </div>

        </div>

    </div>
    {{-- HEADER --}}

    {{-- =========================
        TABLE CARD
    ========================== --}}
    <div class="premium-table-card">

        {{-- SEARCH --}}
        <div class="table-topbar">
            <div class="d-flex flex-wrap gap-2">
                <div class="search-box">

                    <i class="bi bi-search"></i>

                    <input type="text"
                        class="form-control"
                        placeholder="Cari outlet...">

                </div>

                <button class="btn btn-light-premium">

                        <i class="bi bi-funnel me-1"></i>
                        Filter

                </button>
            </div>

            

        </div>

        <div class="table-responsive">

            <table class="table premium-table align-middle mb-0">

                    <thead >
                        <tr>
                            <th>Menu</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($items as $item)
                        <tr>

                            <td class="fw-semibold">
                                {{ $item->name }}
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $item->category->name ?? '-' }}
                                </span>
                            </td>

                            <td>
                                Rp {{ number_format($item->price,0,',','.') }}
                            </td>

                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>

                            <td>

                                <a href="{{ route('menu-items.show', [$currentTenant->slug, $item->id]) }}"
                                   class="btn btn-sm btn-outline-info">
                                    Detail
                                </a>

                                <a href="{{ route('menu-items.edit', [$currentTenant->slug, $item->id]) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    Edit
                                </a>

                                <form action="{{ route('menu-items.destroy', [$currentTenant->slug, $item->id]) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf @method('DELETE')

                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus menu ini?')">
                                        Hapus
                                    </button>
                                </form>

                            </td>

                        </tr>
                        @empty

                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div>
                                    🍽️ Belum ada menu
                                </div>
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@endsection