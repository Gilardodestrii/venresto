@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Waste Management</h3>
            <div class="text-muted">Catat bahan terbuang, expired, rusak, atau pemakaian non-penjualan</div>
        </div>

        <a href="{{ route('tenant.admin.waste-records.create', $currentTenant->slug) }}"
           class="btn btn-primary rounded-4 px-4">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Waste
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

    <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Riwayat Waste</h5>
                    <div class="text-muted small">Semua pengurangan stock karena waste tercatat di sini</div>
                </div>

                <form method="GET" class="d-flex gap-2">
                    <select name="reason" class="form-select rounded-4" onchange="this.form.submit()">
                        <option value="">Semua Reason</option>
                        @foreach(['expired','damaged','spillage','overcooked','staff_meal','other'] as $reason)
                            <option value="{{ $reason }}" {{ request('reason') === $reason ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $reason)) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th>Reason</th>
                        <th>Item</th>
                        <th>Outlet</th>
                        <th>Tanggal</th>
                        <th class="text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($wastes as $waste)
                    <tr>
                        <td class="px-4 fw-semibold">{{ $waste->code }}</td>
                        <td>
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                {{ ucwords(str_replace('_', ' ', $waste->reason)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                {{ $waste->items->count() }} item
                            </span>
                        </td>
                        <td>{{ $waste->outlet?->name ?? '-' }}</td>
                        <td>
                            <div class="fw-semibold">{{ $waste->created_at?->format('d M Y') }}</div>
                            <small class="text-muted">{{ $waste->created_at?->format('H:i') }}</small>
                        </td>
                        <td class="text-end px-4">
                            <a href="{{ route('tenant.admin.waste-records.show', [$currentTenant->slug, $waste->id]) }}"
                               class="btn btn-sm btn-outline-primary rounded-pill">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-trash3 fs-1 d-block mb-2"></i>
                            Belum ada waste record.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($wastes->hasPages())
            <div class="card-footer bg-white border-0 px-4 py-3">
                {{ $wastes->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
