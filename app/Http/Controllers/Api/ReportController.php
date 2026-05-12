<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sales(Request $r){
        return response()->json([
          'summary'=>['orders'=>0,'gross'=>0,'avg_order'=>0],
          'by_day'=>[]
        ]);
    }
    public function topItems(Request $r){
        return response()->json(['data'=>[]]);
    }
    public function cashiers(Request $r){
        return response()->json(['data'=>[]]);
    }
}
