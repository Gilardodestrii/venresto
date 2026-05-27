<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Recipe;
use App\Services\TenantContext;

class MenuCostingController extends Controller
{
    public function index()
    {
        $tenant = TenantContext::get();
        $outletId = session('current_outlet_id');

        $menuItems = MenuItem::where('tenant_id', $tenant->id)
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        $costings = $menuItems->map(function ($menuItem) use ($tenant, $outletId) {
            $recipes = Recipe::with('material')
                ->where('tenant_id', $tenant->id)
                ->where('outlet_id', $outletId)
                ->where('item_id', $menuItem->id)
                ->get();

            $hpp = $recipes->sum(function ($recipe) {
                return (float) $recipe->qty * (float) ($recipe->material?->cost_per_unit ?? 0);
            });

            $price = (float) $menuItem->price;
            $margin = $price - $hpp;
            $foodCostPercent = $price > 0 ? ($hpp / $price) * 100 : 0;

            return (object) [
                'menu' => $menuItem,
                'recipes' => $recipes,
                'hpp' => $hpp,
                'price' => $price,
                'margin' => $margin,
                'food_cost_percent' => $foodCostPercent,
            ];
        });

        return view('admin.menu-costing.index', compact('costings'));
    }
}
