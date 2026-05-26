<?php

namespace App\Models;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'material_id',
        'type',
        'qty',
        'stock_before',
        'stock_after',
        'ref',
        'note',
        'source_type',
        'source_id',
        'created_by',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if ($tenant = TenantContext::get()) {
                $model->tenant_id = $tenant->id;
            }

            if (!$model->outlet_id && session('current_outlet_id')) {
                $model->outlet_id = session('current_outlet_id');
            }

            if (!$model->created_by && auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant_id', TenantContext::get()->id);
    }

    public function scopeCurrentOutlet($query)
    {
        return $query->where('outlet_id', session('current_outlet_id'));
    }
}
