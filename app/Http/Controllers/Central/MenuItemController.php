<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    //
    public function index()
    {
        $items = MenuItem::with('category')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->get();

        return view('admin.menu_items.index', compact('items'));
    }

    public function create()
    {
        $categories = MenuCategory::where('tenant_id', auth()->user()->tenant_id)->get();

        return view('admin.menu_items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required'
        ]);

        MenuItem::create([
            'tenant_id' => Auth::user()->tenant_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'sku' => $request->sku,
            'image_url' => $request->image_url,
            'is_active' => 1
        ]);

       $tenant = request()->route('tenant');

        return redirect()
            ->route('menu-items.index', $tenant)
            ->with('success', 'Menu ditambahkan');
    }

    public function edit($tenant, $id)
    {
        $menu_item = MenuItem::where('tenant_id', auth()->user()->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        $categories = MenuCategory::where('tenant_id', auth()->user()->tenant_id)->get();

        return view('admin.menu_items.edit', compact('menu_item', 'categories'));
    }

    public function update(Request $request, $tenant, $id)
    {
        $menu_item = MenuItem::where('tenant_id', auth()->user()->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        $menu_item->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'sku' => $request->sku,
            'image_url' => $request->image_url,
            'is_active' => $request->is_active ?? 0
        ]);

        return redirect()
            ->route('menu-items.index', $tenant)
            ->with('success', 'Menu diupdate');
    }

    public function destroy($tenant, $id)
    {
        $menu_item = MenuItem::where('tenant_id', auth()->user()->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        $menu_item->delete();

        return back()->with('success', 'Menu dihapus');
    }

    public function show($tenant, $id)
    {
        $menu_item = MenuItem::where('tenant_id', auth()->user()->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        return view('admin.menu_items.show', compact('menu_item'));
    }
}
