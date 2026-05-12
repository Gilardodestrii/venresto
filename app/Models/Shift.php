<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'tenant_id',
        'cashier_id',
        'opened_at',
        'closed_at',
        'opening_cash',
        'closing_cash',
        'notes',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}