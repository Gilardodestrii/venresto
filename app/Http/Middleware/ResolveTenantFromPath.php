<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantContext;
use Spatie\Permission\PermissionRegistrar;

class ResolveTenantFromPath
{

public function handle(Request $request, Closure $next)
{
    $segments = $request->segments();
    $slug = $segments[0] ?? null;

    $central = [
        '', null, 'api','login','logout', 'pricing', 'signup', 'checkout', 'webhooks',
        'password','forgot-password','reset-password',
        'storage','vendor','_debugbar','debugbar','horizon','telescope','build','favicon.ico',
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

    if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
        app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
    }


    $outlet = \App\Models\Outlet::firstOrCreate(
        ['tenant_id' => $tenant->id],
        ['name' => 'Outlet Utama']
    );

    // SET SESSION
    session([
        'current_outlet_id' => $outlet->id
    ]);

    // SHARE KE VIEW (SAFE)
    view()->share('currentTenant', $tenant);
    view()->share('currentOutlet', $outlet);

    // rewrite URL
    $stripped = '/'.implode('/', array_slice($segments, 1));
    $request->server->set('REQUEST_URI', $stripped === '/' ? '/' : $stripped);
    $request->server->set('PATH_INFO',  $stripped === '/' ? '/' : $stripped);

    return $next($request);
}
  
}
