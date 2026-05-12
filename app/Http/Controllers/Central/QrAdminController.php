<?php

namespace App\Http\Controllers\Central;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TenantContext;
use App\Models\Outlet;
use App\Models\OutletTable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrAdminController extends Controller
{
public function index(Request $request)
{
    $tenant = TenantContext::get();
    abort_unless($tenant, 404);

    $outlets = Outlet::where('tenant_id', $tenant->id)->get();

    $outletId = $request->get('outlet_id')
        ?? session('current_outlet_id')
        ?? optional($outlets->first())->id;

    $tables = OutletTable::where('tenant_id', $tenant->id)
        ->when($outletId, fn($q) => $q->where('outlet_id', $outletId))
        ->latest()
        ->get();

    return view('admin.qr.index', compact(
        'tables', 'outlets', 'tenant', 'outletId'
    ));
}

public function generate($tableId)
{
    $tenant = TenantContext::get();
    abort_unless($tenant, 404);

    $table = OutletTable::where('tenant_id', $tenant->id)
        ->findOrFail($tableId);

    $url = url("/{$tenant->slug}/qr/{$table->table_code}");

    return QrCode::format('svg')
        ->size(300)
        ->generate($url);
}

    public function download($tableId)
    {
        $tenant = TenantContext::get();
        abort_unless($tenant, 404);

        $table = OutletTable::where('tenant_id', $tenant->id)
            ->findOrFail($tableId);

        $url = url("/{$tenant->slug}/qr/{$table->code}");

        $qr = QrCode::format('png')
            ->size(400)
            ->generate($url);

        return response($qr)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-'.$table->code.'.png"');
    }

    public function store(Request $request)
    {
        $tenant = TenantContext::get();
        abort_unless($tenant, 404);

        $request->validate([
            'outlet_id'  => 'required|exists:outlets,id',
            'table_code' => 'required|string|max:50',
        ]);

        OutletTable::create([
            'tenant_id'  => $tenant->id,
            'outlet_id'  => $request->outlet_id,
            'table_code' => strtoupper($request->table_code),
        ]);

        return back()->with('success', 'Meja berhasil ditambahkan');
    }

    public function destroy($tableId)
    {
        $tenant = TenantContext::get();
        abort_unless($tenant, 404);

        $table = OutletTable::where('tenant_id', $tenant->id)
            ->findOrFail($tableId);

        $table->delete();

        return back()->with('success', 'Meja berhasil dihapus');
    }

    
}