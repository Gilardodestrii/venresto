<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Recipe;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function deductFromOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {

            $order->loadMissing('items');

            foreach ($order->items as $orderItem) {

                $recipes = Recipe::where('tenant_id', $order->tenant_id)
                    ->where('item_id', $orderItem->menu_item_id)
                    ->with('material')
                    ->get();

                foreach ($recipes as $recipe) {

                    $material = $recipe->material;

                    if (!$material) {
                        continue;
                    }

                    $deductQty = $recipe->qty * $orderItem->qty;

                    if ($material->stock < $deductQty) {
                        abort(422, "Stock {$material->name} tidak cukup.");
                    }

                    $material->decrement('stock', $deductQty);

                    StockMovement::create([
                        'tenant_id' => $order->tenant_id,
                        'material_id' => $material->id,
                        'type' => 'out',
                        'qty' => $deductQty,
                        'ref' => 'ORDER-' . $order->id,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
        });
    }
}