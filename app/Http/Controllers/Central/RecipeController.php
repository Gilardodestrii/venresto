<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MenuItem;
use App\Models\Recipe;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::tenant()
            ->with(['menuItem', 'material'])
            ->latest()
            ->paginate(20);

        return view('admin.recipes.index', compact('recipes'));
    }

    public function create()
    {
        $tenant = TenantContext::get();

        $menuItems = MenuItem::where('tenant_id', $tenant->id)
            ->orderBy('name')
            ->get();

        $materials = Material::tenant()
            ->currentOutlet()
            ->orderBy('name')
            ->get();

        return view('admin.recipes.create', compact('menuItems', 'materials'));
    }

    public function store(Request $request)
    {
        $tenant = TenantContext::get();

        $validated = $request->validate([
            'item_id' => ['required', 'exists:menu_items,id'],
            'recipes' => ['required', 'array', 'min:1'],
            'recipes.*.material_id' => ['required', 'exists:materials,id'],
            'recipes.*.qty' => ['required', 'numeric', 'min:0.001'],
        ]);

        $menuItem = MenuItem::where('tenant_id', $tenant->id)
            ->where('id', $validated['item_id'])
            ->firstOrFail();

        DB::transaction(function () use ($validated, $tenant, $menuItem) {

            foreach ($validated['recipes'] as $recipeData) {

                $material = Material::tenant()
                    ->currentOutlet()
                    ->where('id', $recipeData['material_id'])
                    ->firstOrFail();

                Recipe::updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'outlet_id' => session('current_outlet_id'),
                        'item_id' => $menuItem->id,
                        'material_id' => $material->id,
                    ],
                    [
                        'qty' => $recipeData['qty'],
                    ]
                );
            }
        });

        return redirect()
            ->route('tenant.admin.recipes.index', $tenant->slug)
            ->with('success', 'Recipe berhasil disimpan.');
    }

    public function destroy(string $tenant, Recipe $recipe)
    {
        abort_if($recipe->tenant_id !== TenantContext::get()->id, 404);

        $recipe->delete();

        return back()->with('success', 'Resep berhasil dihapus.');
    }
}
