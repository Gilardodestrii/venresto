@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Inventory Report</h3>
            <div class="text-muted">Analisa pergerakan stok, waste, transfer, dan pemakaian bahan</div>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-end">
            <form method="GET" class="d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label class="form-label small text-muted mb-1">Dari</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="form-control rounded-4">
                </div>
                <div>
                    <label class="form-label small text-muted mb-1">Sampai</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="form-control rounded-4">
                </div>
                <button class="btn btn-primary rounded-4 px-4">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </form>

            <a href="{{ route('tenant.admin.reports.inventory.export', [
                    $currentTenant->slug,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ]) }}"
               class="btn btn-success rounded-4 px-4">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i>
                Export CSV
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5"><div class="card-body p-4">
                <div class="text-muted small mb-1">Stock In</div>
                <h4 class="fw-bold text-success mb-0">{{ number_format($stockInQty, 3) }}</h4>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5"><div class="card-body p-4">
                <div class="text-muted small mb-1">Stock Out</div>
                <h4 class="fw-bold text-danger mb-0">{{ number_format($stockOutQty, 3) }}</h4>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5"><div class="card-body p-4">
                <div class="text-muted small mb-1">Waste</div>
                <h4 class="fw-bold text-warning mb-0">{{ number_format($wasteQty, 3) }}</h4>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5"><div class="card-body p-4">
                <div class="text-muted small mb-1">Low Stock</div>
                <h4 class="fw-bold text-danger mb-0">{{ $lowStockCount }}</h4>
            </div></div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-5 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Transfer Summary</h5>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Transfer In</span>
                        <strong class="text-success">{{ number_format($transferInQty, 3) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Transfer Out</span>
                        <strong class="text-danger">{{ number_format($transferOutQty, 3) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-1">Top Used Materials</h5>
                    <div class="text-muted small">10 bahan paling banyak keluar pada periode ini</div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Bahan</th>
                                <th>Unit</th>
                                <th>Total Qty Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topUsedMaterials as $item)
                                <tr>
                                    <td class="px-4 fw-semibold">{{ $item->material?->name ?? '-' }}</td>
                                    <td>{{ $item->material?->unit ?? '-' }}</td>
                                    <td><span class="fw-bold text-danger">{{ number_format($item->total_qty, 3) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada pemakaian bahan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-1">Movement History</h5>
            <div class="text-muted small">Detail pergerakan stok sesuai filter tanggal</div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th>Bahan</th>
                        <th>Type</th>
                        <th>Qty</th>
                        <th>Before</th>
                        <th>After</th>
                        <th>Ref</th>
                        <th>Source</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr>
                            <td class="px-4">
                                <div class="fw-semibold">{{ $movement->created_at?->format('d M Y') }}</div>
                                <small class="text-muted">{{ $movement->created_at?->format('H:i') }}</small>
                            </td>
                            <td>{{ $movement->material?->name ?? '-' }}</td>
                            <td>
                                @if($movement->type === 'in')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">IN</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">OUT</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ number_format($movement->qty, 3) }}</td>
                            <td>{{ number_format($movement->stock_before ?? 0, 3) }}</td>
                            <td>{{ number_format($movement->stock_after ?? 0, 3) }}</td>
                            <td>{{ $movement->ref }}</td>
                            <td><small class="text-muted">{{ $movement->source_type ?? '-' }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-graph-up fs-1 d-block mb-2"></i>
                                Tidak ada movement pada periode ini.
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
