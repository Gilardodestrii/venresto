<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Receipt - {{ $order->code }}</title>

<style>
*{
    box-sizing:border-box;
}

html,
body{
    margin:0;
    padding:0;
    background:#f3f4f6;
    color:#111827;
    font-family:"Courier New", Courier, monospace;
}

.receipt-wrapper{
    width:80mm;
    min-height:100vh;
    margin:0 auto;
    padding:12px;
    background:#fff;
}

.receipt{
    width:100%;
}

.center{
    text-align:center;
}

.brand{
    font-size:18px;
    font-weight:800;
    letter-spacing:.5px;
    text-transform:uppercase;
    margin-bottom:3px;
}

.outlet{
    font-size:12px;
    font-weight:600;
    margin-bottom:2px;
}

.muted{
    color:#6b7280;
}

.small{
    font-size:11px;
}

.divider{
    border-top:1px dashed #9ca3af;
    margin:10px 0;
}

.divider-solid{
    border-top:1px solid #111827;
    margin:10px 0;
}

.row{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:8px;
    font-size:12px;
    line-height:1.35;
    margin-bottom:4px;
}

.row span:first-child{
    flex:1;
}

.row span:last-child{
    text-align:right;
    flex-shrink:0;
}

.info-label{
    color:#4b5563;
}

.order-type{
    display:inline-block;
    margin-top:6px;
    padding:4px 9px;
    border:1px solid #111827;
    border-radius:999px;
    font-size:11px;
    font-weight:800;
    letter-spacing:.4px;
    text-transform:uppercase;
}

.section-title{
    text-align:center;
    font-size:11px;
    font-weight:800;
    letter-spacing:.8px;
    margin-bottom:8px;
}

.item{
    margin-bottom:9px;
}

.item-main{
    display:flex;
    justify-content:space-between;
    gap:8px;
    font-size:12px;
    line-height:1.35;
}

.item-name{
    flex:1;
    font-weight:700;
}

.item-total{
    text-align:right;
    flex-shrink:0;
    font-weight:700;
}

.item-meta{
    margin-top:2px;
    font-size:11px;
    color:#4b5563;
}

.item-note{
    margin-top:2px;
    font-size:11px;
    color:#6b7280;
    padding-left:10px;
}

.summary .row{
    font-size:12px;
}

.grand{
    font-weight:900;
    font-size:15px;
    margin-top:6px;
}

.grand span{
    font-size:15px;
}

.payment-box{
    border:1px dashed #9ca3af;
    border-radius:8px;
    padding:8px;
    margin-top:8px;
}

.footer{
    margin-top:14px;
    text-align:center;
    font-size:11px;
    line-height:1.45;
}

.qr-space{
    width:100%;
    margin-top:10px;
    text-align:center;
}

.print-actions{
    width:80mm;
    margin:12px auto;
    display:flex;
    gap:8px;
}

.print-actions button{
    flex:1;
    border:0;
    border-radius:10px;
    padding:10px 12px;
    font-family:Arial, sans-serif;
    font-size:13px;
    font-weight:700;
    cursor:pointer;
}

.btn-print{
    background:#0ea5e9;
    color:#fff;
}

.btn-back{
    background:#e5e7eb;
    color:#111827;
}

@media print{
    @page{
        size:80mm auto;
        margin:0;
    }

    html,
    body{
        width:80mm;
        background:#fff;
    }

    .receipt-wrapper{
        width:80mm;
        min-height:auto;
        margin:0;
        padding:8px;
        box-shadow:none;
    }

    .print-actions{
        display:none;
    }
}
</style>
</head>

<body>

@php
    $orderType = $order->order_type ?? 'dine_in';

    $orderTypeLabel = match ($orderType) {
        'takeaway' => 'Take Away',
        'delivery' => 'Delivery',
        'reservation' => 'Reservation',
        default => 'Dine In',
    };

    $latestPayment = $order->latestPayment ?? $order->payments?->last();

    $paidAmount = $latestPayment?->paid_amount ?? $order->grand_total;
    $changeAmount = $latestPayment?->change_amount ?? max(0, $paidAmount - $order->grand_total);
