@extends('layouts.admin')

@section('content')

<style>
:root{
    --primary:#0ea5e9;
    --bg:#f4f8fc;
    --card:#fff;
    --border:#e5e7eb;
}

body{
    background:var(--bg);
}

.page-title{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.card-glass{
    background:#fff;
    border:1px solid var(--border);
    border-radius:18px;
    padding:18px;
    box-shadow:0 8px 30px rgba(0,0,0,.04);
}

.badge-soft{
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
}

.badge-paid{background:#dcfce7;color:#166534;}
.badge-pending{background:#fef9c3;color:#854d0e;}
.badge-open{background:#dbeafe;color:#1e40af;}

.item-row{
    display:flex;
    justify-content:space-between;
    padding:10px 0;
    border-bottom:1px dashed #eee;
}

.summary-box{
    position:sticky;
    top:20px;
}
</style>

<div class="container">

    <!-- HEADER -->
    <div class="page-title">
        <div>
            <h4 class="mb-0">Order #{{ $order->code }}</h4>
            <small class="text-muted">
                {{ $order->created_at->format('d M Y H:i') }}
            </small>
        </div>

        <div>
            @if($order->status == 'paid')
                <span class="badge-soft badge-paid">Paid</span>
            @elseif($order->status == 'pending_payment')
                <span class="badge-soft badge-pending">Pending Payment</span>
            @else
                <span class="badge-soft badge-open">Open</span>
            @endif
        </div>
    </div>

    <div class="row g-3">

        <!-- LEFT: ORDER DETAIL -->
        <div class="col-md-8">

            <!-- CUSTOMER -->
            <div class="card-glass mb-3">
                <h6>Customer Info</h6>

                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Name</small>
                        <div class="fw-bold">
                            {{ $order->customer_name ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">Phone</small>
                        <div>{{ $order->customer_phone ?? '-' }}</div>
                    </div>
                </div>

                <div class="mt-2">
                    <small class="text-muted">Note</small>
                    <div>{{ $order->customer_note ?? '-' }}</div>
                </div>
            </div>

            <!-- ITEMS -->
            <div class="card-glass">

                <h6>Order Items</h6>

                @foreach($order->items as $item)
                    <div class="item-row">

                        <div>
                            <div class="fw-bold">
                                {{ $item->menuItem->name ?? 'Menu' }}
                            </div>
                            <small class="text-muted">
                                {{ $item->qty }} x Rp {{ number_format($item->price,0,',','.') }}
                            </small>
                        </div>

                        <div class="fw-bold">
                            Rp {{ number_format($item->qty * $item->price,0,',','.') }}
                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        <!-- RIGHT: SUMMARY -->
        <div class="col-md-4">

            <div class="card-glass summary-box">

                <h6>Payment Summary</h6>

                <div class="d-flex justify-content-between mt-2">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal,0,',','.') }}</span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Discount</span>
                    <span>- Rp {{ number_format($order->discount,0,',','.') }}</span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Tax</span>
                    <span>Rp {{ number_format($order->tax,0,',','.') }}</span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Service</span>
                    <span>Rp {{ number_format($order->service,0,',','.') }}</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->grand_total,0,',','.') }}</span>
                </div>

                <hr>

                <div class="mb-2">
                    <small class="text-muted">Payment Method</small>
                    <div class="fw-bold">
                        {{ $order->payment_method ?? '-' }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Cashier</small>
                    <div>{{ $order->cashier->name ?? '-' }}</div>
                </div>

                @if($order->status == 'pending_payment')
                    <button class="btn btn-primary w-100"
                            onclick="openPaymentModal()">
                        Mark as Paid
                    </button>
                @endif

            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="paymentModal">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">

      <div class="modal-header">
        <h5>Confirm Payment</h5>
      </div>

      <div class="modal-body">

        <select id="payment_method" class="form-select">
            <option value="cash">Cash</option>
            <option value="qris">QRIS</option>
            <option value="transfer">Transfer</option>
        </select>

      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" onclick="submitPayment()">Confirm</button>
      </div>

    </div>
  </div>
</div>

@endsection

<script>
function openPaymentModal(){
    new bootstrap.Modal(document.getElementById('paymentModal')).show();
}

function submitPayment(){

    fetch(`/${tenant}/admin/orders/{{ $order->id }}/payment`, {
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status:'paid',
            payment_method: document.getElementById('payment_method').value
        })
    })
    .then(res => res.json())
    .then(() => location.reload());
}
</script>