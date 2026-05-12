<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model {
    protected $fillable = ['name','slug','plan_id','status','trial_ends_at','owner_user_id'];
    protected $casts = ['trial_ends_at' => 'datetime'];
}
