<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\CashierSession;
use App\Models\Payment;
use App\Services\TenantContext;
use Illuminate\Http\Request;

class CashierSessionController extends Controller
{
    public function index()
    {
        $tenant = TenantContext::get();
        $outletId = session('current_outlet_id');

        $activeSession = CashierSession::where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->where('cashier_id', auth()->id())
            ->where('status', 'open')
            ->latest()
            ->first();

        $sessions = CashierSession::with('cashier')
            ->where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->latest()
            ->paginate(10);

        $activePaymentTotal = $activeSession
            ? Payment::where('cashier_session_id', $activeSession->id)->sum('amount')
            : 0;

        return view('admin.cashier-sessions.index', compact(
            'sessions',
            'activeSession',
            'activePaymentTotal'
        ));
    }

    public function open(Request $request)
    {
        $request->validate([
            'opening_cash' => 'required|numeric|min:0'
        ]);

        $tenant = TenantContext::get();
        $outletId = session('current_outlet_id');

        $existing = CashierSession::where('tenant_id', $tenant->id)
            ->where('outlet_id', $outletId)
            ->where('cashier_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return back()->with('error', 'Masih ada shift kasir yang terbuka.');
        }

        CashierSession::create([
            'tenant_id' => $tenant->id,
            'outlet_id' => $outletId,
            'cashier_id' => auth()->id(),
            'opened_at' => now(),
            'opening_cash' => $request->opening_cash,
            'status' => 'open',
        ]);

        return back()->with('success', 'Shift kasir berhasil dibuka.');
    }

    public function close(Request $request, $tenant, $sessionId)
    {
        $request->validate([
            'closing_cash' => 'required|numeric|min:0'
        ]);

        $tenantModel = TenantContext::get();
        $outletId = session('current_outlet_id');

        $session = CashierSession::where('tenant_id', $tenantModel->id)
            ->where('outlet_id', $outletId)
            ->where('id', $sessionId)
            ->where('status', 'open')
            ->firstOrFail();

        $paymentTotal = Payment::where('cashier_session_id', $session->id)
            ->sum('amount');

        $expectedCash = $session->opening_cash + $paymentTotal;

        $session->update([
            'closed_at' => now(),
            'closing_cash' => $request->closing_cash,
            'expected_cash' => $expectedCash,
            'cash_difference' => $request->closing_cash - $expectedCash,
            'status' => 'closed',
        ]);

        return back()->with('success', 'Shift kasir berhasil ditutup.');
    }
}
