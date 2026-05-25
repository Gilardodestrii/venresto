<?php

namespace App\Models;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'order_id',
        'cashier_session_id',
        'cashier_id',
        'method',
        'amount',
        'paid_amount',
        'change_amount',
        'reference',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($payment) {
            if ($tenant = TenantContext::get()) {
                $payment->tenant_id = $payment->tenant_id ?? $tenant->id;
            }

            $payment->cashier_id = $payment->cashier_id ?? auth()->id();
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function cashierSession()
    {
        return $this->belongsTo(CashierSession::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
