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

                <div class="d-flex justify-content-between">
                    <div>
                        <div class="fw-bold">#{{ $order->code }} {{ $order->created_at->format('d M Y') }}</div>
                        <small class="text-muted">
                            {{ $order->customer_name ?? 'Guest' }} - {{ $order->table_code ?? '-' }}
                        </small>
                    </div>

                    <div>
                        @if($order->status == 'paid')
                            <span class="badge-soft badge-paid">Paid</span>
                        @elseif($order->status == 'pending_payment')
                            <span class="badge-soft badge-pending">Pending</span>
                        @else
                            <span class="badge-soft badge-open">Open</span>
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
                            onclick="openPayModal({{ $order->id }})">
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

        <label>Status</label>
        <select id="status" class="form-select mb-2">
            <option value="paid">Paid</option>
            <option value="pending_payment">Pending Payment</option>
        </select>

        <label>Payment Method</label>
        <select id="payment_method" class="form-select">
            <option value="cash">Cash</option>
            <option value="qris">QRIS</option>
            <option value="transfer">Transfer</option>
        </select>

      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" onclick="submitPayment()">Save</button>
      </div>

    </div>
  </div>
</div>

@endsection

<script>
function openPayModal(id){
    document.getElementById('order_id').value = id;
    new bootstrap.Modal(document.getElementById('payModal')).show();
}

function submitPayment(){

    let id = document.getElementById('order_id').value;

    fetch(`/${tenant}/admin/orders/${id}/payment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: document.getElementById('status').value,
            payment_method: document.getElementById('payment_method').value
        })
    })
    .then(res => res.json())
    .then(res => {
        location.reload();
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