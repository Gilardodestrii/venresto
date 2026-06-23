<?php

namespace App\Http\Middleware;

use App\Services\TenantContext;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * VenResto uses path-based tenancy: /{tenant}/login for tenant users,
     * /login for central users. We never want to redirect to a non-existent
     * named "login" route — which would throw "Route [login] not defined."
     */
    protected function redirectTo(Request $request): ?string
    {
        // For API / JSON / AJAX requests: don't redirect, return 401 JSON.
        if ($request->expectsJson()) {
            return null;
        }

        // Tenant-scoped request (path-based tenancy): use tenant login.
        $tenant = TenantContext::get();
        if ($tenant) {
            return url('/' . $tenant->slug . '/login');
        }

        // Otherwise (central app / unknown tenant): central login.
        return url('/login');
    }
}
