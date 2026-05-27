<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\StockMovement;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryReportController extends Controller
{
    public function index(Request $request)
    {
        $tenant = TenantContext::get();
        $outletId = session('current_outlet_id');

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $baseQuery = StockMovement::with(['material', 'creator'])
            ->where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $stockInQty = (clone $baseQuery)->where('type', 'in')->sum('qty');
        $stockOutQty = (clone $baseQuery)->where('type', 'out')->sum('qty');

        $wasteQty = (clone $baseQuery)
            ->where('source_type', 'waste_record')
            ->sum('qty');

        $transferInQty = (clone $baseQuery)
            ->where('source_type', 'stock_transfer_in')
            ->sum('qty');

        $transferOutQty = (clone $baseQuery)
            ->where('source_type', 'stock_transfer_out')
            ->sum('qty');

        $lowStockCount = Material::where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->whereColumn('stock', '<=', 'min_stock')
            ->count();

        $topUsedMaterials = StockMovement::select('material_id', DB::raw('SUM(qty) as total_qty'))
            ->with('material')
            ->where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('material_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $movements = (clone $baseQuery)
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.reports.inventory', compact(
            'startDate',
            'endDate',
            'stockInQty',
            'stockOutQty',
            'wasteQty',
            'transferInQty',
            'transferOutQty',
            'lowStockCount',
            'topUsedMaterials',
            'movements'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $tenant = TenantContext::get();
        $outletId = session('current_outlet_id');

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $fileName = 'inventory-report-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.csv';

        $query = StockMovement::with(['material', 'creator'])
            ->where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest();

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Tanggal',
                'Material',
                'Unit',
                'Type',
                'Qty',
                'Stock Before',
                'Stock After',
                'Reference',
                'Source Type',
                'Source ID',
                'Note',
                'Created By',
            ]);

            $query->chunk(500, function ($movements) use ($handle) {
                foreach ($movements as $movement) {
                    fputcsv($handle, [
                        $movement->created_at?->format('Y-m-d H:i:s'),
                        $movement->material?->name,
                        $movement->material?->unit,
                        strtoupper($movement->type),
                        $movement->qty,
                        $movement->stock_before,
                        $movement->stock_after,
                        $movement->ref,
                        $movement->source_type,
                        $movement->source_id,
                        $movement->note,
                        $movement->creator?->name,
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
