<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        return view(strtolower('Pos').'.index');
    }

    public function store(Request $request)
    {
        // TODO: implement store logic
        return response()->json([
            'message' => 'PosController store success'
        ]);
    }

    public function update(Request $request, $id)
    {
        // TODO: implement update logic
        return response()->json([
            'message' => 'PosController update success'
        ]);
    }

    public function destroy($id)
    {
        // TODO: implement delete logic
        return response()->json([
            'message' => 'PosController deleted'
        ]);
    }
}
