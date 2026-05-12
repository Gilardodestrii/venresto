<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'tenant_id',
        'material_id',
        'type',
        'qty',
        'ref',
        'created_by',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}