<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\TenantContext;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'address',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if ($tenant = TenantContext::get()) {
                $model->tenant_id = $tenant->id;
            }
        });
    }

    public function tables()
    {
        return $this->hasMany(OutletTable::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function sessions()
    {
        return $this->hasMany(CashierSession::class);
    }
}