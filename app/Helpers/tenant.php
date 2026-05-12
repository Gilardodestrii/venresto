<?php

use App\Services\TenantContext;

if (!function_exists('tenant_url')) {
    function tenant_url($path = '')
    {
        $tenant = TenantContext::get();

        if (!$tenant) {
            return url($path);
        }

        return url($tenant->slug . '/' . ltrim($path, '/'));
    }
}