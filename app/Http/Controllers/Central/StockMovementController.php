<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\StockMovement;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $request->validate([
            'qty' => ['required', 'numeric', 'min:0.001'],
            'ref' => ['nullable', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($request, $material) {
            $qty = (float) $request->qty;

            $material->increment('stock', $qty);

            StockMovement::create([
                'tenant_id' => TenantContext::get()->id,
                'material_id' => $material->id,
                'type' => 'in',
                'qty' => $qty,
                'ref' => $request->ref ?? 'STOCK-IN',
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Stok masuk berhasil disimpan.');
    }

    public function stockOut(Request $request, string $tenant, Material $material)
    {
        $this->authorizeTenant($material);

        $request->validate([
            'qty' => ['required', 'numeric', 'min:0.001'],
            'ref' => ['nullable', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($request, $material) {
            $qty = (float) $request->qty;

            if ($material->stock < $qty) {
                abort(422, 'Stok tidak mencukupi.');
            }

            $material->decrement('stock', $qty);

            StockMovement::create([
                'tenant_id' => TenantContext::get()->id,
                'material_id' => $material->id,
                'type' => 'out',
                'qty' => $qty,
                'ref' => $request->ref ?? 'STOCK-OUT',
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Stok keluar berhasil disimpan.');
    }

    private function authorizeTenant(Material $material): void
    {
        abort_if($material->tenant_id !== TenantContext::get()->id, 404);
    }
}