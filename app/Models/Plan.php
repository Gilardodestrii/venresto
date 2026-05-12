<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','name','currency',
        'price_monthly','price_yearly',
        'max_outlets','max_tables','max_users',
        'trial_days','features_json','is_active',
    ];

    protected $casts = [
        'price_monthly' => 'integer',
        'price_yearly'  => 'integer',
        'max_outlets'   => 'integer',
        'max_tables'    => 'integer',
        'max_users'     => 'integer',
        'trial_days'    => 'integer',
        'features_json' => 'array',
        'is_active'     => 'bool',
    ];

    /* ---------- Relationships ---------- */
    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    /* ---------- Scopes ---------- */
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeCode($q, string $code)
    {
        return $q->where('code', $code);
    }

    /* ---------- Helpers ---------- */
    public function priceFor(string $interval = 'monthly'): ?int
    {
        return $interval === 'yearly' ? $this->price_yearly : $this->price_monthly;
    }

    public function formatIdr(?int $amount): ?string
    {
        return is_null($amount) ? null : 'Rp '.number_format($amount, 0, ',', '.');
    }

    public function getDisplayMonthlyAttribute(): ?string
    {
        return $this->formatIdr($this->price_monthly);
    }

    public function getDisplayYearlyAttribute(): ?string
    {
        return $this->formatIdr($this->price_yearly);
    }

    public function allows(string $key): bool
    {
        // Contoh: $plan->allows('printer_kitchen')
        return (bool) data_get($this->features_json, $key, false);
    }
}
