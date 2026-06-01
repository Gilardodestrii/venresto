<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Outlet;
use App\Services\TenantContext;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenant = TenantContext::get();

        abort_if(!$tenant, 404);

        $month = $request->input('month', now()->format('Y-m'));
        $outletId = $request->input('outlet_id');

        $date = Carbon::createFromFormat('Y-m', $month);
        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();

        $outlets = Outlet::where('tenant_id', $tenant->id)
            ->orderBy('name')
            ->get();

        $ordersQuery = Order::query()
            ->where('tenant_id', $tenant->id)
            ->whereBetween('created_at', [$start, $end])
            ->when($outletId, fn ($q) => $q->where('outlet_id', $outletId));

        $users = User::where('tenant_id', $tenant->id)->count();

        $orders = (clone $ordersQuery)->count();

        $revenue = (clone $ordersQuery)
            ->where('status', 'paid')
            ->sum('grand_total');

        $menu = MenuItem::where('tenant_id', $tenant->id)->count();

        $paidOrders = (clone $ordersQuery)
            ->where('status', 'paid')
            ->count();

        $avgOrder = $paidOrders > 0 ? $revenue / $paidOrders : 0;

        $salesDaily = (clone $ordersQuery)
            ->selectRaw('DAY(created_at) as day')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(CASE WHEN status = "paid" THEN grand_total ELSE 0 END) as total_revenue')
            ->groupBy(DB::raw('DAY(created_at)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $days = range(1, $date->daysInMonth);

        $chartLabels = collect($days)->map(fn ($day) => str_pad($day, 2, '0', STR_PAD_LEFT))->values();

        $chartRevenue = collect($days)->map(fn ($day) => (float) ($salesDaily[$day]->total_revenue ?? 0))->values();

        $chartOrders = collect($days)->map(fn ($day) => (int) ($salesDaily[$day]->total_orders ?? 0))->values();

        $bestSellers = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->where('orders.tenant_id', $tenant->id)
            ->where('order_items.tenant_id', $tenant->id)
            ->whereBetween('orders.created_at', [$start, $end])
            ->when($outletId, fn ($q) => $q->where('orders.outlet_id', $outletId))
            ->select(
                'menu_items.name',
                DB::raw('SUM(order_items.qty) as total_qty'),
                DB::raw('SUM(order_items.qty * order_items.price) as total_sales')
            )
            ->groupBy('menu_items.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'tenant',
            'outlets',
            'month',
            'outletId',
            'users',
            'orders',
            'revenue',
            'menu',
            'paidOrders',
            'avgOrder',
            'chartLabels',
            'chartRevenue',
            'chartOrders',
            'bestSellers'
        ));
    }
}