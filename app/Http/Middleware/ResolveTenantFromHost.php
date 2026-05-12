<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use App\Services\TenantContext;
use Spatie\Permission\PermissionRegistrar;

class ResolveTenantFromHost
{
    public function handle($request, Closure $next)
    {
        // contoh host: warung-aji.appku.com
        $host = $request->getHost(); // warung-aji.appku.com
        $parts = explode('.', $host);
        $slug  = $parts[0] ?? null;

        // abaikan domain utama (www/app/appku)
        if (!$slug || in_array($slug, ['www','app','appku','localhost'])) {
            return $next($request);
        }

        // temukan tenant berdasarkan slug
        $tenant = Tenant::where('slug', $slug)->first();
        abort_unless($tenant, 404, 'Tenant tidak ditemukan');

        // simpan konteks tenant agar bisa diakses global
        TenantContext::set($tenant);

        // integrasi dengan spatie/laravel-permission (teams)
        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
        }

        // share ke view
        view()->share('currentTenant', $tenant);

        return $next($request);
    }
}
