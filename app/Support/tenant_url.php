<?php

use App\Services\TenantContext;

if (!function_exists('tenant_url')) {
    function tenant_url(string $path = ''): string
    {
        $tenant = TenantContext::get();
        $slug = $tenant?->slug;

        $path = ltrim($path, '/');
        if ($slug) {
            return url($slug.'/'.$path);
        }
        return url($path);
    }
}
