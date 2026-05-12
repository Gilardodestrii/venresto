<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\TenantContext;

class CashierSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'cashier_id',
        'opened_at',
        'closed_at',
        'opening_cash',
        'closing_cash',
        'status',
        'notes'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if ($tenant = TenantContext::get()) {
                $model->tenant_id = $tenant->id;
            }
        });
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function close($closingCash)
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closing_cash' => $closingCash
        ]);
    }
}