<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Services\TenantContext;

class KitchenDisplayController extends Controller
{
    public function index()
    {
        $tenant = TenantContext::get();

        $items = OrderItem::with([
                'order',
                'menuItem'
            ])
            ->where('tenant_id', $tenant->id)
            ->whereHas('order', function ($q) {
                $q->where('outlet_id', session('current_outlet_id'));
            })
            ->whereIn('kitchen_status', [
                'new',
                'cook',
                'ready'
            ])
            ->latest()
            ->get();

        return view(
            'admin.kitchen.index',
            compact('items', 'tenant')
        );
    }

    public function live()
    {
        $tenant = TenantContext::get();

        $items = OrderItem::with([
                'order',
                'menuItem'
            ])
            ->where('tenant_id', $tenant->id)
            ->whereHas('order', function ($q) {
                $q->where('outlet_id', session('current_outlet_id'));
            })
            ->whereIn('kitchen_status', [
                'new',
                'cook',
                'ready'
            ])
            ->latest()
            ->get();

        return response()->json($items);
    }

    public function updateStatus(
        Request $request,
        $tenant,
        $id
    )
    {
        $tenantModel = TenantContext::get();

        $request->validate([
            'status' => 'required|in:new,cook,ready,served'
        ]);

        $orderItem = OrderItem::with('order')
            ->findOrFail($id);

        abort_if(!$orderItem->order, 404);

        abort_if(
            $orderItem->order->tenant_id != $tenantModel->id,
            403
        );

        abort_if(
            $orderItem->order->outlet_id != session('current_outlet_id'),
            403
        );

        $orderItem->update([
            'kitchen_status' => $request->status
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    
}