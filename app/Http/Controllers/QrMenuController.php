<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\TenantContext;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrMenuController extends Controller
{
    public function index($tenantSlug, $table)
    {
        $tenant = TenantContext::get();

        abort_unless($tenant, 404, 'Tenant tidak ditemukan');

        $menus = MenuItem::where('tenant_id', $tenant->id)
            ->where('is_active', 1)
            ->get();

        return view('qr.index', compact('menus', 'table', 'tenant'));
    }

    public function store(Request $request, $tenantSlug)
    {
        $tenant = TenantContext::get();
        abort_unless($tenant, 404);

        $request->validate([
            'table' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|integer|min:0',
        ]);

        $subtotal = collect($request->items)->sum(function ($item) {
            return $item['qty'] * $item['price'];
        });

        $order = Order::create([
            'tenant_id' => $tenant->id,
            'outlet_id' => $request->outlet_id ?? null,
            'code' => 'QR-' . strtoupper(Str::random(6)),
            'table_code' => $request->table,
            'customer_name' => $request->customer_name ?? 'Guest',
            'status' => 'open',
            'subtotal' => $subtotal,
            'grand_total' => $subtotal,
        ]);

        foreach ($request->items as $item) {
            OrderItem::create([
                'tenant_id' => $tenant->id,
                'order_id' => $order->id,
                'menu_item_id' => $item['id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'kitchen_status' => 'new'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dibuat',
            'order_code' => $order->code
        ]);
    }

    public function tableQr($tenantSlug, $table)
    {
        $url = url("/{$tenantSlug}/qr/{$table}");

        return response(
            QrCode::format('svg')->size(250)->generate($url),
            200,
            ['Content-Type' => 'image/svg+xml']
        );
    }
}