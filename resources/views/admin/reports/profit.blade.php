@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold mb-1">Profit Report</h3>
            <div class="text-gray-500 text-sm">Analisa profit, HPP, margin, dan food cost berdasarkan recipe menu</div>
        </div>

        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-sm text-gray-500 mb-1">Dari</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Sampai</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition-colors">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border-0 rounded-2xl shadow-sm p-5">
            <div class="text-gray-500 text-sm mb-1">Revenue</div>
            <h4 class="font-bold text-green-600 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
        </div>

        <div class="bg-white border-0 rounded-2xl shadow-sm p-5">
            <div class="text-gray-500 text-sm mb-1">Estimated HPP</div>
            <h4 class="font-bold text-red-600 mb-0">Rp {{ number_format($totalHpp, 0, ',', '.') }}</h4>
        </div>

        <div class="bg-white border-0 rounded-2xl shadow-sm p-5">
            <div class="text-gray-500 text-sm mb-1">Gross Profit</div>
            <h4 class="font-bold {{ $grossProfit >= 0 ? 'text-blue-600' : 'text-red-600' }} mb-0">
                Rp {{ number_format($grossProfit, 0, ',', '.') }}
            </h4>
        </div>

        <div class="bg-white border-0 rounded-2xl shadow-sm p-5">
            <div class="text-gray-500 text-sm mb-1">Gross Margin</div>
            <h4 class="font-bold {{ $grossMarginPercent >= 30 ? 'text-green-600' : 'text-yellow-600' }} mb-0">
                {{ number_format($grossMarginPercent, 1) }}%
            </h4>
            <small class="text-gray-500">Food Cost {{ number_format($foodCostPercent, 1) }}%</small>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white border-0 rounded-2xl shadow-sm overflow-hidden h-full">
            <div class="bg-white border-0 px-5 py-4">
                <h5 class="font-bold mb-1">Most Profitable Menu</h5>
                <div class="text-gray-500 text-sm">Ranking menu dengan gross profit tertinggi</div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full align-middle mb-0">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Menu</th>
                            <th class="px-4 py-3 text-left">Revenue</th>
                            <th class="px-4 py-3 text-left">Profit</th>
                            <th class="px-4 py-3 text-left">Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mostProfitableMenus as $row)
                            <tr class="border-b border-gray-100 last:border-0">
                                <td class="px-4 py-3 font-semibold">{{ $row->menu?->name ?? '-' }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                                <td class="px-4 py-3"><span class="font-bold text-green-600">Rp {{ number_format($row->gross_profit, 0, ',', '.') }}</span></td>
                                <td class="px-4 py-3">{{ number_format($row->margin_percent, 1) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-500">Belum ada data profit.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border-0 rounded-2xl shadow-sm overflow-hidden h-full">
            <div class="bg-white border-0 px-5 py-4">
                <h5 class="font-bold mb-1">Highest Food Cost</h5>
                <div class="text-gray-500 text-sm">Menu dengan food cost tertinggi, perlu review harga atau recipe</div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full align-middle mb-0">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Menu</th>
                            <th class="px-4 py-3 text-left">HPP</th>
                            <th class="px-4 py-3 text-left">Revenue</th>
                            <th class="px-4 py-3 text-left">Food Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($highestFoodCostMenus as $row)
                            <tr class="border-b border-gray-100 last:border-0">
                                <td class="px-4 py-3 font-semibold">{{ $row->menu?->name ?? '-' }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($row->total_hpp, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @if($row->food_cost_percent <= 35)
                                        <span class="inline-block bg-green-100 text-green-700 rounded-full px-3 py-1 text-sm font-medium">
                                            {{ number_format($row->food_cost_percent, 1) }}%
                                        </span>
                                    @elseif($row->food_cost_percent <= 50)
                                        <span class="inline-block bg-yellow-100 text-yellow-700 rounded-full px-3 py-1 text-sm font-medium">
                                            {{ number_format($row->food_cost_percent, 1) }}%
                                        </span>
                                    @else
                                        <span class="inline-block bg-red-100 text-red-700 rounded-full px-3 py-1 text-sm font-medium">
                                            {{ number_format($row->food_cost_percent, 1) }}%
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-500">Belum ada data food cost.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white border-0 rounded-2xl shadow-sm overflow-hidden">
        <div class="bg-white border-0 px-5 py-4">
            <h5 class="font-bold mb-1">Menu Profitability Detail</h5>
            <div class="text-gray-500 text-sm">Detail revenue, HPP, gross profit, margin, dan food cost per menu</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full align-middle mb-0">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Menu</th>
                        <th class="px-4 py-3 text-left">Qty Sold</th>
                        <th class="px-4 py-3 text-left">Revenue</th>
                        <th class="px-4 py-3 text-left">HPP / Item</th>
                        <th class="px-4 py-3 text-left">Total HPP</th>
                        <th class="px-4 py-3 text-left">Gross Profit</th>
                        <th class="px-4 py-3 text-left">Margin</th>
                        <th class="px-4 py-3 text-left">Food Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($profitRows as $row)
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="px-4 py-3 font-semibold">{{ $row->menu?->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ number_format($row->total_qty, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($row->hpp_per_item, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($row->total_hpp, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="font-bold {{ $row->gross_profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($row->gross_profit, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ number_format($row->margin_percent, 1) }}%</td>
                            <td class="px-4 py-3">{{ number_format($row->food_cost_percent, 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-500">
                                <i class="bi bi-cash-coin text-5xl block mb-3"></i>
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
