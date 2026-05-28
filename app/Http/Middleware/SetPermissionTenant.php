<?php

namespace App\Http\Middleware;

use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class SetPermissionTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($tenant = TenantContext::get()) {
            setPermissionsTeamId($tenant->id);

            app(PermissionRegistrar::class)
                ->setPermissionsTeamId($tenant->id);
        }

        return $next($request);
    }
}