<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MenuItem;
use App\Models\Recipe;
use App\Services\TenantContext;
use Illuminate\Http\Request;

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
            ->orderBy('name')
            ->get();

        return view('admin.recipes.create', compact('menuItems', 'materials'));
    }

    public function store(Request $request)
    {
        $tenant = TenantContext::get();

        $request->validate([
            'item_id' => ['required', 'exists:menu_items,id'],
            'material_id' => ['required', 'exists:materials,id'],
            'qty' => ['required', 'numeric', 'min:0.001'],
        ]);

        $material = Material::tenant()
            ->where('id', $request->material_id)
            ->firstOrFail();

        $menuItem = MenuItem::where('tenant_id', $tenant->id)
            ->where('id', $request->item_id)
            ->firstOrFail();

        Recipe::updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'item_id' => $menuItem->id,
                'material_id' => $material->id,
            ],
            [
                'qty' => $request->qty,
            ]
        );

        return redirect()
            ->route('tenant.admin.recipes.index', $tenant->slug)
            ->with('success', 'Resep berhasil disimpan.');
    }

    public function destroy(string $tenant, Recipe $recipe)
    {
        abort_if($recipe->tenant_id !== TenantContext::get()->id, 404);

        $recipe->delete();

        return back()->with('success', 'Resep berhasil dihapus.');
    }
}