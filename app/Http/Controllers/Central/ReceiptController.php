<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\TenantContext;

class ReceiptController extends Controller
{
    public function show($tenant, Order $order)
    {
        $tenantModel = TenantContext::get();

        abort_if($order->tenant_id !== $tenantModel->id, 404);

        $order->load([
            'items.menuItem',
            'cashier',
        ]);

        return view('admin.pos.receipt', compact('order'));
    }
}
