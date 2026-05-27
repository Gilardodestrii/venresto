@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Profit Report</h3>
            <div class="text-muted">Analisa profit, HPP, margin, dan food cost berdasarkan recipe menu</div>
        </div>

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
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Revenue</div>
                    <h4 class="fw-bold text-success mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Estimated HPP</div>
                    <h4 class="fw-bold text-danger mb-0">Rp {{ number_format($totalHpp, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Gross Profit</div>
                    <h4 class="fw-bold {{ $grossProfit >= 0 ? 'text-primary' : 'text-danger' }} mb-0">
                        Rp {{ number_format($grossProfit, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Gross Margin</div>
                    <h4 class="fw-bold {{ $grossMarginPercent >= 30 ? 'text-success' : 'text-warning' }} mb-0">
                        {{ number_format($grossMarginPercent, 1) }}%
                    </h4>
                    <small class="text-muted">Food Cost {{ number_format($foodCostPercent, 1) }}%</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-1">Most Profitable Menu</h5>
                    <div class="text-muted small">Ranking menu dengan gross profit tertinggi</div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Menu</th>
                                <th>Revenue</th>
                                <th>Profit</th>
                                <th>Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mostProfitableMenus as $row)
                                <tr>
                                    <td class="px-4 fw-semibold">{{ $row->menu?->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                                    <td><span class="fw-bold text-success">Rp {{ number_format($row->gross_profit, 0, ',', '.') }}</span></td>
                                    <td>{{ number_format($row->margin_percent, 1) }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data profit.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-1">Highest Food Cost</h5>
                    <div class="text-muted small">Menu dengan food cost tertinggi, perlu review harga atau recipe</div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Menu</th>
                                <th>HPP</th>
                                <th>Revenue</th>
                                <th>Food Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($highestFoodCostMenus as $row)
                                <tr>
                                    <td class="px-4 fw-semibold">{{ $row->menu?->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($row->total_hpp, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                                    <td>
                                        @if($row->food_cost_percent <= 35)
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                {{ number_format($row->food_cost_percent, 1) }}%
                                            </span>
                                        @elseif($row->food_cost_percent <= 50)
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                                {{ number_format($row->food_cost_percent, 1) }}%
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                                {{ number_format($row->food_cost_percent, 1) }}%
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data food cost.</td>
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
            <h5 class="fw-bold mb-1">Menu Profitability Detail</h5>
            <div class="text-muted small">Detail revenue, HPP, gross profit, margin, dan food cost per menu</div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Menu</th>
                        <th>Qty Sold</th>
                        <th>Revenue</th>
                        <th>HPP / Item</th>
                        <th>Total HPP</th>
                        <th>Gross Profit</th>
                        <th>Margin</th>
                        <th>Food Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($profitRows as $row)
                        <tr>
                            <td class="px-4 fw-semibold">{{ $row->menu?->name ?? '-' }}</td>
                            <td>{{ number_format($row->total_qty, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($row->hpp_per_item, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($row->total_hpp, 0, ',', '.') }}</td>
                            <td>
                                <span class="fw-bold {{ $row->gross_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($row->gross_profit, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>{{ number_format($row->margin_percent, 1) }}%</td>
                            <td>{{ number_format($row->food_cost_percent, 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-cash-coin fs-1 d-block mb-2"></i>
                                Belum ada data penjualan paid pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