@endphp

<div class="receipt-wrapper">
    <div class="receipt">

        <div class="center">
            <div class="brand">{{ $currentTenant->name ?? 'VenResto' }}</div>

            <div class="outlet">
                {{ $currentOutlet->name ?? '-' }}
            </div>

            @if(!empty($currentOutlet?->address))
                <div class="small muted">
                    {{ $currentOutlet->address }}
                </div>
            @endif

            <div class="order-type">
                {{ $orderTypeLabel }}
            </div>
        </div>

        <div class="divider"></div>

        <div class="row">
            <span class="info-label">No Order</span>
            <span>{{ $order->code }}</span>
        </div>

        <div class="row">
            <span class="info-label">Tanggal</span>
            <span>{{ $order->created_at?->format('d/m/Y H:i') }}</span>
        </div>

        <div class="row">
            <span class="info-label">Meja</span>
            <span>
                @if($orderType === 'takeaway')
                    -
                @else
                    {{ $order->table_code ?? '-' }}
                @endif
            </span>
        </div>

        <div class="row">
            <span class="info-label">Customer</span>
            <span>{{ $order->customer_name ?? 'Guest' }}</span>
        </div>

        <div class="row">
            <span class="info-label">Kasir</span>
            <span>{{ $order->cashier?->name ?? '-' }}</span>
        </div>

        <div class="row">
            <span class="info-label">Pembayaran</span>
            <span>{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? '-')) }}</span>
        </div>

        <div class="divider"></div>

        <div class="section-title">DETAIL PESANAN</div>

        @foreach($order->items as $item)
            @php
                $lineTotal = (float) $item->qty * (float) $item->price;
            @endphp

            <div class="item">
                <div class="item-main">
                    <div class="item-name">
                        {{ $item->menuItem?->name ?? 'Menu' }}
                    </div>

                    <div class="item-total">
                        Rp {{ number_format($lineTotal, 0, ',', '.') }}
                    </div>
                </div>

                <div class="item-meta">
                    {{ number_format($item->qty, 0, ',', '.') }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                </div>

                @if($item->note)
                    <div class="item-note">
                        * {{ $item->note }}
                    </div>
                @endif
            </div>
        @endforeach

        <div class="divider"></div>

        <div class="summary">
            <div class="row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>

            <div class="row">
                <span>Discount</span>
                <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
            </div>

            <div class="row">
                <span>Service</span>
                <span>Rp {{ number_format($order->service, 0, ',', '.') }}</span>
            </div>

            <div class="row">
                <span>Tax</span>
                <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="divider-solid"></div>

        <div class="row grand">
            <span>TOTAL</span>
            <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
        </div>

        <div class="payment-box">
            <div class="row">
                <span>Dibayar</span>
                <span>Rp {{ number_format($paidAmount, 0, ',', '.') }}</span>
            </div>

            <div class="row">
                <span>Kembali</span>
                <span>Rp {{ number_format($changeAmount, 0, ',', '.') }}</span>
            </div>
        </div>

        @if(!empty($order->customer_note))
            <div class="divider"></div>

            <div class="small">
                <strong>Catatan:</strong><br>
                {{ $order->customer_note }}
            </div>
        @endif

        <div class="divider"></div>

        <div class="footer">
            <strong>Terima kasih 🙏</strong><br>
            Simpan struk ini sebagai bukti pembayaran.<br>
            Powered by VenResto POS
        </div>

    </div>
</div>

<div class="print-actions">
    <button type="button" class="btn-back" onclick="history.back()">
        Kembali
    </button>

    <button type="button" class="btn-print" onclick="window.print()">
        Print
    </button>
</div>

<script>
window.onload = function () {
    setTimeout(function () {
        window.print();
    }, 450);
};
</script>

</body>
</html>