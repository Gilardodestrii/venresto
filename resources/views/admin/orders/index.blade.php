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
    background: var(--bg);
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.order-card{
    background: var(--card);
    border:1px solid var(--border);
    border-radius:18px;
    padding:16px;
    transition:.2s;
}

.order-card:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,.06);
}

.badge-soft{
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
}

.order-head{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.order-head-top,
.order-head-bottom{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
}

.order-head-bottom{
    align-items:center;
}

.order-status-wrap{
    flex-shrink:0;
}

.order-type-badge{
    flex-shrink:0;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:4px;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    white-space:nowrap;
}

.order-type-dinein{
    background:#dbeafe;
    color:#1d4ed8;
}

.order-type-takeaway{
    background:#dcfce7;
    color:#166534;
}

.min-w-0{
    min-width:0;
}

@media(max-width:575px){
    .order-card{
        padding:14px;
    }

    .order-head-top,
    .order-head-bottom{
        gap:8px;
    }

    .order-type-badge,
    .badge-soft{
        font-size:11px;
        padding:5px 9px;
    }
}

.badge-paid{background:#dcfce7;color:#166534;}
.badge-pending{background:#fef9c3;color:#854d0e;}
.badge-open{background:#dbeafe;color:#1e40af;}
</style>

<div class="container">

    <div class="page-header">
        <div>
            <h4 class="mb-0">Menu Pesanan</h4>
            <small class="text-muted">Kelola transaksi & pembayaran</small>
        </div>

        <div>
            <select class="form-select" onchange="filterStatus(this)">
                <option value="">Semua Status</option>
                <option value="open">Open</option>
                <option value="pending_payment">Pending Payment</option>
                <option value="paid">Paid</option>
            </select>
        </div>
    </div>

    <div class="row g-3">

        @foreach($orders as $order)
        <div class="col-md-6 col-lg-4">

            <div class="order-card">

                <div class="order-head">

                    <div class="order-head-top">
                        <div class="min-w-0">
                            <div class="fw-bold text-truncate">
                                #{{ $order->code }}
                            </div>
                            <small class="text-muted">
                                {{ $order->created_at->format('d M Y') }}
                            </small>
                        </div>

                        <div class="order-status-wrap">
                            @if($order->status == 'paid')
                                <span class="badge-soft badge-paid">Paid</span>
                            @elseif($order->status == 'pending_payment')
                                <span class="badge-soft badge-pending">Pending</span>
                            @else
                                <span class="badge-soft badge-open">Open</span>
                            @endif
                        </div>
                    </div>

                    <div class="order-head-bottom">
                        <small class="text-muted text-truncate">
                            {{ $order->customer_name ?? 'Guest' }} - {{ $order->table_code ?? '-' }}
                        </small>

                        @if($order->order_type === 'takeaway')
                            <span class="order-type-badge order-type-takeaway">
                                <i class="bi bi-bag-check me-1"></i>
                                Takeaway
                            </span>
                        @else
                            <span class="order-type-badge order-type-dinein">
                                <i class="bi bi-cup-hot me-1"></i>
                                Dine In
                            </span>
                        @endif
                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <div>
                        <small>Total</small>
                        <div class="fw-bold">Rp {{ number_format($order->grand_total,0,',','.') }}</div>
                    </div>

                    <div class="text-end">
                        <small>Method</small>
                        <div>{{ $order->payment_method ?? '-' }}</div>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">

                    <a href="{{ route('tenant.admin.orders.show', [$currentTenant->slug, $order->id]) }}"
                       class="btn btn-sm btn-outline-primary w-50">
                        Detail
                    </a>

                    @if($order->status == 'pending_payment')
                    <button class="btn btn-sm btn-primary w-50"
                            onclick="openPayModal({{ $order->id }}, {{ $order->grand_total }})">
                        Pay
                    </button>
                    @endif

                </div>

            </div>

        </div>
        @endforeach

    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>

</div>

<div class="modal fade" id="payModal">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">

      <div class="modal-header">
        <h5>Update Payment</h5>
      </div>

      <div class="modal-body">

        <input type="hidden" id="order_id">

        <label>Payment Method</label>
        <select id="payment_method" class="form-select mb-3">
            <option value="cash">Cash</option>
            <option value="qris">QRIS</option>
        </select>

        <label>Paid Amount</label>
        <input type="number"
               id="paid_amount"
               class="form-control"
               min="0"
               value="0">

      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" onclick="submitPayment()">Save</button>
      </div>

    </div>
  </div>
</div>

@endsection

<script>
function openPayModal(id, total){
    document.getElementById('order_id').value = id;
    document.getElementById('paid_amount').value = total;

    new bootstrap.Modal(document.getElementById('payModal')).show();
}

function submitPayment(){

    let id = document.getElementById('order_id').value;

    fetch(`/{{ $currentTenant->slug }}/admin/orders/${id}/payment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            payment_method: document.getElementById('payment_method').value,
            paid_amount: document.getElementById('paid_amount').value
        })
    })
    .then(async res => {

        const data = await res.json();

        if (!res.ok) {
            alert(data.message || 'Payment gagal');
            return;
        }

        location.reload();
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan');
    });

}

function filterStatus(el){
    let status = el.value;
    let url = new URL(window.location.href);
    if(status){
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}
</script>