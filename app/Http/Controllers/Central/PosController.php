<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Models\OutletTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TenantSetting;
use App\Services\TenantContext;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\CashierSession;
use Illuminate\Validation\ValidationException;
use App\Services\InventoryService;

class PosController extends Controller
{
    public function index($tenant)
    {
        $tenantModel = TenantContext::get();

        $categories = MenuCategory::query()
            ->where('tenant_id', $tenantModel->id)
            ->orderBy('seq')
            ->get();

        $menuItems = MenuItem::query()
            ->where('tenant_id', $tenantModel->id)
            ->where('is_active', 1)
            ->with('category')
            ->latest()
            ->get();

        $tables = OutletTable::query()
            ->where('tenant_id', $tenantModel->id)
            ->where('outlet_id', session('current_outlet_id'))
            ->orderBy('table_code')
            ->get();

        $settings = TenantSetting::firstOrCreate(
            ['tenant_id' => $tenantModel->id],
            [
                'tax_enabled' => true,
                'tax_rate' => 0.10,
                'tax_inclusive' => false,
                'service_enabled' => true,
                'service_rate' => 0.05,
                'service_inclusive' => false,
                'kitchen_ticket_on_open_for_cash' => true,
                'stock_deduct_on' => 'paid',
                'payments_json' => [
                    'qris_mode' => 'snap',
                    'qris_snap' => [
                        'client_key' => null,
                        'server_key' => null,
                        'expiry_minutes' => 15,
                    ],
                    'qris_static' => [
                        'qr_payload' => null,
                        'qr_image_url' => null,
                    ],
                    'cash_enabled' => true,
                    'qris_enabled' => true,
                ],
            ]
        );

        $payments = $settings->payments_json ?? [];

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


        return view('admin.pos.index', compact(
            'categories',
            'menuItems',
            'tables',
            'tenant',
            'settings',
            'paymentOptions'
        ));
    }

