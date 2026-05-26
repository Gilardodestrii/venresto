<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Models\OutletTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\TenantContext;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\CashierSession;
use Illuminate\Validation\ValidationException;


class PosController extends Controller
{
    //

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

        return view('admin.pos.index', compact(
            'categories',
            'menuItems',
            'tables',
            'tenant'
        ));
    }

public function store(Request $request, $tenant)
{
    $tenantModel = TenantContext::get();

    if (!$tenantModel) {
        return back()->with('error', 'Tenant tidak ditemukan.');
    }

    $outletId = session('current_outlet_id');

    if (!$outletId) {
        return back()->with('error', 'Outlet aktif belum dipilih.');
    }

    $validated = $request->validate([
        'table_code'              => ['required', 'string'],
        'customer_name'           => ['nullable', 'string', 'max:255'],
        'customer_phone'          => ['nullable', 'string', 'max:50'],
        'customer_note'           => ['nullable', 'string'],
        'payment_method'          => ['required', 'string', 'in:cash,qris,debit,transfer'],
        'action'                  => ['required', 'string', 'in:hold,paid'],

        'discount'                => ['nullable', 'numeric', 'min:0'],
        'tax'                     => ['nullable', 'numeric', 'min:0'],
        'service'                 => ['nullable', 'numeric', 'min:0'],
        'paid_amount'             => ['nullable', 'numeric', 'min:0'],

        'items'                   => ['required', 'array', 'min:1'],
        'items.*.menu_item_id'    => ['required', 'integer'],
        'items.*.qty'             => ['required', 'numeric', 'min:1'],
        'items.*.price'           => ['required', 'numeric', 'min:0'],
        'items.*.note'            => ['nullable', 'string'],
    ]);

    try {
        DB::transaction(function () use ($validated, $request, $tenantModel, $outletId) {

            $subtotal = collect($validated['items'])->sum(function ($item) {
                return ((float) $item['qty']) * ((float) $item['price']);
            });

            $discount = (float) ($validated['discount'] ?? 0);
            $tax      = (float) ($validated['tax'] ?? 0);
            $service  = (float) ($validated['service'] ?? 0);

            $grandTotal = max(0, ($subtotal - $discount) + $tax + $service);

            $isPaid = $validated['action'] === 'paid';

            $order = Order::create([
                'tenant_id'       => $tenantModel->id,
                'outlet_id'       => $outletId,
                'code'            => 'ORD-' . strtoupper(Str::random(8)),
                'table_code'      => $validated['table_code'],
                'customer_name'   => $validated['customer_name'] ?? null,
                'customer_phone'  => $validated['customer_phone'] ?? null,
                'customer_note'   => $validated['customer_note'] ?? null,

                'status'          => $isPaid ? 'paid' : 'pending',
                // 'payment_status'  => $isPaid ? 'paid' : 'unpaid',
                'paid_at'         => $isPaid ? now() : null,

                'subtotal'        => $subtotal,
                'discount'        => $discount,
                'tax'             => $tax,
                'service'         => $service,
                'grand_total'     => $grandTotal,
                'payment_method'  => $validated['payment_method'],
                'cashier_id'      => Auth::id(),
            ]);

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
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Order berhasil dibuat');

    } catch (ValidationException $e) {
        throw $e;

    } catch (\Throwable $e) {
        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}
}
