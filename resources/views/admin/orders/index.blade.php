@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
        <div>
            <h4 class="mb-0 font-bold text-lg">Menu Pesanan</h4>
            <small class="text-gray-500">Kelola transaksi & pembayaran</small>
        </div>

        <div>
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500" onchange="filterStatus(this)">
                <option value="">Semua Status</option>
                <option value="open">Open</option>
                <option value="pending_payment">Pending Payment</option>
                <option value="paid">Paid</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        @foreach($orders as $order)
        <div>

            <div class="bg-white border border-gray-200 rounded-2xl p-4 hover:-translate-y-1 hover:shadow-lg transition-all duration-200">

                <div class="flex flex-col gap-2.5">

                    <div class="flex justify-between items-start gap-3">
                        <div class="min-w-0">
                            <div class="font-bold text-truncate">
                                #{{ $order->code }}
                            </div>
                            <small class="text-gray-500">
                                {{ $order->created_at->format('d M Y') }}
                            </small>
                        </div>

                        <div class="shrink-0">
                            @if($order->status == 'paid')
                                <span class="px-2.5 py-1 rounded-full text-xs bg-green-100 text-green-800">Paid</span>
                            @elseif($order->status == 'pending_payment')
                                <span class="px-2.5 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Pending</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs bg-blue-100 text-blue-800">Open</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-between items-center gap-3">
                        <small class="text-gray-500 text-truncate">
                            {{ $order->customer_name ?? 'Guest' }} - {{ $order->table_code ?? '-' }}
                        </small>

                        @if($order->order_type === 'takeaway')
                            <span class="shrink-0 inline-flex items-center justify-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold whitespace-nowrap bg-green-100 text-green-800">
                                <i class="bi bi-bag-check"></i>
                                Takeaway
                            </span>
                        @else
                            <span class="shrink-0 inline-flex items-center justify-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold whitespace-nowrap bg-blue-100 text-blue-800">
                                <i class="bi bi-cup-hot"></i>
                                Dine In
                            </span>
                        @endif
                    </div>

                </div>

                <hr class="my-3">

                <div class="flex justify-between">
                    <div>
                        <small>Total</small>
                        <div class="font-bold">Rp {{ number_format($order->grand_total,0,',','.') }}</div>
                    </div>

                    <div class="text-right">
                        <small>Method</small>
                        <div>{{ $order->payment_method ?? '-' }}</div>
                    </div>
                </div>

                <div class="mt-3 flex gap-2">

                    <a href="{{ route('tenant.admin.orders.show', [$currentTenant->slug, $order->id]) }}"
                       class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-sm font-medium border border-sky-500 text-sky-600 hover:bg-sky-50 w-1/2">
                        Detail
                    </a>

                    @if($order->status == 'pending_payment')
                    <button class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-sky-500 text-white hover:bg-sky-600 w-1/2"
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

<!-- Native Tailwind Modal -->
<div id="payModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <!-- Backdrop -->
  <div class="fixed inset-0 bg-gray-900/50 transition-opacity" onclick="closePayModal()"></div>

  <!-- Modal Panel -->
  <div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
      <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
        
        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
              <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Update Payment</h3>
              
              <div class="mt-4 space-y-4">
                <input type="hidden" id="order_id">

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                  <select id="payment_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Paid Amount</label>
                  <input type="number"
                         id="paid_amount"
                         class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                         min="0"
                         value="0">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          <button type="button" onclick="submitPayment()" class="inline-flex w-full justify-center rounded-lg bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-500 sm:ml-3 sm:w-auto">
            Save
          </button>
          <button type="button" onclick="closePayModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
            Cancel
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

@endsection

<script>
function openPayModal(id, total){
    document.getElementById('order_id').value = id;
    document.getElementById('paid_amount').value = total;
    
    document.getElementById('payModal').classList.remove('hidden');
}

function closePayModal(){
    document.getElementById('payModal').classList.add('hidden');
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

        closePayModal();
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