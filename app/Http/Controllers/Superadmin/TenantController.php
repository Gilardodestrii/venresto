<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Outlet;
use App\Models\User;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $tenants = Tenant::query()
            ->with('owner')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%")
                      ->orWhereHas('owner', function ($q) use ($search) {
                          $q->where('email', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.tenants.index', compact('tenants', 'search'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('owner');

        $stats = [
            'total_outlets' => Outlet::where('tenant_id', $tenant->id)->count(),
            'total_items'   => MenuItem::where('tenant_id', $tenant->id)->count(),
            'total_users'   => User::where('tenant_id', $tenant->id)->count(),
            'total_orders'  => Order::where('tenant_id', $tenant->id)->count(),
        ];

        return view('superadmin.tenants.show', compact('tenant', 'stats'));
    }
}
