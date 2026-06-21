@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold text-lg mb-1">Sales Report</h3>
            <div class="text-gray-500 text-sm">Analisa revenue, transaksi, item terjual, dan menu terlaris</div>
        </div>

        <div class="flex flex-wrap gap-2 items-end">
            <form method="GET" class="flex flex-wrap gap-2 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Dari</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Sampai</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </form>

            <a href="{{ route('tenant.admin.reports.sales.export', [
                    $currentTenant->slug,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ]) }}"
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i>
                Export CSV
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow-sm rounded-2xl p-5">
            <div class="text-gray-500 text-xs mb-1">Total Revenue</div>
            <h4 class="font-bold text-green-600 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
        </div>
        <div class="bg-white shadow-sm rounded-2xl p-5">
            <div class="text-gray-500 text-xs mb-1">Total Orders</div>
            <h4 class="font-bold mb-0">{{ number_format($totalOrders) }}</h4>
        </div>
        <div class="bg-white shadow-sm rounded-2xl p-5">
            <div class="text-gray-500 text-xs mb-1">Average Order</div>
            <h4 class="font-bold text-blue-600 mb-0">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h4>
        </div>
        <div class="bg-white shadow-sm rounded-2xl p-5">
            <div class="text-gray-500 text-xs mb-1">Items Sold</div>
            <h4 class="font-bold text-yellow-600 mb-0">{{ number_format($totalItemsSold, 0, ',', '.') }}</h4>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-2xl mb-6">
        <div class="border-b border-gray-100 px-5 py-4">
            <h5 class="font-bold mb-1">Revenue Trend</h5>
            <div class="text-gray-500 text-xs">Grafik revenue harian berdasarkan transaksi paid</div>
        </div>
        <div class="p-5">
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
        <div class="lg:col-span-7">
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden h-full">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h5 class="font-bold mb-1">Best Selling Menu</h5>
                    <div class="text-gray-500 text-xs">10 menu terlaris berdasarkan qty terjual</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Sold</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($bestSellingMenus as $item)
                                <tr>
                                    <td class="px-4 py-3 font-semibold">{{ $item->menuItem?->name ?? '-' }}</td>
                                    <td class="px-4 py-3"><span class="font-bold">{{ number_format($item->total_qty, 0, ',', '.') }}</span></td>
                                    <td class="px-4 py-3"><span class="font-bold text-green-600">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-gray-400">Belum ada data menu terjual.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden h-full">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h5 class="font-bold mb-1">Daily Sales</h5>
                    <div class="text-gray-500 text-xs">Ringkasan revenue per hari</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($dailySales as $day)
                                <tr>
                                    <td class="px-4 py-3 font-semibold">{{ \Illuminate\Support\Carbon::parse($day->sales_date)->format('d M Y') }}</td>
                                    <td class="px-4 py-3">{{ number_format($day->total_orders) }}</td>
                                    <td class="px-4 py-3"><span class="font-bold text-green-600">Rp {{ number_format($day->total_revenue, 0, ',', '.') }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-gray-400">Belum ada penjualan harian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h5 class="font-bold mb-1">Paid Orders</h5>
            <div class="text-gray-500 text-xs">Riwayat transaksi paid sesuai periode laporan</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cashier</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-semibold">{{ $order->created_at?->format('d M Y') }}</div>
                                <small class="text-gray-400">{{ $order->created_at?->format('H:i') }}</small>
                            </td>
                            <td class="px-4 py-3 font-semibold">{{ $order->code }}</td>
                            <td class="px-4 py-3">{{ $order->customer_name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $order->cashier?->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ strtoupper($order->payment_method ?? '-') }}</td>
                            <td class="px-4 py-3"><span class="font-bold text-green-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400">
                                <i class="bi bi-receipt text-4xl d-block mb-2"></i>
                                Tidak ada transaksi paid pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="border-t border-gray-100 px-4 py-3 bg-white">
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
