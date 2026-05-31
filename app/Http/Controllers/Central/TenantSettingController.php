<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\TenantSetting;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TenantSettingController extends Controller
{
    public function index()
    {
        $tenant = TenantContext::get();

        abort_if(!$tenant, 404);

        $settings = TenantSetting::firstOrCreate(
            ['tenant_id' => $tenant->id],
            $this->defaultSettings()
        );

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $tenant = TenantContext::get();

        abort_if(!$tenant, 404);

        $validated = $request->validate([
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:1'],
            'service_rate' => ['required', 'numeric', 'min:0', 'max:1'],
            'stock_deduct_on' => ['required', Rule::in(['open', 'paid'])],

            'qris_mode' => ['nullable', Rule::in(['snap', 'static'])],

            'qris_snap_client_key' => ['nullable', 'string', 'max:255'],
            'qris_snap_server_key' => ['nullable', 'string', 'max:255'],
            'qris_snap_expiry_minutes' => ['nullable', 'integer', 'min:1', 'max:1440'],

            'qris_static_payload' => ['nullable', 'string', 'max:10000'],
            'qris_static_image_url' => ['nullable', 'url', 'max:1000'],
        ]);

        $cashEnabled = $request->boolean('cash_enabled');
        $qrisEnabled = $request->boolean('qris_enabled');
        $qrisMode = $validated['qris_mode'] ?? 'snap';

        $paymentsJson = [
            'qris_mode' => $qrisMode,

            'qris_snap' => [
                'client_key' => $validated['qris_snap_client_key'] ?? null,
                'server_key' => $validated['qris_snap_server_key'] ?? null,
                'expiry_minutes' => (int) ($validated['qris_snap_expiry_minutes'] ?? 15),
            ],

            'qris_static' => [
                'qr_payload' => $validated['qris_static_payload'] ?? null,
                'qr_image_url' => $validated['qris_static_image_url'] ?? null,
            ],

            'cash_enabled' => $cashEnabled,
            'qris_enabled' => $qrisEnabled,
        ];

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

                'payments_json' => $paymentsJson,

                // optional kalau kolom ini masih ada di database
                'qris_static_payload' => $validated['qris_static_payload'] ?? null,
            ]
        );

        return back()->with('success', 'Pengaturan tenant berhasil diperbarui.');
    }

    private function defaultSettings(): array
    {
        return [
            'tax_enabled' => true,
            'tax_rate' => 0.11,
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

            'qris_static_payload' => null,
        ];
    }
}