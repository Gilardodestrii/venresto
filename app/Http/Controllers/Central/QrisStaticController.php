<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\TenantSetting;
use App\Services\QrisStaticService;
use App\Services\TenantContext;
use Illuminate\Http\Request;

class QrisStaticController extends Controller
{
    public function generate(Request $request, QrisStaticService $qrisService)
    {
        $tenant = TenantContext::get();

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $settings = TenantSetting::where('tenant_id', $tenant->id)->first();

        if (!$settings || blank($settings->qris_static_payload)) {
            return response()->json([
                'success' => false,
                'message' => 'QRIS Static Payload belum diatur di Tenant Settings.',
            ], 422);
        }

        $payload = $qrisService->generate(
            $settings->qris_static_payload,
            (float) $validated['amount']
        );

        return response()->json([
            'success' => true,
            'amount' => (float) $validated['amount'],
            'payload' => $payload,
            'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&data=' . urlencode($payload),
        ]);
    }
}
