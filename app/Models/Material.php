<?php

namespace App\Models;

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
}