<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Recipe;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProfitReportController extends Controller
{
    public function index(Request $request)
    {
        $tenant = TenantContext::get();
        $outletId = session('current_outlet_id');

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $soldMenus = OrderItem::select(
                'menu_item_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(qty * price) as revenue')
            )
            ->with('menuItem')
            ->whereHas('order', function ($query) use ($tenant, $outletId, $startDate, $endDate) {
                $query->where('tenant_id', $tenant->id)
                    ->where('outlet_id', $outletId)
                    ->where('status', 'paid')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('menu_item_id')
            ->orderByDesc('revenue')
            ->get();

        $profitRows = $soldMenus->map(function ($soldMenu) use ($tenant, $outletId) {
            $hppPerItem = Recipe::with('material')
                ->where('tenant_id', $tenant->id)
                ->where('outlet_id', $outletId)
                ->where('item_id', $soldMenu->menu_item_id)
                ->get()
                ->sum(function ($recipe) {
                    return (float) $recipe->qty * (float) ($recipe->material?->cost_per_unit ?? 0);
                });

            $totalHpp = $hppPerItem * (float) $soldMenu->total_qty;
            $revenue = (float) $soldMenu->revenue;
            $grossProfit = $revenue - $totalHpp;
            $marginPercent = $revenue > 0 ? ($grossProfit / $revenue) * 100 : 0;
            $foodCostPercent = $revenue > 0 ? ($totalHpp / $revenue) * 100 : 0;

            return (object) [
                'menu' => $soldMenu->menuItem,
                'total_qty' => (float) $soldMenu->total_qty,
                'revenue' => $revenue,
                'hpp_per_item' => $hppPerItem,
                'total_hpp' => $totalHpp,
                'gross_profit' => $grossProfit,
                'margin_percent' => $marginPercent,
                'food_cost_percent' => $foodCostPercent,
            ];
        });

        $totalRevenue = $profitRows->sum('revenue');
        $totalHpp = $profitRows->sum('total_hpp');
        $grossProfit = $profitRows->sum('gross_profit');
        $grossMarginPercent = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;
        $foodCostPercent = $totalRevenue > 0 ? ($totalHpp / $totalRevenue) * 100 : 0;

        $mostProfitableMenus = $profitRows
            ->sortByDesc('gross_profit')
            ->take(10)
            ->values();

        $highestFoodCostMenus = $profitRows
            ->sortByDesc('food_cost_percent')
            ->take(10)
            ->values();

        return view('admin.reports.profit', compact(
            'startDate',
            'endDate',
            'profitRows',
            'totalRevenue',
            'totalHpp',
            'grossProfit',
            'grossMarginPercent',
            'foodCostPercent',
            'mostProfitableMenus',
            'highestFoodCostMenus'
        ));
    }
}
