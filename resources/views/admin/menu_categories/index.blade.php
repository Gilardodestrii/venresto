@extends('layouts.admin')
@section('page-title', 'Kategori Menu')
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
                            Kategori Menu
                        </h2>

                        <p class="text-muted mb-0">
                            Kelola kategori menu untuk outlet
                        </p>

                    </div>

                </div>

            </div>

                <div class="d-flex flex-wrap gap-2">



                <a href="{{ route('menu-categories.create', $currentTenant->slug) }}" class="btn btn-primary-premium"">

                    <i class="bi bi-plus-circle me-1"></i>
                    Tambah Kategori

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

                {{-- TABLE HEAD --}}
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th class="text-center">Urutan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- TABLE BODY --}}
                <tbody>

                    @forelse($categories as $cat)
                    <tr>

                        {{-- NAME --}}
                        <td class="fw-semibold">
                            {{ $cat->name }}
                        </td>

                        {{-- ORDER --}}
                        <td class="text-center">
                            <span class="badge badge-info">
                                {{ $cat->seq }}
                            </span>
                        </td>

                        {{-- ACTION --}}
                        <td class="text-end">

                            <div class="flex justify-center gap-2">

                                <a href="{{ route('menu-categories.edit', [$currentTenant->slug, $cat->id]) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('menu-categories.destroy', [$currentTenant->slug, $cat->id]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus kategori ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash3"></i>
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    {{-- EMPTY STATE --}}
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">

                            <div class="flex flex-column items-center gap-2">

                                <div class="fs-lg">📂</div>

                                <div class="fw-semibold">
                                    Belum ada kategori menu
                                </div>

                                <div class="fs-sm text-muted">
                                    Silakan tambah kategori terlebih dahulu
                                </div>

                            </div>

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection