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

        $request->validate([
            'table_code'       => 'required',
            'payment_method'   => 'required',
            'items'            => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            $subtotal = 0;

            foreach ($request->items as $item) {
                $subtotal += ($item['qty'] * $item['price']);
            }

            $discount = $request->discount ?? 0;
            $tax      = $request->tax ?? 0;
            $service  = $request->service ?? 0;

            $grandTotal = ($subtotal - $discount) + $tax + $service;

            $order = Order::create([
                'tenant_id'       => $tenantModel->id,
                'outlet_id'       => session('current_outlet_id'),
                'code'            => 'ORD-' . strtoupper(Str::random(6)),
                'table_code'      => $request->table_code,
                'customer_name'   => $request->customer_name,
                'customer_phone'  => $request->customer_phone,
                'customer_note'   => $request->customer_note,
                'status' => $request->action == 'hold'
                    ? 'pending_payment'
                    : 'paid',

                'subtotal'        => $subtotal,
                'discount'        => $discount,
                'tax'             => $tax,
                'service'         => $service,
                'grand_total'     => $grandTotal,
                'payment_method'  => $request->payment_method,
                'cashier_id'      => Auth::id(),
            ]);

            foreach ($request->items as $item) {

                OrderItem::create([
                    'tenant_id'     => $tenantModel->id,
                    'order_id'      => $order->id,
                    'menu_item_id'  => $item['menu_item_id'],
                    'qty'           => $item['qty'],
                    'price'         => $item['price'],
                    'note'          => $item['note'] ?? null,
                    'kitchen_status'=> 'new',
                ]);
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Order berhasil dibuat');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}
