<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
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

        $ordersQuery = Order::with(['cashier'])
            ->where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate]);

        $totalRevenue = (clone $ordersQuery)->sum('grand_total');
        $totalOrders = (clone $ordersQuery)->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $totalItemsSold = OrderItem::whereHas('order', function ($query) use ($tenant, $outletId, $startDate, $endDate) {
                $query->where('tenant_id', $tenant->id)
                    ->where('outlet_id', $outletId)
                    ->where('status', 'paid')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum('qty');

        $bestSellingMenus = OrderItem::select(
                'menu_item_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(qty * price) as total_sales')
            )
            ->with('menuItem')
            ->whereHas('order', function ($query) use ($tenant, $outletId, $startDate, $endDate) {
                $query->where('tenant_id', $tenant->id)
                    ->where('outlet_id', $outletId)
                    ->where('status', 'paid')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('menu_item_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $dailySales = Order::select(
                DB::raw('DATE(created_at) as sales_date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(grand_total) as total_revenue')
            )
            ->where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('sales_date')
            ->get();

        $orders = (clone $ordersQuery)
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.reports.sales', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'averageOrderValue',
            'totalItemsSold',
            'bestSellingMenus',
            'dailySales',
            'orders'
        ));
    }
}
