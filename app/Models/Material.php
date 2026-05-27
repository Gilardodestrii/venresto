<?php

namespace App\Models;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'name',
        'unit',
        'stock',
        'min_stock',
        'cost_per_unit',
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
        });
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant_id', TenantContext::get()->id);
    }

    public function scopeCurrentOutlet($query)
    {
        return $query->where('outlet_id', session('current_outlet_id'));
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock <= $this->min_stock;
    }
}
