<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Outlet;
use App\Models\User;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'total_outlets'  => Outlet::count(),
            'total_users'    => User::count(),
            'total_orders'   => Order::count(),
            'recent_tenants' => Tenant::latest()->take(5)->get(),
        ];

        return view('superadmin.dashboard', compact('stats'));
    }
}
