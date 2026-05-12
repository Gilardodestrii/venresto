<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\TenantContext;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $tenant = TenantContext::get();
        $outletId = session('current_outlet_id');

        $orders = Order::with(['items.menuItem'])
            ->where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($tenant, $orderId)
    {
        $tenantModel = TenantContext::get();
        $outletId = session('current_outlet_id');

        $order = Order::with(['items.menuItem', 'cashier'])
            ->where('tenant_id', $tenantModel->id)
            ->where('outlet_id', $outletId)
            ->where('id', $orderId)
            ->firstOrFail();

        return view('admin.orders.show', compact('order'));
    }

    public function updatePayment(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:paid,pending_payment',
            'payment_method' => 'required|string|max:50',
        ]);

        $order->update([
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'cashier_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Payment updated successfully',
            'status' => $order->status
        ]);
    }
}
