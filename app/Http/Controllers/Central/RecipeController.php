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
        $tenant = TenantContext::get();

        // Recipe table is FLAT: 1 row per (menu_item_id, material_id).
        // The view expects 1 row per menu_item, with sub-ingredients collection.
        // So we group by item_id and build lightweight "RecipeGroup" DTOs.
        $recipeRows = Recipe::tenant()
            ->with(['menuItem', 'material'])
            ->orderBy('item_id')
            ->orderBy('id')
            ->get();

        $grouped = $recipeRows->groupBy('item_id')->map(function ($rows, $itemId) {
            $first = $rows->first();
            $ingredients = $rows->map(function ($row) {
                return (object) [
                    'id'           => $row->id,
                    'material_id'  => $row->material_id,
                    'material'     => $row->material,
                    'qty'          => $row->qty,
                    'notes'        => $row->notes ?? null,
                ];
            });

            $totalHpp = $ingredients->sum(function ($ing) {
                return (float) $ing->qty * (float) ($ing->material?->cost_per_unit ?? 0);
            });

            return (object) [
                'id'          => $first->id,
                'menu_item_id'=> $first->item_id,
                'menuItem'    => $first->menuItem,
                'ingredients' => $ingredients,
                'total_hpp'   => $totalHpp,
            ];
        })->values();

        // Manual paginator so the view's $recipes->links() works.
        $perPage = 20;
        $page    = request()->integer('page', 1);
        $slice   = $grouped->slice(($page - 1) * $perPage, $perPage)->values();
        $recipes = new \Illuminate\Pagination\LengthAwarePaginator(
            $slice,
            $grouped->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

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

        // Forms send `menu_item_id` and `ingredients[]` — match the views.
        $validated = $request->validate([
            'menu_item_id'             => ['required', 'exists:menu_items,id'],
            'ingredients'              => ['required', 'array', 'min:1'],
            'ingredients.*.material_id' => ['required', 'exists:materials,id'],
            'ingredients.*.qty'        => ['required', 'numeric', 'min:0.001'],
        ]);

        $menuItem = MenuItem::where('tenant_id', $tenant->id)
            ->where('id', $validated['menu_item_id'])
            ->firstOrFail();

        DB::transaction(function () use ($validated, $tenant, $menuItem) {
            foreach ($validated['ingredients'] as $ingredientData) {
                $material = Material::tenant()
                    ->currentOutlet()
                    ->where('id', $ingredientData['material_id'])
                    ->firstOrFail();

                Recipe::updateOrCreate(
                    [
                        'tenant_id'   => $tenant->id,
                        'outlet_id'   => session('current_outlet_id'),
                        'item_id'     => $menuItem->id,
                        'material_id' => $material->id,
                    ],
                    [
                        'qty'   => $ingredientData['qty'],
                        'notes' => $ingredientData['notes'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('tenant.admin.recipes.index', $tenant->slug)
            ->with('success', 'Recipe berhasil disimpan.');
    }

    public function edit(string $tenant, Recipe $recipe)
    {
        abort_if($recipe->tenant_id !== TenantContext::get()->id, 404);

        // The route param is the FIRST recipe row id. Load ALL rows for the same
        // menu_item so the view's @foreach($recipe->ingredients ...) works.
        $ingredientRows = Recipe::tenant()
            ->with('material')
            ->where('item_id', $recipe->item_id)
            ->orderBy('id')
            ->get();

        $ingredients = $ingredientRows->map(function ($row) {
            return (object) [
                'id'          => $row->id,
                'material_id' => $row->material_id,
                'material'    => $row->material,
                'qty'         => $row->qty,
                'notes'       => $row->notes ?? null,
            ];
        });

        $recipeGroup = (object) [
            'id'           => $recipe->id,
            'menu_item_id' => $recipe->item_id,
            'menuItem'     => $recipe->menuItem()->first(),
            'ingredients'  => $ingredients,
        ];

        $menuItems = MenuItem::where('tenant_id', TenantContext::get()->id)
            ->orderBy('name')
            ->get();

        $materials = Material::tenant()
            ->currentOutlet()
            ->orderBy('name')
            ->get();

        return view('admin.recipes.edit', [
            'recipe'    => $recipeGroup,
            'menuItems' => $menuItems,
            'materials' => $materials,
        ]);
    }

    public function update(Request $request, string $tenant, Recipe $recipe)
    {
        abort_if($recipe->tenant_id !== TenantContext::get()->id, 404);

        $validated = $request->validate([
            'menu_item_id'              => ['required', 'exists:menu_items,id'],
            'ingredients'               => ['required', 'array', 'min:1'],
            'ingredients.*.material_id' => ['required', 'exists:materials,id'],
            'ingredients.*.qty'         => ['required', 'numeric', 'min:0.001'],
        ]);

        DB::transaction(function () use ($validated, $recipe) {
            // Simplest correct semantics: delete old ingredients for this menu item,
            // re-create from submitted list. Avoids stale rows when user removes an ingredient.
            Recipe::where('tenant_id', $recipe->tenant_id)
                ->where('outlet_id', $recipe->outlet_id ?? session('current_outlet_id'))
                ->where('item_id', $validated['menu_item_id'])
                ->delete();

            foreach ($validated['ingredients'] as $ingredientData) {
                Recipe::create([
                    'tenant_id'   => $recipe->tenant_id,
                    'outlet_id'   => $recipe->outlet_id ?? session('current_outlet_id'),
                    'item_id'     => $validated['menu_item_id'],
                    'material_id' => $ingredientData['material_id'],
                    'qty'         => $ingredientData['qty'],
                    'notes'       => $ingredientData['notes'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('tenant.admin.recipes.index', $tenant)
            ->with('success', 'Resep berhasil diperbarui.');
    }

    public function destroy(string $tenant, Recipe $recipe)
    {
        abort_if($recipe->tenant_id !== TenantContext::get()->id, 404);

        $recipe->delete();

        return back()->with('success', 'Resep berhasil dihapus.');
    }
}