    public function store(Request $request, $tenant, InventoryService $inventoryService)
    {
        $tenantModel = TenantContext::get();

        if (!$tenantModel) {
            return back()->with('error', 'Tenant tidak ditemukan.');
        }

        $outletId = session('current_outlet_id');

        if (!$outletId) {
            return back()->with('error', 'Outlet aktif belum dipilih.');
        }

        $settings = TenantSetting::firstOrCreate(
            ['tenant_id' => $tenantModel->id],
            [
                'tax_enabled' => true,
                'tax_rate' => 0.11,
                'tax_inclusive' => false,
                'service_enabled' => true,
                'service_rate' => 0.05,
                'service_inclusive' => false,
                'kitchen_ticket_on_open_for_cash' => true,
                'stock_deduct_on' => 'paid',
                'payments_json' => [
                    'cash' => true,
                    'qris' => true,
                    'qris_snap' => false,
                    'qris_static' => true,
                ],
            ]
        );

      

        $enabledPayments = collect($settings->payments_json ?? [])
            ->filter(fn ($enabled) => (bool) $enabled)
            ->keys()
            ->implode(',');

        if (!$enabledPayments) {
            return back()->withInput()->with('error', 'Belum ada metode pembayaran aktif di Tenant Settings.');
        }


        $validated = $request->validate([
            'table_code'              => ['required', 'string'],
            'customer_name'           => ['nullable', 'string', 'max:255'],
            'customer_phone'          => ['nullable', 'string', 'max:50'],
            'customer_note'           => ['nullable', 'string'],
            'payment_method'          => ['required', 'string', 'in:' . $enabledPayments],
            'action'                  => ['required', 'string', 'in:hold,paid'],
            'discount'                => ['nullable', 'numeric', 'min:0'],
            'paid_amount'             => ['nullable', 'numeric', 'min:0'],
            'items'                   => ['required', 'array', 'min:1'],
            'items.*.menu_item_id'    => ['required', 'integer'],
            'items.*.qty'             => ['required', 'numeric', 'min:1'],
            'items.*.price'           => ['required', 'numeric', 'min:0'],
            'items.*.note'            => ['nullable', 'string'],
        ]);

        $createdOrder = null;

        try {
            DB::transaction(function () use ($validated, $tenantModel, $outletId, $inventoryService, $settings, &$createdOrder) {

                $subtotal = collect($validated['items'])->sum(function ($item) {
                    return ((float) $item['qty']) * ((float) $item['price']);
                });

                $discount = (float) ($validated['discount'] ?? 0);
                $baseAmount = max(0, $subtotal - $discount);

                $service = 0;
                if ($settings->service_enabled && !$settings->service_inclusive) {
                    $service = round($baseAmount * (float) $settings->service_rate);
                }

                $taxBase = $baseAmount + $service;
                $tax = 0;
                if ($settings->tax_enabled && !$settings->tax_inclusive) {
                    $tax = round($taxBase * (float) $settings->tax_rate);
                }

                $grandTotal = max(0, $baseAmount + $tax + $service);

                $isPaid = $validated['action'] === 'paid';

                $order = Order::create([
                    'tenant_id'       => $tenantModel->id,
                    'outlet_id'       => $outletId,
                    'code'            => 'ORD-' . strtoupper(Str::random(8)),
                    'table_code'      => $validated['table_code'],
                    'customer_name'   => $validated['customer_name'] ?? null,
                    'customer_phone'  => $validated['customer_phone'] ?? null,
                    'customer_note'   => $validated['customer_note'] ?? null,
                    'status'          => $isPaid ? 'paid' : 'pending_payment',
                    'paid_at'         => $isPaid ? now() : null,
                    'subtotal'        => $subtotal,
                    'discount'        => $discount,
                    'tax'             => $tax,
                    'service'         => $service,
                    'grand_total'     => $grandTotal,
                    'payment_method'  => $validated['payment_method'],
                    'cashier_id'      => Auth::id(),
                ]);

                $createdOrder = $order;

                foreach ($validated['items'] as $item) {
                    OrderItem::create([
                        'tenant_id'      => $tenantModel->id,
                        'order_id'       => $order->id,
                        'menu_item_id'   => $item['menu_item_id'],
                        'qty'            => $item['qty'],
                        'price'          => $item['price'],
                        'note'           => $item['note'] ?? null,
                        'kitchen_status' => 'new',
                    ]);
                }

                $shouldDeductStock = $settings->stock_deduct_on === 'open';

                if ($isPaid) {
                    $activeSession = CashierSession::where('tenant_id', $tenantModel->id)
                        ->where('outlet_id', $outletId)
                        ->where('cashier_id', Auth::id())
                        ->where('status', 'open')
                        ->lockForUpdate()
                        ->latest('id')
                        ->first();

                    if (!$activeSession) {
                        throw ValidationException::withMessages([
                            'cashier_session' => 'Shift kasir belum dibuka.',
                        ]);
                    }

                    $paidAmount = isset($validated['paid_amount']) && $validated['paid_amount'] > 0
                        ? (float) $validated['paid_amount']
                        : $grandTotal;

                    if ($paidAmount < $grandTotal) {
                        throw ValidationException::withMessages([
                            'paid_amount' => 'Nominal pembayaran kurang dari total order.',
                        ]);
                    }

                    Payment::create([
                        'tenant_id'           => $tenantModel->id,
                        'outlet_id'           => $outletId,
                        'order_id'            => $order->id,
                        'cashier_session_id'  => $activeSession->id,
                        'cashier_id'          => Auth::id(),
                        'method'              => $validated['payment_method'],
                        'amount'              => $grandTotal,
                        'paid_amount'         => $paidAmount,
                        'change_amount'       => max(0, $paidAmount - $grandTotal),
                        'reference'           => 'PAY-' . strtoupper(Str::random(12)),
                        'status'              => 'paid',
                        'paid_at'             => now(),
                    ]);

                    $shouldDeductStock = $shouldDeductStock || $settings->stock_deduct_on === 'paid';
                }

                if ($shouldDeductStock) {
                    $inventoryService->deductFromOrder($order);
                }
            });

            $redirect = redirect()
                ->back()
                ->with('success', 'Order berhasil dibuat');

            if (($validated['action'] ?? null) === 'paid' && $createdOrder) {
                $redirect->with('receipt_url', route('tenant.admin.orders.receipt', [$tenant, $createdOrder->id]));
            }

            return $redirect;

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
