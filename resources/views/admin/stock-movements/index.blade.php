@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Stock Movement</h3>
            <div class="text-muted">
                Riwayat keluar masuk stok bahan baku
            </div>
        </div>

        <a href="{{ route('tenant.admin.materials.index', $currentTenant->slug) }}"
           class="btn btn-primary rounded-4 px-4">
            <i class="bi bi-box-seam me-1"></i>
            Inventory
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Movement</div>
                    <h4 class="fw-bold mb-0">{{ $movements->total() }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body">
                    <div class="text-muted small mb-1">Stok Masuk</div>
                    <h4 class="fw-bold text-success mb-0">
                        {{ $movements->where('type', 'in')->count() }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body">
                    <div class="text-muted small mb-1">Stok Keluar</div>
                    <h4 class="fw-bold text-danger mb-0">
                        {{ $movements->where('type', 'out')->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Riwayat Pergerakan Stok</h5>
                    <div class="text-muted small">
                        Semua transaksi stok masuk dan stok keluar
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th>Bahan</th>
                        <th>Tipe</th>
                        <th>Qty</th>
                        <th>Ref</th>
                        <th>Dibuat Oleh</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($movements as $movement)
                        <tr>
                            <td class="px-4">
                                <div class="fw-semibold">
                                    {{ $movement->created_at?->format('d M Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ $movement->created_at?->format('H:i') }}
                                </small>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $movement->material?->name ?? '-' }}
                                </div>
                                <small class="text-muted">
                                    Unit: {{ $movement->material?->unit ?? '-' }}
                                </small>
                            </td>

                            <td>
                                @if($movement->type === 'in')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                        <i class="bi bi-arrow-down-circle me-1"></i>
                                        Masuk
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                        <i class="bi bi-arrow-up-circle me-1"></i>
                                        Keluar
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="fw-bold {{ $movement->type === 'in' ? 'text-success' : 'text-danger' }}">
                                    {{ $movement->type === 'in' ? '+' : '-' }}
                                    {{ number_format($movement->qty, 3) }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                    {{ $movement->ref ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $movement->creator?->name ?? 'System' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-2">
                                    <i class="bi bi-clock-history fs-1 text-muted"></i>
                                </div>
                                <div class="fw-semibold">Belum ada stock movement</div>
                                <div class="text-muted small">
                                    Riwayat stok akan muncul setelah ada stok masuk atau stok keluar.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movements->hasPages())
            <div class="card-footer bg-white border-0 px-4 py-3">
                {{ $movements->links() }}
            </div>
        @endif
    </div>

</div>

@endsection