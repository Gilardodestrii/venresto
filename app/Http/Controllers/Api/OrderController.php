<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TenantContext;

class OrderController extends Controller
{
    public function index(Request $r){
        // TODO: implement order listing with status filter
        return response()->json(['data'=>[]]);
    }
    public function store(Request $r){
        $data = $r->validate([
          'customer_name'=>['required','string','max:100'],
          'customer_phone'=>['nullable','string','max:20'],
          'customer_note'=>['nullable','string','max:255'],
          'table_code'=>['nullable','string','max:50'],
          'items'=>['required','array','min:1'],
          'items.*.menu_item_id'=>['required','integer'],
          'items.*.qty'=>['required','integer','min:1'],
          'items.*.price'=>['required','integer','min:0'],
          'payment_method'=>['nullable','in:cash,qris'],
        ]);
        $tenant = TenantContext::get();
        // Stub save...
        $id = random_int(1000,9999);
        $res = [
          'id'=>$id,
          'code'=>'ORD-'.date('Ymd').'-'.$id,
          'status'=>$data['payment_method']==='cash'?'open':'pending_payment',
          'customer_name'=>$data['customer_name'],
          'customer_phone'=>$data['customer_phone']??null,
          'customer_note'=>$data['customer_note']??null,
          'table_code'=>$data['table_code']??null,
          'subtotal'=>0,'discount'=>0,'tax'=>0,'service'=>0,'grand_total'=>0,
          'payment_method'=>$data['payment_method']??null,
          'items'=>$data['items'],
          'qris'=> $data['payment_method']==='qris' ? ['type'=>'snap','qr_url'=>'https://example.com/qr.png','expires_at'=>date('c', time()+900)] : null,
        ];
        return response()->json($res,201);
    }
    public function pay($id, Request $r){
        $validated = $r->validate(['payment_method'=>'required|in:cash,qris_static','paid_amount'=>'required|integer|min:0']);
        return response()->json(['id'=>(int)$id,'status'=>'paid','payment_method'=>$validated['payment_method']]);
    }
}
