@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Sales Report</h3>
            <div class="text-muted">Analisa revenue, transaksi, item terjual, dan menu terlaris</div>
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

            <a href="{{ route('tenant.admin.reports.sales.export', [
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
                <div class="text-muted small mb-1">Total Revenue</div>
                <h4 class="fw-bold text-success mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5"><div class="card-body p-4">
                <div class="text-muted small mb-1">Total Orders</div>
                <h4 class="fw-bold mb-0">{{ number_format($totalOrders) }}</h4>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5"><div class="card-body p-4">
                <div class="text-muted small mb-1">Average Order</div>
                <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h4>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5"><div class="card-body p-4">
                <div class="text-muted small mb-1">Items Sold</div>
                <h4 class="fw-bold text-warning mb-0">{{ number_format($totalItemsSold, 0, ',', '.') }}</h4>
            </div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-5 mb-4">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-1">Revenue Trend</h5>
            <div class="text-muted small">Grafik revenue harian berdasarkan transaksi paid</div>
        </div>
        <div class="card-body p-4">
            <div style="height: 320px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-1">Best Selling Menu</h5>
                    <div class="text-muted small">10 menu terlaris berdasarkan qty terjual</div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Menu</th>
                                <th>Qty Sold</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellingMenus as $item)
                                <tr>
                                    <td class="px-4 fw-semibold">{{ $item->menuItem?->name ?? '-' }}</td>
                                    <td><span class="fw-bold">{{ number_format($item->total_qty, 0, ',', '.') }}</span></td>
                                    <td><span class="fw-bold text-success">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada data menu terjual.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-1">Daily Sales</h5>
                    <div class="text-muted small">Ringkasan revenue per hari</div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th>Orders</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailySales as $day)
                                <tr>
                                    <td class="px-4 fw-semibold">{{ \Illuminate\Support\Carbon::parse($day->sales_date)->format('d M Y') }}</td>
                                    <td>{{ number_format($day->total_orders) }}</td>
                                    <td><span class="fw-bold text-success">Rp {{ number_format($day->total_revenue, 0, ',', '.') }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada penjualan harian.</td>
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
            <h5 class="fw-bold mb-1">Paid Orders</h5>
            <div class="text-muted small">Riwayat transaksi paid sesuai periode laporan</div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th>Kode</th>
                        <th>Customer</th>
                        <th>Cashier</th>
                        <th>Payment</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-4">
                                <div class="fw-semibold">{{ $order->created_at?->format('d M Y') }}</div>
                                <small class="text-muted">{{ $order->created_at?->format('H:i') }}</small>
                            </td>
                            <td class="fw-semibold">{{ $order->code }}</td>
                            <td>{{ $order->customer_name ?? '-' }}</td>
                            <td>{{ $order->cashier?->name ?? '-' }}</td>
                            <td>{{ strtoupper($order->payment_method ?? '-') }}</td>
                            <td><span class="fw-bold text-success">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                                Tidak ada transaksi paid pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="card-footer bg-white border-0 px-4 py-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const revenueLabels = @json($dailySales->map(fn ($day) => \Illuminate\Support\Carbon::parse($day->sales_date)->format('d M'))->values());
const revenueData = @json($dailySales->map(fn ($day) => (float) $day->total_revenue)->values());

const revenueCanvas = document.getElementById('revenueChart');

if (revenueCanvas) {
    new Chart(revenueCanvas, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue',
                data: revenueData,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y || 0);
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
}
</script>
@endpush
