<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model {
    protected $fillable = ['tenant_id','category_id','name','price','sku','image_url','is_active'];
    public function category(){ return $this->belongsTo(MenuCategory::class,'category_id'); }

    public function tenant(){ return $this->belongsTo(Tenant::class); }
}


