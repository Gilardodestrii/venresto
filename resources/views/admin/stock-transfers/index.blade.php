@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Transfer Stock</h3>
            <div class="text-muted">Kelola perpindahan bahan baku antar outlet</div>
        </div>

        <a href="{{ route('tenant.admin.stock-transfers.create', $currentTenant->slug) }}"
           class="btn btn-primary rounded-4 px-4">
            <i class="bi bi-plus-lg me-1"></i>
            Buat Transfer
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Total Transfer</div>
                    <h4 class="fw-bold mb-0">{{ $transfers->total() }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Pending</div>
                    <h4 class="fw-bold text-warning mb-0">
                        {{ $transfers->where('status', 'pending')->count() }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Completed</div>
                    <h4 class="fw-bold text-success mb-0">
                        {{ $transfers->where('status', 'completed')->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-1">Riwayat Transfer</h5>
            <div class="text-muted small">Semua transaksi transfer stock antar outlet</div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th>Dari Outlet</th>
                        <th>Ke Outlet</th>
                        <th>Item</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $transfer)
                        <tr>
                            <td class="px-4 fw-semibold">{{ $transfer->code }}</td>
                            <td>{{ $transfer->fromOutlet?->name ?? '-' }}</td>
                            <td>{{ $transfer->toOutlet?->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                    {{ $transfer->items->count() }} item
                                </span>
                            </td>
                            <td>
                                @if($transfer->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">Pending</span>
                                @elseif($transfer->status === 'completed')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Completed</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $transfer->created_at?->format('d M Y') }}</div>
                                <small class="text-muted">{{ $transfer->created_at?->format('H:i') }}</small>
                            </td>
                            <td class="text-end px-4">
                                <a href="{{ route('tenant.admin.stock-transfers.show', [$currentTenant->slug, $transfer->id]) }}"
                                   class="btn btn-sm btn-outline-primary rounded-pill">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-arrow-left-right fs-1 d-block mb-2"></i>
                                Belum ada transfer stock.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transfers->hasPages())
            <div class="card-footer bg-white border-0 px-4 py-3">
                {{ $transfers->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
