<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TenantContext;
use App\Models\TenantSetting;

class SettingsController extends Controller
{
    public function show(){
        $t = TenantContext::get();
        return response()->json(TenantSetting::firstOrCreate(['tenant_id'=>$t->id]));
    }
    public function update(Request $r){
        $t = TenantContext::get();
        $s = TenantSetting::firstOrCreate(['tenant_id'=>$t->id]);
        $s->fill($r->only([
          'tax_enabled','tax_rate','tax_inclusive',
          'service_enabled','service_rate','service_inclusive',
          'kitchen_ticket_on_open_for_cash','stock_deduct_on','payments_json'
        ]));
        $s->save();
        return response()->json($s);
    }
}
