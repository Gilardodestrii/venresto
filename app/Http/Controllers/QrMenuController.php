<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\OutletTable;
use App\Services\TenantContext;
use App\Models\TenantSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrMenuController extends Controller
{
public function index($tenantSlug, Outlet $outlet, OutletTable $table)
{
    $tenant = TenantContext::get();

    abort_unless($tenant, 404, 'Tenant tidak ditemukan');

    abort_unless(
        $outlet->tenant_id === $tenant->id,
        404,
        'Outlet tidak ditemukan'
    );

    abort_unless(
        $table->tenant_id === $tenant->id && $table->outlet_id === $outlet->id,
        404,
        'Meja tidak ditemukan'
    );

    $menus = MenuItem::with('category')
        ->where('tenant_id', $tenant->id)
        ->where('is_active', 1)
        ->orderBy('name')
        ->get();

    $categories = MenuCategory::where('tenant_id', $tenant->id)
        ->orderBy('seq')
        ->get();

    $settings = TenantSetting::where('tenant_id', $tenant->id)->first();

    $payments = json_decode($settings->payments_json ?? '{}', true) ?: [];
    $paymentOptions = [];

    if (!empty($payments['cash_enabled'])) {
        $paymentOptions['cash'] = 'Cash';
    }

    if (!empty($payments['qris_enabled'])) {
        $qrisMode = $payments['qris_mode'] ?? null;

        if ($qrisMode === 'snap') {
            $paymentOptions['qris_snap'] = 'QRIS Snap';
        }

        if ($qrisMode === 'static') {
            $paymentOptions['qris_static'] = 'QRIS Static';
        }
    }

    return view('qr.index', compact(
        'tenant',
        'outlet',
        'table',
        'menus',
        'categories',
        'paymentOptions',
        'settings'
    ));
}

    public function store(Request $request, $tenantSlug, Outlet $outlet)
    {
        $tenant = TenantContext::get();
        abort_unless($tenant, 404, 'Tenant tidak ditemukan');

        abort_unless($outlet->tenant_id === $tenant->id, 404, 'Outlet tidak ditemukan');

        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:100'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'table_id' => ['required', 'integer'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:999'],
            'items.*.note' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'string'],
            'customer_note' => ['nullable', 'string', 'max:500'],
        ]);

        return DB::transaction(function () use ($validated, $tenant, $outlet) {
            $table = OutletTable::where('tenant_id', $tenant->id)
                ->where('outlet_id', $outlet->id)
                ->where('id', $validated['table_id'])
                ->firstOrFail();

            $menuIds = collect($validated['items'])->pluck('id')->unique()->values();

            $menus = MenuItem::where('tenant_id', $tenant->id)
                ->where('is_active', 1)
                ->whereIn('id', $menuIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            abort_if($menus->count() !== $menuIds->count(), 422, 'Menu tidak valid');

            $settings = TenantSetting::where('tenant_id', $tenant->id)->first();

            $subtotal = 0;
            $items = [];

            foreach ($validated['items'] as $cartItem) {
                $menu = $menus->get($cartItem['id']);

                $qty = (int) $cartItem['qty'];
                $price = (int) $menu->price;
                $lineTotal = $qty * $price;

                $subtotal += $lineTotal;

                $items[] = [
                    'tenant_id' => $tenant->id,
                    'menu_item_id' => $menu->id,
                    'qty' => $qty,
                    'price' => $price,
                    'note' => $cartItem['note'] ?? null,
                    'kitchen_status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $discount = 0;
            $afterDiscount = $subtotal;

            $tax = ($settings && $settings->tax_enabled && !$settings->tax_inclusive)
                ? round($afterDiscount * ((float) $settings->tax_rate / 100))
                : 0;

            $service = ($settings && $settings->service_enabled && !$settings->service_inclusive)
                ? round($afterDiscount * ((float) $settings->service_rate / 100))
                : 0;

            $grandTotal = $afterDiscount + $tax + $service;

            $order = Order::create([
                'tenant_id' => $tenant->id,
                'outlet_id' => $outlet->id,
                'code' => $this->generateQrOrderCode(),
                'table_code' => $table->table_code,
                'customer_name' => !empty($validated['customer_name'])
                    ? trim($validated['customer_name'])
                    : 'Guest',

                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_note' => $validated['customer_note'] ?? null,
                'status' => 'open',
                'payment_status' => 'unpaid',
                'payment_method' => $validated['payment_method'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'service' => $service,
                'grand_total' => $grandTotal,
            ]);

            foreach ($items as &$item) {
                $item['order_id'] = $order->id;
            }

            OrderItem::insert($items);

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat',
                'order_code' => $order->code,
                'data' => [
                    'id' => $order->id,
                    'code' => $order->code,
                    'outlet_id' => $order->outlet_id,
                    'table_code' => $order->table_code,
                    'grand_total' => $order->grand_total,
                ],
            ]);
        });
    }

    private function generateQrOrderCode(): string
    {
        do {
            $code = 'QR-' . now()->format('ymdHis') . '-' . strtoupper(\Illuminate\Support\Str::random(4));
        } while (\App\Models\Order::where('code', $code)->exists());

        return $code;
    }

    public function tableQr($tenantSlug, Outlet $outlet, OutletTable $table)
    {
        $tenant = TenantContext::get();

        abort_unless($tenant, 404);
        abort_unless($outlet->tenant_id === $tenant->id, 404);
        abort_unless($table->tenant_id === $tenant->id && $table->outlet_id === $outlet->id, 404);

        $url = route('qr.menu', [
            'tenant' => $tenant->slug,
            'outlet' => $outlet->id,
            'table' => $table->id,
        ]);

        return response(
            QrCode::format('svg')->size(250)->generate($url),
            200,
            ['Content-Type' => 'image/svg+xml']
        );
    }
}