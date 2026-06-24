<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Outlet;
use App\Services\TenantContext;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $tenant = TenantContext::get();

        $outlets = Outlet::where('tenant_id', $tenant->id)->orderBy('name')->get();

        $query = Material::where('tenant_id', $tenant->id)->latest();

        if ($request->filled('outlet_filter')) {
            $query->where('outlet_id', $request->outlet_filter);
        }

        $materials = $query->paginate(15)->withQueryString();

        return view('admin.materials.index', compact('materials', 'outlets'));
    }

    public function create()
    {
        return view('admin.materials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'unit' => ['required', 'string', 'max:30'],
            'stock' => ['nullable', 'numeric', 'min:0'],
            'min_stock' => ['nullable', 'numeric', 'min:0'],
            'cost_per_unit' => ['nullable', 'numeric', 'min:0'],
        ]);

        Material::create([
            'tenant_id' => TenantContext::get()->id,
            'outlet_id' => session('current_outlet_id'),
            'name' => $request->name,
            'unit' => $request->unit,
            'stock' => $request->stock ?? 0,
            'min_stock' => $request->min_stock ?? 0,
            'cost_per_unit' => $request->cost_per_unit ?? 0,
        ]);

        return redirect()
            ->route('tenant.admin.materials.index', TenantContext::get()->slug)
            ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(string $tenant, Material $material)
    {
        $this->authorizeTenant($material);

        return view('admin.materials.edit', compact('material'));
    }

    public function update(Request $request, string $tenant, Material $material)
    {
        $this->authorizeTenant($material);

        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'unit' => ['required', 'string', 'max:30'],
            'min_stock' => ['nullable', 'numeric', 'min:0'],
            'cost_per_unit' => ['nullable', 'numeric', 'min:0'],
        ]);

        $material->update([
            'name' => $request->name,
            'unit' => $request->unit,
            'min_stock' => $request->min_stock ?? 0,
            'cost_per_unit' => $request->cost_per_unit ?? 0,
        ]);

        return redirect()
            ->route('tenant.admin.materials.index', TenantContext::get()->slug)
            ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(string $tenant, Material $material)
    {
        $this->authorizeTenant($material);

        $material->delete();

        return back()->with('success', 'Bahan baku berhasil dihapus.');
    }

    private function authorizeTenant(Material $material): void
    {
        abort_if(
            $material->tenant_id !== TenantContext::get()->id ||
            $material->outlet_id !== session('current_outlet_id'),
            404
        );
    }
}
