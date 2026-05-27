@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Detail Transfer Stock</h3>
            <div class="text-muted">{{ $stockTransfer->code }}</div>
        </div>

        <a href="{{ route('tenant.admin.stock-transfers.index', $currentTenant->slug) }}"
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

    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-1">Item Transfer</h5>
                    <div class="text-muted small">Daftar bahan yang dipindahkan antar outlet</div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Bahan</th>
                                <th>Unit</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($stockTransfer->items as $item)
                            <tr>
                                <td class="px-4 fw-semibold">{{ $item->material?->name ?? '-' }}</td>
                                <td>{{ $item->material?->unit ?? '-' }}</td>
                                <td>
                                    <span class="fw-bold">{{ number_format($item->qty, 3) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    Belum ada item transfer.
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
                    <h5 class="fw-bold mb-3">Informasi Transfer</h5>

                    <div class="mb-3">
                        <small class="text-muted">Kode</small>
                        <div class="fw-bold">{{ $stockTransfer->code }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Status</small><br>
                        @if($stockTransfer->status === 'pending')
                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">Pending</span>
                        @elseif($stockTransfer->status === 'completed')
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Completed</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Cancelled</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Outlet Asal</small>
                        <div class="fw-semibold">{{ $stockTransfer->fromOutlet?->name ?? '-' }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Outlet Tujuan</small>
                        <div class="fw-semibold">{{ $stockTransfer->toOutlet?->name ?? '-' }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Catatan</small>
                        <div>{{ $stockTransfer->notes ?? '-' }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Dibuat</small>
                        <div>{{ $stockTransfer->created_at?->format('d M Y H:i') }}</div>
                    </div>

                    @if($stockTransfer->completed_at)
                        <div class="mb-3">
                            <small class="text-muted">Diselesaikan</small>
                            <div>{{ $stockTransfer->completed_at?->format('d M Y H:i') }}</div>
                        </div>
                    @endif

                    @if($stockTransfer->status === 'pending')
                        <hr>

                        <form method="POST"
                              action="{{ route('tenant.admin.stock-transfers.complete', [$currentTenant->slug, $stockTransfer->id]) }}"
                              onsubmit="return confirm('Selesaikan transfer ini? Stock asal akan berkurang dan stock tujuan akan bertambah.')">
                            @csrf
                            <button class="btn btn-success rounded-4 w-100 mb-2">
                                <i class="bi bi-check-circle me-1"></i>
                                Complete Transfer
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('tenant.admin.stock-transfers.cancel', [$currentTenant->slug, $stockTransfer->id]) }}"
                              onsubmit="return confirm('Batalkan transfer ini?')">
                            @csrf
                            <button class="btn btn-outline-danger rounded-4 w-100">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancel Transfer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
