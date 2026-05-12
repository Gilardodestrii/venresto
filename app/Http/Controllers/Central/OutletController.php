<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\OutletTable;
use App\Services\TenantContext;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    //
    public function index()
    {
        $tenant = TenantContext::get();

        $outlets = Outlet::where('tenant_id', $tenant->id)
            ->latest()
            ->paginate(10);

        return view('admin.outlet.index', compact('outlets'));
    }

        public function store(Request $request)
    {
        $tenant = TenantContext::get();

        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        Outlet::create([
            'tenant_id' => $tenant->id,
            'name'      => $request->name,
            'address'   => $request->address,
        ]);

        return back()->with('success', 'Outlet berhasil ditambahkan');
    }

    public function destroy($tenant, Outlet $outlet)
    {
        $outlet->delete();
        $tables = OutletTable::where('outlet_id', $outlet->id)->delete();


        return back()->with('success', 'Outlet berhasil dihapus');
    }

    public function show($tenant, Outlet $outlet)
    {
        return view('admin.outlet.show', compact('outlet'));
    }

    public function edit($tenant, Outlet $outlet)
    {
        return view('admin.outlet.edit', compact('outlet'));
    }

    public function update(Request $request, $tenant, Outlet $outlet)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $outlet->update([
            'name'    => $request->name,
            'address' => $request->address,
        ]);
        return back()->with('success', 'Outlet berhasil diperbarui');

    }
}
