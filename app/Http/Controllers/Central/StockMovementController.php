<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\StockMovement;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::tenant()
            ->with(['material', 'creator'])
            ->latest()
            ->paginate(20);

        return view('admin.stock-movements.index', compact('movements'));
    }

    public function stockIn(Request $request, string $tenant, Material $material)
    {
        $this->authorizeTenant($material);

        $validated = $request->validate([
            'qty' => ['required', 'numeric', 'min:0.001'],
            'ref' => ['nullable', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($validated, $material) {
            $material = Material::where('tenant_id', TenantContext::get()->id)
                ->where('id', $material->id)
                ->lockForUpdate()
                ->firstOrFail();

            $qty = (float) $validated['qty'];
            $material->increment('stock', $qty);

            StockMovement::create([
                'tenant_id' => TenantContext::get()->id,
                'material_id' => $material->id,
                'type' => 'in',
                'qty' => $qty,
                'ref' => $validated['ref'] ?? 'STOCK-IN',
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Stok masuk berhasil disimpan.');
    }

    public function stockOut(Request $request, string $tenant, Material $material)
    {
        $this->authorizeTenant($material);

        $validated = $request->validate([
            'qty' => ['required', 'numeric', 'min:0.001'],
            'ref' => ['nullable', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($validated, $material) {
            $material = Material::where('tenant_id', TenantContext::get()->id)
                ->where('id', $material->id)
                ->lockForUpdate()
                ->firstOrFail();

            $qty = (float) $validated['qty'];

            if ((float) $material->stock < $qty) {
                throw ValidationException::withMessages([
                    'qty' => 'Stok tidak mencukupi.',
                ]);
            }

            $material->decrement('stock', $qty);

            StockMovement::create([
                'tenant_id' => TenantContext::get()->id,
                'material_id' => $material->id,
                'type' => 'out',
                'qty' => $qty,
                'ref' => $validated['ref'] ?? 'STOCK-OUT',
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Stok keluar berhasil disimpan.');
    }

    public function adjustment(Request $request, string $tenant, Material $material)
    {
        $this->authorizeTenant($material);

        $validated = $request->validate([
            'stock' => ['required', 'numeric', 'min:0'],
            'ref' => ['nullable', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($validated, $material) {
            $material = Material::where('tenant_id', TenantContext::get()->id)
                ->where('id', $material->id)
                ->lockForUpdate()
                ->firstOrFail();

            $currentStock = (float) $material->stock;
            $newStock = (float) $validated['stock'];
            $diff = $newStock - $currentStock;

            if (abs($diff) < 0.000001) {
                return;
            }

            $material->forceFill([
                'stock' => $newStock,
            ])->save();

            StockMovement::create([
                'tenant_id' => TenantContext::get()->id,
                'material_id' => $material->id,
                'type' => $diff > 0 ? 'in' : 'out',
                'qty' => abs($diff),
                'ref' => $validated['ref'] ?? 'ADJUSTMENT',
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Stock adjustment berhasil disimpan.');
    }

    private function authorizeTenant(Material $material): void
    {
        abort_if($material->tenant_id !== TenantContext::get()->id, 404);
    }
}
