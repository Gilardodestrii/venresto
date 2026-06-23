@extends('layouts.admin')

@section('page-title','Dashboard')

@section('content')

<div class="flex flex-wrap justify-between items-center gap-3 mb-4">
    <div>
        <h4 class="font-bold mb-1">Dashboard</h4>
        <div class="text-gray-500 text-sm">Ringkasan performa penjualan tenant bulan ini.</div>
    </div>

    <form method="GET" class="flex flex-wrap gap-2">
        <input type="month"
               name="month"
               value="{{ $month }}"
               class="w-full max-w-[180px] px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

        <select name="outlet_id" class="w-full max-w-[220px] px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Semua Outlet</option>
            @foreach($outlets as $outlet)
                <option value="{{ $outlet->id }}" @selected((string) $outletId === (string) $outlet->id)>
                    {{ $outlet->name }}
                </option>
            @endforeach
        </select>

        <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
            <i class="bi bi-funnel me-1"></i> Filter
        </button>
    </form>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 gap-3">

    <div class="bg-white rounded-lg shadow-sm p-3 h-full">
        <div class="flex justify-between">
            <div>
                <small class="text-gray-500">Users</small>
                <h4 class="font-bold mb-0">{{ $users }}</h4>
            </div>
            <div class="text-gray-400"><i class="bi bi-people"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-3 h-full">
        <div class="flex justify-between">
            <div>
                <small class="text-gray-500">Orders</small>
                <h4 class="font-bold mb-0">{{ $orders }}</h4>
            </div>
            <div class="text-gray-400"><i class="bi bi-journal"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-3 h-full">
        <div class="flex justify-between">
            <div>
                <small class="text-gray-500">Revenue</small>
                <h4 class="font-bold text-blue-500 mb-0">
                    Rp {{ number_format($revenue,0,',','.') }}
                </h4>
            </div>
            <div class="text-gray-400"><i class="bi bi-cash"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-3 h-full">
        <div class="flex justify-between">
            <div>
                <small class="text-gray-500">Avg Order</small>
                <h4 class="font-bold mb-0">
                    Rp {{ number_format($avgOrder,0,',','.') }}
                </h4>
            </div>
            <div class="text-gray-400"><i class="bi bi-graph-up"></i></div>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-1">

    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-4 h-full">
        <div class="flex justify-between items-center mb-3">
            <div>
                <h6 class="font-bold mb-1">Sales Overview</h6>
                <small class="text-gray-500">Grafik omzet dan order harian</small>
            </div>
        </div>

        <div style="height:340px">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-4 h-full">
        <h6 class="font-bold mb-3">Best Seller</h6>

        @forelse($bestSellers as $item)
            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                <div>
                    <div class="font-semibold">{{ $item->name }}</div>
                    <small class="text-gray-500">{{ $item->total_qty }} terjual</small>
                </div>
                <div class="font-bold text-blue-500">
                    Rp {{ number_format($item->total_sales,0,',','.') }}
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-12">
                <i class="bi bi-inbox text-4xl d-block mb-2"></i>
                Belum ada penjualan.
            </div>
        @endforelse
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
