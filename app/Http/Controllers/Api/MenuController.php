<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\MenuCategory;

class MenuController extends Controller
{
    public function index(Request $r){
        $q = MenuItem::query()->with('category')->where('is_active',1);
        if($r->category) $q->whereHas('category', fn($x)=>$x->where('name',$r->category));
        if($r->q) $q->where('name','like','%'.$r->q.'%');
        return response()->json(['data'=>$q->limit(200)->get()]);
    }
    public function store(Request $r){
        $data = $r->validate(['name'=>'required','price'=>'required|integer','category_id'=>'required|integer']);
        $item = MenuItem::create($data + ['is_active'=>1]);
        return response()->json($item,201);
    }
    public function update(MenuItem $item, Request $r){
        $item->update($r->only(['name','price','category_id','is_active','image_url']));
        return response()->json($item);
    }
    public function destroy(MenuItem $item){
        $item->delete(); return response()->noContent();
    }
    public function recommended(){
        // Stub: return top items; replace with real recommendations table
        return response()->json(['data'=>MenuItem::orderBy('id','desc')->take(8)->get()]);
    }
}
