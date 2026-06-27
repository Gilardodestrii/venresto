<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model {
    protected $fillable = ['name','slug','plan_id','status','trial_ends_at','owner_user_id'];
    protected $casts = ['trial_ends_at' => 'datetime'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
