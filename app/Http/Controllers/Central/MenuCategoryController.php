<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    //
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;

        $categories = MenuCategory::where('tenant_id', $tenantId)
            ->orderBy('seq', 'asc')
            ->get();

        return view('admin.menu_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.menu_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        MenuCategory::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $request->name,
            'seq' => $request->seq ?? 0
        ]);

        $tenant = request()->tenant; // dari route parameter

        return redirect()
            ->route('menu-categories.index', $tenant)
            ->with('success', 'Kategori dibuat');
    }

    public function edit($tenant, $id)
    {
        $menu_category = MenuCategory::where('tenant_id', auth()->user()->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        return view('admin.menu_categories.edit', compact('menu_category'));
    }

    public function update(Request $request, $tenant, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $category = MenuCategory::where('tenant_id', auth()->user()->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        $category->update([
            'name' => $request->name,
            'seq' => $request->seq ?? 0
        ]);

        return redirect()
            ->route('menu-categories.index', $tenant)
            ->with('success', 'Kategori berhasil diupdate');
}

    public function destroy(MenuCategory $menu_category)
    {
        $menu_category->delete();
        return back()->with('success', 'Kategori dihapus');
    }

    public function show (MenuCategory $menu_category)
    {
        return view('admin.menu_categories.show', compact('menu_category'));
    }

}
