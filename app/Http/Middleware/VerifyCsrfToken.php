<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '*/qr/outlets/*/order',   // QR checkout (full URL with tenant slug)
        'qr/outlets/*/order',     // QR checkout (after tenant slug stripped by middleware)
        'webhooks/*',             // Payment webhooks (Midtrans etc.)
    ];
}