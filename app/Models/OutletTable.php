<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\TenantContext;

class OutletTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'table_code',
    ];

    /**
     * Auto scope tenant (SaaS multi tenant safety)
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (TenantContext::get()) {
                $model->tenant_id = TenantContext::get()->id;
            }
        });
    }

    /**
     * Relationship ke tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relationship ke outlet (kalau multi outlet POS)
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Helper QR URL
     */
    public function qrUrl()
    {
        return url($this->tenant->slug . '/qr/' . $this->table_code);
    }
}