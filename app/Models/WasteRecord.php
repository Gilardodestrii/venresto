<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\TenantContext;

class WasteRecord extends Model
{
    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'code',
        'reason',
        'notes',
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

    public function items()
    {
        return $this->hasMany(WasteRecordItem::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
