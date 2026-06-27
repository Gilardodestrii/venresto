<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $tenants = Tenant::with(['owner'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhereHas('owner', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.tenants.index', compact('tenants', 'search'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['owner']);
        
        $stats = [
            'total_outlets' => $tenant->outlets()->count(),
            'total_users' => \App\Models\User::where('tenant_id', $tenant->id)->count(),
            'total_orders' => $tenant->orders()->count(),
            'total_items' => $tenant->menuItems()->count(),
        ];

        return view('superadmin.tenants.show', compact('tenant', 'stats'));
    }
}