<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\TenantSetting;
use App\Services\TenantContext;
use Illuminate\Http\Request;

class TenantSettingController extends Controller
{
    public function index()
    {
        $tenant = TenantContext::get();

        $settings = TenantSetting::firstOrCreate(
            ['tenant_id' => $tenant->id],
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
                'qris_static_payload' => null,
            ]
        );

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $tenant = TenantContext::get();

        $validated = $request->validate([
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:1'],
            'service_rate' => ['required', 'numeric', 'min:0', 'max:1'],
            'stock_deduct_on' => ['required', 'in:open,paid'],
            'qris_static_payload' => ['nullable', 'string', 'max:5000'],
        ]);

        TenantSetting::updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'tax_enabled' => $request->boolean('tax_enabled'),
                'tax_rate' => $validated['tax_rate'],
                'tax_inclusive' => $request->boolean('tax_inclusive'),
                'service_enabled' => $request->boolean('service_enabled'),
                'service_rate' => $validated['service_rate'],
                'service_inclusive' => $request->boolean('service_inclusive'),
                'kitchen_ticket_on_open_for_cash' => $request->boolean('kitchen_ticket_on_open_for_cash'),
                'stock_deduct_on' => $validated['stock_deduct_on'],
                'payments_json' => [
                    'cash' => $request->boolean('payment_cash'),
                    'qris' => $request->boolean('payment_qris'),
                    'qris_snap' => $request->boolean('payment_qris_snap'),
                    'qris_static' => $request->boolean('payment_qris_static'),
                ],
                'qris_static_payload' => $validated['qris_static_payload'] ?? null,
            ]
        );

        return back()->with('success', 'Pengaturan tenant berhasil diperbarui.');
    }
}
