<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\StockMovement;
use App\Models\WasteRecord;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WasteRecordController extends Controller
{
    public function index(Request $request)
    {
        $tenant = TenantContext::get();
        $outletId = session('current_outlet_id');

        $wastes = WasteRecord::with(['items.material', 'outlet'])
            ->where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->when($request->reason, fn ($query) => $query->where('reason', $request->reason))
            ->latest()
            ->paginate(15);

        return view('admin.waste-records.index', compact('wastes'));
    }

    public function create()
    {
        $tenant = TenantContext::get();
        $outletId = session('current_outlet_id');

        $materials = Material::where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->orderBy('name')
            ->get();

        return view('admin.waste-records.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $tenant = TenantContext::get();
        $outletId = session('current_outlet_id');

        $validated = $request->validate([
            'reason' => ['required', 'in:expired,damaged,spillage,overcooked,staff_meal,other'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'integer'],
            'items.*.qty' => ['required', 'numeric', 'min:0.001'],
        ]);

        $waste = DB::transaction(function () use ($validated, $tenant, $outletId) {
            $waste = WasteRecord::create([
                'tenant_id' => $tenant->id,
                'outlet_id' => $outletId,
                'code' => 'WST-' . strtoupper(Str::random(8)),
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            $usedMaterials = [];

            foreach ($validated['items'] as $item) {
                if (in_array((int) $item['material_id'], $usedMaterials, true)) {
                    throw ValidationException::withMessages([
                        'items' => 'Bahan tidak boleh duplikat dalam satu waste record.',
                    ]);
                }

                $usedMaterials[] = (int) $item['material_id'];

                $material = Material::where('tenant_id', $tenant->id)
                    ->where('outlet_id', $outletId)
                    ->where('id', $item['material_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $qty = (float) $item['qty'];

                if ((float) $material->stock < $qty) {
                    throw ValidationException::withMessages([
                        'stock' => "Stock {$material->name} tidak mencukupi.",
                    ]);
                }

                $before = (float) $material->stock;
                $after = $before - $qty;

                $material->decrement('stock', $qty);

                $waste->items()->create([
                    'material_id' => $material->id,
                    'qty' => $qty,
                ]);

                StockMovement::create([
                    'tenant_id' => $tenant->id,
                    'outlet_id' => $outletId,
                    'material_id' => $material->id,
                    'type' => 'out',
                    'qty' => $qty,
                    'stock_before' => $before,
                    'stock_after' => $after,
                    'ref' => 'WASTE-' . $waste->id,
                    'note' => 'Waste stock: ' . $validated['reason'],
                    'source_type' => 'waste_record',
                    'source_id' => $waste->id,
                    'created_by' => Auth::id(),
                ]);
            }

            return $waste;
        });

        return redirect()
            ->route('tenant.admin.waste-records.show', [TenantContext::get()->slug, $waste->id])
            ->with('success', 'Waste record berhasil disimpan dan stock berkurang.');
    }

    public function show(string $tenant, WasteRecord $wasteRecord)
    {
        $this->authorizeWaste($wasteRecord);

        $wasteRecord->load(['items.material', 'outlet']);

        return view('admin.waste-records.show', compact('wasteRecord'));
    }

    private function authorizeWaste(WasteRecord $wasteRecord): void
    {
        abort_if(
            $wasteRecord->tenant_id !== TenantContext::get()->id ||
            $wasteRecord->outlet_id !== session('current_outlet_id'),
            404
        );
    }
}
