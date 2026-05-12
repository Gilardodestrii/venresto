<?php
namespace App\Services;
use App\Models\Tenant;
class TenantContext { protected static ?Tenant $t=null;
  public static function set(Tenant $tenant){ self::$t=$tenant; }
  public static function get(): ?Tenant { return self::$t; } }
