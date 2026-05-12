<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
  protected $table = 'tenant_settings';
  protected $primaryKey = 'tenant_id';
  public $incrementing = false;
  protected $guarded = [];

  protected $casts = [
    'tax_enabled' => 'bool',
    'tax_rate' => 'float',
    'tax_inclusive' => 'bool',
    'service_enabled' => 'bool',
    'service_rate' => 'float',
    'service_inclusive' => 'bool',
    'kitchen_ticket_on_open_for_cash' => 'bool',
    'payments_json' => 'array',
  ];
}
