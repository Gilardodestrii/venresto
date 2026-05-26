<?php

namespace App\Models;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'tenant_id',
        'item_id',
        'material_id',
        'qty',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if ($tenant = TenantContext::get()) {
                $model->tenant_id = $tenant->id;
            }
        });
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'item_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant_id', TenantContext::get()->id);
    }
}