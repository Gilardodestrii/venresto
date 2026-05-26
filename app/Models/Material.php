<?php

namespace App\Models;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'unit',
        'stock',
        'min_stock',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if ($tenant = TenantContext::get()) {
                $model->tenant_id = $tenant->id;
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

    public function scopeTenant($query)
    {
        return $query->where('tenant_id', TenantContext::get()->id);
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock <= $this->min_stock;
    }
}