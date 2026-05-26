<?php

namespace App\Models;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'tenant_id',
        'outlet_id',
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

            if (!$model->outlet_id && session('current_outlet_id')) {
                $model->outlet_id = session('current_outlet_id');
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
}
