@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Detail Waste Record</h3>
            <div class="text-muted">{{ $wasteRecord->code }}</div>
        </div>

        <a href="{{ route('tenant.admin.waste-records.index', $currentTenant->slug) }}"
           class="btn btn-light rounded-4 px-4">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-1">Item Waste</h5>
                    <div class="text-muted small">Bahan yang dikurangi dari inventory</div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Bahan</th>
                                <th>Unit</th>
                                <th>Qty Waste</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wasteRecord->items as $item)
                                <tr>
                                    <td class="px-4 fw-semibold">{{ $item->material?->name ?? '-' }}</td>
                                    <td>{{ $item->material?->unit ?? '-' }}</td>
                                    <td>
                                        <span class="fw-bold text-danger">
                                            -{{ number_format($item->qty, 3) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        Tidak ada item waste.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Informasi Waste</h5>

                    <div class="mb-3">
                        <small class="text-muted">Kode</small>
                        <div class="fw-bold">{{ $wasteRecord->code }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Reason</small><br>
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                            {{ ucwords(str_replace('_', ' ', $wasteRecord->reason)) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Outlet</small>
                        <div class="fw-semibold">{{ $wasteRecord->outlet?->name ?? '-' }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Catatan</small>
                        <div>{{ $wasteRecord->notes ?? '-' }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Tanggal</small>
                        <div>{{ $wasteRecord->created_at?->format('d M Y H:i') }}</div>
                    </div>

                    <hr>

                    <a href="{{ route('tenant.admin.stock-movements.index', $currentTenant->slug) }}"
                       class="btn btn-outline-dark rounded-4 w-100">
                        <i class="bi bi-clock-history me-1"></i>
                        Lihat Stock Movement
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
