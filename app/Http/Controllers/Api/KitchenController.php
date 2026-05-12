<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index(Request $r){
        // TODO: return tickets by status
        return response()->json(['data'=>[]]);
    }
    public function update($id, Request $r){
        $r->validate(['status'=>'required|in:cook,ready,served']);
        return response()->json(['id'=>(int)$id,'status'=>$r->status]);
    }
}
