<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        try {
            $tenantModel = TenantContext::get();

            $request->validate([
                'status' => 'required|in:new,cook,ready,served'
            ]);

            $orderItem = OrderItem::with('order')->findOrFail($id);

            if (!$orderItem->order) {
                Log::warning("Kitchen update: order not found for item {$id}");
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found for this item'
                ], 404);
            }

            if ($orderItem->order->tenant_id != $tenantModel->id) {
                Log::warning("Kitchen update: tenant mismatch for item {$id}");
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant mismatch'
                ], 403);
            }

            // Don't block on outlet mismatch — orders may not have outlet_id set yet.
            // Tenant scope already covers authorization.

            $orderItem->update([
                'kitchen_status' => $request->status
            ]);

            return response()->json([
                'success' => true
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status value'
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order item not found'
            ], 404);

        } catch (\Throwable $e) {
            Log::error("Kitchen updateStatus error: " . $e->getMessage(), [
                'item_id' => $id,
                'status'  => $request->input('status'),
                'trace'   => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
