<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Services\TenantContext;
use Spatie\Permission\PermissionRegistrar;

class ResolveTenantFromPath
{
    public function handle(Request $request, Closure $next)
    {
        $segments = $request->segments();
        $slug = $segments[0] ?? null;

        /**
         * Central/public paths that must not be resolved as tenant slugs.
         *
         * Because VenResto uses path-based tenancy, every first URL segment is a
         * tenant candidate by default. These reserved paths belong to the central
         * app/marketing/system routes, so the request should continue without
         * loading TenantContext.
         */
        $central = [
            '',
            null,

            // Public marketing pages
            'features',
            'pricing',
            'documentation',
            'privacy',
            'terms',
            'contact',

            // Auth / SaaS pages
            'login',
            'logout',
            'signup',
            'checkout',
            'webhooks',

            // Password routes
            'password',
            'forgot-password',
            'reset-password',

            // System / assets / tooling
            'api',
            'storage',
            'vendor',
            '_debugbar',
            'debugbar',
            'horizon',
            'telescope',
            'build',
            'superadmin',
            'favicon.ico',
        ];

        if (!$slug || in_array($slug, $central, true)) {
            return $next($request);
        }

        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            return $next($request);
        }

        $tenant = Tenant::where('slug', $slug)->first();
        abort_unless($tenant, 404, 'Tenant not found');

        TenantContext::set($tenant);

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
        }

        $outlet = \App\Models\Outlet::firstOrCreate(
            ['tenant_id' => $tenant->id],
            ['name' => 'Outlet Utama']
        );

        session([
            'current_outlet_id' => $outlet->id,
        ]);

        view()->share('currentTenant', $tenant);
        view()->share('currentOutlet', $outlet);

        $stripped = '/' . implode('/', array_slice($segments, 1));
        $request->server->set('REQUEST_URI', $stripped === '/' ? '/' : $stripped);
        $request->server->set('PATH_INFO', $stripped === '/' ? '/' : $stripped);

        return $next($request);
    }
}
