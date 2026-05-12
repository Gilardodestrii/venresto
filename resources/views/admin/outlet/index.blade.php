@extends('layouts.admin')

@section('page-title', 'Outlet Management')

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
                        <i class="bi bi-shop"></i>
                    </div>

                    <div>

                        <h2 class="fw-bold mb-1">
                            Outlet Management
                        </h2>

                        <p class="text-muted mb-0">
                            Kelola seluruh cabang restoran tenant VenResto
                        </p>

                    </div>

                </div>

            </div>

            <div class="d-flex flex-wrap gap-2">

                <button class="btn btn-light-premium">

                    <i class="bi bi-funnel me-1"></i>
                    Filter

                </button>

                <button class="btn btn-primary-premium"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCreateOutlet">

                    <i class="bi bi-plus-circle me-1"></i>
                    Tambah Outlet

                </button>

            </div>

        </div>

    </div>

    {{-- =========================
        STATS
    ========================== --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4 col-sm-6">

            <div class="premium-stat-card">

                <div class="stat-content">

                    <div>

                        <div class="stat-label">
                            Total Outlet
                        </div>

                        <div class="stat-number">
                            {{ $outlets->count() }}
                        </div>

                    </div>

                    <div class="stat-icon primary">
                        <i class="bi bi-shop"></i>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4 col-sm-6">

            <div class="premium-stat-card">

                <div class="stat-content">

                    <div>

                        <div class="stat-label">
                            Outlet Active
                        </div>

                        <div class="stat-number">
                            {{ $outlets->where('is_active', true)->count() }}
                        </div>

                    </div>

                    <div class="stat-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4 col-sm-6">

            <div class="premium-stat-card">

                <div class="stat-content">

                    <div>

                        <div class="stat-label">
                            Non Active
                        </div>

                        <div class="stat-number">
                            {{ $outlets->where('is_active', false)->count() }}
                        </div>

                    </div>

                    <div class="stat-icon danger">
                        <i class="bi bi-x-circle"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================
        TABLE CARD
    ========================== --}}
    <div class="premium-table-card">

        {{-- SEARCH --}}
        <div class="table-topbar">

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input type="text"
                       class="form-control"
                       placeholder="Cari outlet...">

            </div>

        </div>

        {{-- TABLE --}}
        <div class="table-responsive">

            <table class="table premium-table align-middle mb-0">

                <thead>

                    <tr>
                        <th>Outlet</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($outlets as $outlet)

                        <tr>

                            {{-- OUTLET --}}
                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    <div class="outlet-avatar">

                                        <i class="bi bi-shop"></i>

                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $outlet->name }}
                                        </div>

                                        <div class="small text-muted">
                                            {{ $outlet->address ?? 'Alamat belum tersedia' }}
                                        </div>

                                    </div>

                                </div>

                            </td>

                            {{-- CONTACT --}}
                            <td>

                                <div class="small">

                                    <div class="mb-1">
                                        <i class="bi bi-telephone me-1"></i>
                                        {{ $outlet->phone ?? '-' }}
                                    </div>

                                    <div class="text-muted">
                                        <i class="bi bi-envelope me-1"></i>
                                        {{ $outlet->email ?? '-' }}
                                    </div>

                                </div>

                            </td>

                            {{-- STATUS --}}
                            <td>

                                @if($outlet->is_active)

                                    <span class="badge-premium success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge-premium danger">
                                        Non Active
                                    </span>

                                @endif

                            </td>

                            {{-- ACTION --}}
                            <td class="text-end">

                                <div class="d-flex justify-content-end gap-2">
                                {{-- VIEW --}}
                                <a href="{{ route('tenant.admin.outlets.show', [$currentTenant->slug, $outlet->id]) }}"
                                class="action-btn">

                                    <i class="bi bi-eye"></i>

                                </a>

                                {{-- EDIT --}}
                                <a href="{{ route('tenant.admin.outlets.edit', [$currentTenant->slug, $outlet->id]) }}"
                                class="action-btn">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                    <form method="POST"
                                        action="{{ route('tenant.admin.outlets.destroy', [$currentTenant->slug, $outlet->id]) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button class="action-btn danger"
                                                onclick="return confirm('Hapus outlet ini?')">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4">

                                <div class="empty-state">

                                    <div class="empty-icon">

                                        <i class="bi bi-shop"></i>

                                    </div>

                                    <h5 class="fw-bold mb-2">
                                        Belum Ada Outlet
                                    </h5>

                                    <p class="text-muted mb-4">
                                        Tambahkan outlet pertama untuk memulai sistem POS restoran.
                                    </p>

                                    <a href="#"
                                       class="btn btn-primary-premium">

                                        <i class="bi bi-plus-circle me-1"></i>
                                        Tambah Outlet

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ======================================
    MODAL CREATE OUTLET
====================================== --}}
<div class="modal fade"
     id="modalCreateOutlet"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content premium-modal border-0">

            <form method="POST"
                  action="{{ route('tenant.admin.outlets.store', $currentTenant->slug) }}">

                @csrf

                <div class="modal-header border-0 pb-0">

                    <div>

                        <h5 class="fw-bold mb-1">
                            Tambah Outlet
                        </h5>

                        <p class="text-muted small mb-0">
                            Tambahkan cabang restoran baru
                        </p>

                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    {{-- NAME --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Nama Outlet
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control premium-input @error('name') is-invalid @enderror"
                               placeholder="Contoh: Outlet Bogor">

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    {{-- ADDRESS --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Alamat
                        </label>

                        <textarea name="address"
                                  rows="4"
                                  class="form-control premium-input @error('address') is-invalid @enderror"
                                  placeholder="Masukkan alamat outlet"></textarea>

                        @error('address')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

                <div class="modal-footer border-0 pt-0">

                    <button type="button"
                            class="btn btn-light-premium"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-primary-premium">

                        <i class="bi bi-check-circle me-1"></i>
                        Simpan Outlet

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>











/* ===============================
    MOBILE
================================ */

@media(max-width:768px){

    .outlet-header-card{
        padding:20px;
        border-radius:24px;
    }

    .header-icon{
        width:58px;
        height:58px;
        font-size:24px;
    }

    .stat-number{
        font-size:26px;
    }

    .premium-table tbody td{
        min-width:180px;
    }

}

</style>

@endpush