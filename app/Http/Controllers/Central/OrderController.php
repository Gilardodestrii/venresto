<?php

namespace App\Http\Controllers\Central;

use App\Actions\Orders\ProcessOrderPaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\TenantContext;
use Illuminate\Validation\ValidationException;

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

        $order = Order::with([
                'items.menuItem',
                'cashier',
                'payments'
            ])
            ->where('tenant_id', $tenantModel->id)
            ->where('outlet_id', $outletId)
            ->where('id', $orderId)
            ->firstOrFail();

        return view('admin.orders.show', compact('order'));
    }

    public function updatePayment(
        ProcessPaymentRequest $request,
        $tenant,
        $orderId,
        ProcessOrderPaymentAction $action
    ) {
        $tenantModel = TenantContext::get();
        $outletId = session('current_outlet_id');

        $order = Order::where('tenant_id', $tenantModel->id)
            ->where('outlet_id', $outletId)
            ->where('id', $orderId)
            ->firstOrFail();

        try {
            $payment = $action->handle($order, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses.',
                'payment' => $payment,
                'order_status' => 'paid'
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }
    }
}
