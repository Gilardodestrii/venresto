<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return view(strtolower('Inventory').'.index');
    }

    public function store(Request $request)
    {
        // TODO: implement store logic
        return response()->json([
            'message' => 'InventoryController store success'
        ]);
    }

    public function update(Request $request, $id)
    {
        // TODO: implement update logic
        return response()->json([
            'message' => 'InventoryController update success'
        ]);
    }

    public function destroy($id)
    {
        // TODO: implement delete logic
        return response()->json([
            'message' => 'InventoryController deleted'
        ]);
    }
}
