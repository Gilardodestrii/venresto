<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\OutletTable;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrAdminController extends Controller
{
    public function index(Request $request, $tenantSlug, $outletId)
    {
        $tenant = TenantContext::get();
        abort_unless($tenant, 404, 'Tenant tidak ditemukan');

        $outlet = $this->getOutlet($tenant->id, $outletId);

        $outlets = Outlet::where('tenant_id', $tenant->id)
            ->orderBy('name')
            ->get();

        session(['current_outlet_id' => $outlet->id]);

        $tables = OutletTable::where('tenant_id', $tenant->id)
            ->where('outlet_id', $outlet->id)
            ->latest()
            ->get();

        $outletId = $outlet->id;

        return view('admin.qr.index', compact(
            'tables',
            'outlets',
            'tenant',
            'outlet',
            'outletId'
        ));
    }

    public function generate($tenantSlug, $outletId, $tableId)
    {
        $tenant = TenantContext::get();
        abort_unless($tenant, 404, 'Tenant tidak ditemukan');

        $outlet = $this->getOutlet($tenant->id, $outletId);
        $table = $this->getTable($tenant->id, $outlet->id, $tableId);

        $url = $this->qrCustomerUrl($tenant->slug, $outlet->id, $table->id);

        return response(
            QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate($url),
            200,
            ['Content-Type' => 'image/svg+xml']
        );
    }

    public function download($tenantSlug, $outletId, $tableId)
    {
        $tenant = TenantContext::get();
        abort_unless($tenant, 404, 'Tenant tidak ditemukan');

        $outlet = $this->getOutlet($tenant->id, $outletId);
        $table = $this->getTable($tenant->id, $outlet->id, $tableId);

        $url = $this->qrCustomerUrl($tenant->slug, $outlet->id, $table->id);

        $qr = QrCode::format('png')
            ->size(600)
            ->margin(2)
            ->generate($url);

        $fileName = 'qr-' .
            str($tenant->slug)->slug() . '-' .
            str($outlet->name)->slug() . '-' .
            str($table->table_code)->slug() .
            '.png';

        return response($qr)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function store(Request $request, $tenantSlug, $outletId)
    {
        $tenant = TenantContext::get();
        abort_unless($tenant, 404, 'Tenant tidak ditemukan');

        $outlet = $this->getOutlet($tenant->id, $outletId);

        $validated = $request->validate([
            'table_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('outlet_tables', 'table_code')
                    ->where('tenant_id', $tenant->id)
                    ->where('outlet_id', $outlet->id),
            ],
        ]);

        OutletTable::create([
            'tenant_id' => $tenant->id,
            'outlet_id' => $outlet->id,
            'table_code' => strtoupper(trim($validated['table_code'])),
        ]);

        return back()->with('success', 'Meja berhasil ditambahkan');
    }

    public function destroy($tenantSlug, $outletId, $tableId)
    {
        $tenant = TenantContext::get();
        abort_unless($tenant, 404, 'Tenant tidak ditemukan');

        $outlet = $this->getOutlet($tenant->id, $outletId);
        $table = $this->getTable($tenant->id, $outlet->id, $tableId);

        $table->delete();

        return back()->with('success', 'Meja berhasil dihapus');
    }

    private function getOutlet(int $tenantId, int|string $outletId): Outlet
    {
        return Outlet::where('tenant_id', $tenantId)
            ->where('id', $outletId)
            ->firstOrFail();
    }

    private function getTable(int $tenantId, int $outletId, int|string $tableId): OutletTable
    {
        return OutletTable::where('tenant_id', $tenantId)
            ->where('outlet_id', $outletId)
            ->where('id', $tableId)
            ->firstOrFail();
    }

    private function qrCustomerUrl(string $tenantSlug, int $outletId, int $tableId): string
    {
        return route('qr.menu', [
            'tenant' => $tenantSlug,
            'outlet' => $outletId,
            'table' => $tableId,
        ]);
    }
}