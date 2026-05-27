<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Receipt</title>
<style>
body{font-family:monospace;width:80mm;margin:0 auto;padding:12px;background:#fff;color:#111}
.center{text-align:center}
.divider{border-top:1px dashed #999;margin:10px 0}
.row{display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px}
.grand{font-weight:bold;font-size:16px}
.note{font-size:11px;color:#666}
@media print{body{padding:0}}
</style>
</head>
<body>
<div class="center">
<h3>{{ $currentTenant->name ?? 'VenResto' }}</h3>
<div>{{ $currentOutlet->name ?? '-' }}</div>
</div>
<div class="divider"></div>
<div class="row"><span>Order</span><span>{{ $order->code }}</span></div>
<div class="row"><span>Meja</span><span>{{ $order->table_code }}</span></div>
<div class="row"><span>Kasir</span><span>{{ $order->cashier?->name ?? '-' }}</span></div>
<div class="divider"></div>
@foreach($order->items as $item)
<div style="margin-bottom:8px">
<div class="row">
<span>{{ $item->qty }}x {{ $item->menuItem?->name }}</span>
<span>Rp {{ number_format($item->qty * $item->price,0,',','.') }}</span>
</div>
@if($item->note)
<div class="note">{{ $item->note }}</div>
@endif
</div>
@endforeach
<div class="divider"></div>
<div class="row"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal,0,',','.') }}</span></div>
<div class="row"><span>Discount</span><span>Rp {{ number_format($order->discount,0,',','.') }}</span></div>
<div class="row"><span>Tax</span><span>Rp {{ number_format($order->tax,0,',','.') }}</span></div>
<div class="row"><span>Service</span><span>Rp {{ number_format($order->service,0,',','.') }}</span></div>
<div class="divider"></div>
<div class="row grand"><span>Total</span><span>Rp {{ number_format($order->grand_total,0,',','.') }}</span></div>
<div class="divider"></div>
<div class="center">Terima kasih 🙏</div>
<script>
window.onload=function(){setTimeout(()=>window.print(),400)}
</script>
</body>
</html>