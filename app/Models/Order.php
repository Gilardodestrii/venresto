<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'code',
        'table_code',
        'customer_name',
        'customer_phone',
        'customer_note',
        'status',
        'subtotal',
        'discount',
        'tax',
        'service',
        'grand_total',
        'payment_method',
        'cashier_id',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'discount'     => 'decimal:2',
        'tax'          => 'decimal:2',
        'service'      => 'decimal:2',
        'grand_total'  => 'decimal:2',
    ];

    /*
    |-----------------------------------------
    | RELATIONSHIP
    |-----------------------------------------
    */

    // Tenant (multi-tenant)
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Outlet / Cabang
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    // Cashier / User yang memproses
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |-----------------------------------------
    | SCOPES (optional tapi berguna)
    |-----------------------------------------
    */

public function scopePaid($query)
{
    return $query->where('status', 'paid');
}

public function scopePending($query)
{
    return $query->where('status', 'pending_payment');
}

public function scopeOpen($query)
{
    return $query->where('status', 'open');
}
}