<?php
namespace App\Http\Controllers\Central;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function __invoke(Request $r){
        // TODO: verify signature & update subscription/order payment
        return response()->json(['ok'=>true]);
    }
}
