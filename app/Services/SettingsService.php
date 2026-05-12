<?php

namespace App\Services;

use App\Models\TenantSetting;

class SettingsService
{
  public function forTenant(int $tenantId): object
  {
    $defaults = [
      'tax_enabled' => true,
      'tax_rate' => 0.11,
      'tax_inclusive' => false,
      'service_enabled' => true,
      'service_rate' => 0.05,
      'service_inclusive' => false,
      'kitchen_ticket_on_open_for_cash' => true,
      'stock_deduct_on' => 'paid', // <-- sesuai migrasi kamu
      'payments_json' => [
        'cash_enabled' => true,
        'qris_enabled' => true,
        'qris_mode' => 'snap', // 'snap' | 'static'
        'qris_snap' => [
          'server_key' => null,
          'client_key' => null,
          'expiry_minutes' => 15,
        ],
        'qris_static' => [
          'qr_payload' => null,
          'qr_image_url' => null,
        ],
      ],
    ];

    $setting = TenantSetting::firstOrCreate(['tenant_id' => $tenantId], $defaults);
    $p = (object) ($setting->payments_json ?? $defaults['payments_json']);

    return (object) [
      'tax_enabled' => $setting->tax_enabled,
      'tax_rate' => $setting->tax_rate,
      'tax_inclusive' => $setting->tax_inclusive,
      'service_enabled' => $setting->service_enabled,
      'service_rate' => $setting->service_rate,
      'service_inclusive' => $setting->service_inclusive,
      'kitchen_ticket_on_open_for_cash' => $setting->kitchen_ticket_on_open_for_cash,
      'stock_deduct_on' => $setting->stock_deduct_on, // <-- ikut dibawa
      'payments' => (object) [
        'cash_enabled' => $p->cash_enabled ?? true,
        'qris_enabled' => $p->qris_enabled ?? true,
        'qris_mode' => $p->qris_mode ?? 'snap',
        'qris_snap' => (object) ($p->qris_snap ?? []),
        'qris_static' => (object) ($p->qris_static ?? []),
      ],
    ];
  }
}
