@extends('layouts.admin')

@section('page-title','Dashboard')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard</h4>
        <div class="text-muted small">Ringkasan performa penjualan tenant bulan ini.</div>
    </div>

    <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="month"
               name="month"
               value="{{ $month }}"
               class="form-control"
               style="max-width:180px">

        <select name="outlet_id" class="form-select" style="max-width:220px">
            <option value="">Semua Outlet</option>
            @foreach($outlets as $outlet)
                <option value="{{ $outlet->id }}" @selected((string) $outletId === (string) $outlet->id)>
                    {{ $outlet->name }}
                </option>
            @endforeach
        </select>

        <button class="btn btn-primary">
            <i class="bi bi-funnel me-1"></i> Filter
        </button>
    </form>
</div>

<div class="row g-3">

    <div class="col-md-3">
        <div class="card-premium p-3 h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="text-muted">Users</small>
                    <h4 class="fw-bold mb-0">{{ $users }}</h4>
                </div>
                <div class="stat-icon"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-premium p-3 h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="text-muted">Orders</small>
                    <h4 class="fw-bold mb-0">{{ $orders }}</h4>
                </div>
                <div class="stat-icon"><i class="bi bi-journal"></i></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-premium p-3 h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="text-muted">Revenue</small>
                    <h4 class="fw-bold text-primary-soft mb-0">
                        Rp {{ number_format($revenue,0,',','.') }}
                    </h4>
                </div>
                <div class="stat-icon"><i class="bi bi-cash"></i></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-premium p-3 h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="text-muted">Avg Order</small>
                    <h4 class="fw-bold mb-0">
                        Rp {{ number_format($avgOrder,0,',','.') }}
                    </h4>
                </div>
                <div class="stat-icon"><i class="bi bi-graph-up"></i></div>
            </div>
        </div>
    </div>

</div>

<div class="row g-4 mt-1">

    <div class="col-lg-8">
        <div class="card-premium p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-1">Sales Overview</h6>
                    <small class="text-muted">Grafik omzet dan order harian</small>
                </div>
            </div>

            <div style="height:340px">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-premium p-4 h-100">
            <h6 class="fw-bold mb-3">Best Seller</h6>

            @forelse($bestSellers as $item)
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ $item->name }}</div>
                        <small class="text-muted">{{ $item->total_qty }} terjual</small>
                    </div>
                    <div class="fw-bold text-primary-soft">
                        Rp {{ number_format($item->total_sales,0,',','.') }}
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    Belum ada penjualan.
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const salesCtx = document.getElementById('salesChart');

new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [
            {
                label: 'Revenue',
                data: @json($chartRevenue),
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14,165,233,.12)',
                fill: true,
                tension: .45,
                pointRadius: 3,
                pointHoverRadius: 6
            },
            {
                label: 'Orders',
                data: @json($chartOrders),
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,.08)',
                fill: false,
                tension: .45,
                pointRadius: 3,
                pointHoverRadius: 6,
                yAxisID: 'ordersAxis'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                display: true,
                labels: {
                    usePointStyle: true,
                    boxWidth: 8
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        if (context.dataset.label === 'Revenue') {
                            return 'Revenue: Rp ' + Number(context.raw).toLocaleString('id-ID');
                        }

                        return 'Orders: ' + context.raw;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + Number(value).toLocaleString('id-ID');
                    }
                },
                grid: {
                    color: 'rgba(148,163,184,.15)'
                }
            },
            ordersAxis: {
                beginAtZero: true,
                position: 'right',
                grid: {
                    drawOnChartArea: false
                },
                ticks: {
                    precision: 0
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
@endpush