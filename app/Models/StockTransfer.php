<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\TenantContext;

class StockTransfer extends Model
{
    protected $fillable = [
        'tenant_id',
        'from_outlet_id',
        'to_outlet_id',
        'code',
        'status',
        'notes',
        'created_by',
        'completed_by',
        'completed_at',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if ($tenant = TenantContext::get()) {
                $model->tenant_id = $tenant->id;
            }

            if (!$model->created_by && auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }

    public function items()
    {
        return $this->hasMany(StockTransferItem::class);
    }

    public function fromOutlet()
    {
        return $this->belongsTo(Outlet::class, 'from_outlet_id');
    }

    public function toOutlet()
    {
        return $this->belongsTo(Outlet::class, 'to_outlet_id');
    }
}
