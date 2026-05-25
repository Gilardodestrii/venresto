<?php

namespace App\Actions\Orders;

use App\Models\CashierSession;
use App\Models\Order;
use App\Models\Payment;
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProcessOrderPaymentAction
{
    public function handle(Order $order, array $data): Payment
    {
        return DB::transaction(function () use ($order, $data) {

            $tenant = TenantContext::get();
            $outletId = session('current_outlet_id');

            $lockedOrder = Order::where('tenant_id', $tenant->id)
                ->where('outlet_id', $outletId)
                ->where('id', $order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status === 'paid') {
                throw ValidationException::withMessages([
                    'order' => 'Order sudah dibayar.'
                ]);
            }

            $activeSession = CashierSession::where('tenant_id', $tenant->id)
                ->where('outlet_id', $outletId)
                ->where('cashier_id', auth()->id())
                ->where('status', 'open')
                ->latest()
                ->first();

            if (! $activeSession) {
                throw ValidationException::withMessages([
                    'cashier_session' => 'Shift kasir belum dibuka.'
                ]);
            }

            $paidAmount = (float) $data['paid_amount'];
            $grandTotal = (float) $lockedOrder->grand_total;

            if ($paidAmount < $grandTotal) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Nominal pembayaran kurang dari total order.'
                ]);
            }

            $payment = Payment::create([
                'tenant_id' => $lockedOrder->tenant_id,
                'outlet_id' => $lockedOrder->outlet_id,
                'order_id' => $lockedOrder->id,
                'cashier_session_id' => $activeSession->id,
                'cashier_id' => auth()->id(),
                'method' => $data['payment_method'],
                'amount' => $grandTotal,
                'paid_amount' => $paidAmount,
                'change_amount' => max(0, $paidAmount - $grandTotal),
                'reference' => 'PAY-' . now()->format('YmdHis') . '-' . $lockedOrder->id,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $lockedOrder->update([
                'status' => 'paid',
                'payment_method' => $data['payment_method'],
                'cashier_id' => auth()->id(),
            ]);

            return $payment;
        });
    }
}
