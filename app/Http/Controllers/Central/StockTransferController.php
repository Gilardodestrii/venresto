<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Outlet;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StockTransferController extends Controller
{
    public function index()
    {
        $tenant = TenantContext::get();

        $transfers = StockTransfer::with(['fromOutlet', 'toOutlet', 'items.material'])
            ->where('tenant_id', $tenant->id)
            ->latest()
            ->paginate(15);

        return view('admin.stock-transfers.index', compact('transfers'));
    }

    public function create()
    {
        $tenant = TenantContext::get();
        $currentOutletId = session('current_outlet_id');

        $outlets = Outlet::where('tenant_id', $tenant->id)
            ->where('id', '!=', $currentOutletId)
            ->orderBy('name')
            ->get();

        $materials = Material::where('tenant_id', $tenant->id)
            ->where('outlet_id', $currentOutletId)
            ->orderBy('name')
            ->get();

        return view('admin.stock-transfers.create', compact('outlets', 'materials'));
    }

    public function store(Request $request)
    {
        $tenant = TenantContext::get();
        $fromOutletId = session('current_outlet_id');

        $validated = $request->validate([
            'to_outlet_id' => ['required', 'integer'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'integer'],
            'items.*.qty' => ['required', 'numeric', 'min:0.001'],
        ]);

        if ((int) $validated['to_outlet_id'] === (int) $fromOutletId) {
            throw ValidationException::withMessages([
                'to_outlet_id' => 'Outlet tujuan tidak boleh sama dengan outlet asal.',
            ]);
        }

        Outlet::where('tenant_id', $tenant->id)
            ->where('id', $validated['to_outlet_id'])
            ->firstOrFail();

        $transfer = DB::transaction(function () use ($validated, $tenant, $fromOutletId) {
            $transfer = StockTransfer::create([
                'tenant_id' => $tenant->id,
                'from_outlet_id' => $fromOutletId,
                'to_outlet_id' => $validated['to_outlet_id'],
                'code' => 'TRF-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            $usedMaterials = [];

            foreach ($validated['items'] as $item) {
                if (in_array((int) $item['material_id'], $usedMaterials, true)) {
                    throw ValidationException::withMessages([
                        'items' => 'Bahan tidak boleh duplikat dalam satu transfer.',
                    ]);
                }

                $usedMaterials[] = (int) $item['material_id'];

                Material::where('tenant_id', $tenant->id)
                    ->where('outlet_id', $fromOutletId)
                    ->where('id', $item['material_id'])
                    ->firstOrFail();

                $transfer->items()->create([
                    'material_id' => $item['material_id'],
                    'qty' => $item['qty'],
                ]);
            }

            return $transfer;
        });

        return redirect()
            ->route('tenant.admin.stock-transfers.show', [TenantContext::get()->slug, $transfer->id])
            ->with('success', 'Transfer stock berhasil dibuat.');
    }

    public function show(string $tenant, StockTransfer $stockTransfer)
    {
        $this->authorizeTransfer($stockTransfer);

        $stockTransfer->load(['fromOutlet', 'toOutlet', 'items.material']);

        return view('admin.stock-transfers.show', compact('stockTransfer'));
    }

    public function complete(string $tenant, StockTransfer $stockTransfer)
    {
        $this->authorizeTransfer($stockTransfer);

        DB::transaction(function () use ($stockTransfer) {
            $stockTransfer = StockTransfer::where('id', $stockTransfer->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($stockTransfer->status !== 'pending') {
                throw ValidationException::withMessages([
                    'status' => 'Transfer sudah diproses.',
                ]);
            }

            $stockTransfer->load('items.material');

            foreach ($stockTransfer->items as $item) {
                $fromMaterial = Material::where('tenant_id', $stockTransfer->tenant_id)
                    ->where('outlet_id', $stockTransfer->from_outlet_id)
                    ->where('id', $item->material_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $qty = (float) $item->qty;

                if ((float) $fromMaterial->stock < $qty) {
                    throw ValidationException::withMessages([
                        'stock' => "Stock {$fromMaterial->name} tidak mencukupi.",
                    ]);
                }

                $fromBefore = (float) $fromMaterial->stock;
                $fromAfter = $fromBefore - $qty;
                $fromMaterial->decrement('stock', $qty);

                $toMaterial = Material::firstOrCreate(
                    [
                        'tenant_id' => $stockTransfer->tenant_id,
                        'outlet_id' => $stockTransfer->to_outlet_id,
                        'name' => $fromMaterial->name,
                    ],
                    [
                        'unit' => $fromMaterial->unit,
                        'stock' => 0,
                        'min_stock' => $fromMaterial->min_stock,
                    ]
                );

                $toMaterial = Material::where('id', $toMaterial->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $toBefore = (float) $toMaterial->stock;
                $toAfter = $toBefore + $qty;
                $toMaterial->increment('stock', $qty);

                StockMovement::create([
                    'tenant_id' => $stockTransfer->tenant_id,
                    'outlet_id' => $stockTransfer->from_outlet_id,
                    'material_id' => $fromMaterial->id,
                    'type' => 'out',
                    'qty' => $qty,
                    'stock_before' => $fromBefore,
                    'stock_after' => $fromAfter,
                    'ref' => 'TRANSFER-OUT-' . $stockTransfer->id,
                    'note' => 'Transfer stock keluar ke outlet tujuan',
                    'source_type' => 'stock_transfer_out',
                    'source_id' => $stockTransfer->id,
                    'created_by' => Auth::id(),
                ]);

                StockMovement::create([
                    'tenant_id' => $stockTransfer->tenant_id,
                    'outlet_id' => $stockTransfer->to_outlet_id,
                    'material_id' => $toMaterial->id,
                    'type' => 'in',
                    'qty' => $qty,
                    'stock_before' => $toBefore,
                    'stock_after' => $toAfter,
                    'ref' => 'TRANSFER-IN-' . $stockTransfer->id,
                    'note' => 'Transfer stock masuk dari outlet asal',
                    'source_type' => 'stock_transfer_in',
                    'source_id' => $stockTransfer->id,
                    'created_by' => Auth::id(),
                ]);
            }

            $stockTransfer->update([
                'status' => 'completed',
                'completed_by' => Auth::id(),
                'completed_at' => now(),
            ]);
        });

        return back()->with('success', 'Transfer stock berhasil diselesaikan.');
    }

    public function cancel(string $tenant, StockTransfer $stockTransfer)
    {
        $this->authorizeTransfer($stockTransfer);

        if ($stockTransfer->status !== 'pending') {
            return back()->with('error', 'Transfer tidak bisa dibatalkan karena sudah diproses.');
        }

        $stockTransfer->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Transfer stock berhasil dibatalkan.');
    }

    private function authorizeTransfer(StockTransfer $transfer): void
    {
        abort_if($transfer->tenant_id !== TenantContext::get()->id, 404);
    }
}
