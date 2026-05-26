<?php

namespace App\Services;

use App\Models\Material;
use App\Models\Order;
use App\Models\Recipe;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    public function deductFromOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order = Order::where('id', $order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->inventory_processed_at) {
                return;
            }

            $order->loadMissing('items');

            foreach ($order->items as $orderItem) {
                $recipes = Recipe::where('tenant_id', $order->tenant_id)
                    ->where('outlet_id', $order->outlet_id)
                    ->where('item_id', $orderItem->menu_item_id)
                    ->get();

                foreach ($recipes as $recipe) {
                    $material = Material::where('tenant_id', $order->tenant_id)
                        ->where('outlet_id', $order->outlet_id)
                        ->where('id', $recipe->material_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$material) {
                        continue;
                    }

                    $orderQty = $orderItem->qty ?? $orderItem->quantity ?? 0;
                    $deductQty = (float) $recipe->qty * (float) $orderQty;

                    if ($deductQty <= 0) {
                        continue;
                    }

                    if ((float) $material->stock < $deductQty) {
                        throw ValidationException::withMessages([
                            'stock' => "Stock {$material->name} tidak cukup.",
                        ]);
                    }

                    $before = (float) $material->stock;
                    $after = $before - $deductQty;

                    $material->decrement('stock', $deductQty);

                    StockMovement::create([
                        'tenant_id' => $order->tenant_id,
                        'outlet_id' => $order->outlet_id,
                        'material_id' => $material->id,
                        'type' => 'out',
                        'qty' => $deductQty,
                        'stock_before' => $before,
                        'stock_after' => $after,
                        'ref' => 'ORDER-' . $order->id,
                        'note' => 'POS payment stock deduction',
                        'source_type' => 'order',
                        'source_id' => $order->id,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            $order->forceFill([
                'inventory_processed_at' => now(),
            ])->save();
        });
    }

    public function restoreFromOrder(Order $order, string $refPrefix = 'RESTORE-ORDER'): void
    {
        DB::transaction(function () use ($order, $refPrefix) {
            $order = Order::where('id', $order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$order->inventory_processed_at) {
                return;
            }

            $order->loadMissing('items');

            foreach ($order->items as $orderItem) {
                $recipes = Recipe::where('tenant_id', $order->tenant_id)
                    ->where('outlet_id', $order->outlet_id)
                    ->where('item_id', $orderItem->menu_item_id)
                    ->get();

                foreach ($recipes as $recipe) {
                    $material = Material::where('tenant_id', $order->tenant_id)
                        ->where('outlet_id', $order->outlet_id)
                        ->where('id', $recipe->material_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$material) {
                        continue;
                    }

                    $orderQty = $orderItem->qty ?? $orderItem->quantity ?? 0;
                    $restoreQty = (float) $recipe->qty * (float) $orderQty;

                    if ($restoreQty <= 0) {
                        continue;
                    }

                    $before = (float) $material->stock;
                    $after = $before + $restoreQty;

                    $material->increment('stock', $restoreQty);

                    StockMovement::create([
                        'tenant_id' => $order->tenant_id,
                        'outlet_id' => $order->outlet_id,
                        'material_id' => $material->id,
                        'type' => 'in',
                        'qty' => $restoreQty,
                        'stock_before' => $before,
                        'stock_after' => $after,
                        'ref' => $refPrefix . '-' . $order->id,
                        'note' => 'Order void stock restore',
                        'source_type' => 'order_void',
                        'source_id' => $order->id,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            $order->forceFill([
                'inventory_processed_at' => null,
            ])->save();
        });
    }
}
