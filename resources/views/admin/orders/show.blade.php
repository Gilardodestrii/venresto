@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h4 class="mb-0">Order #{{ $order->code }}</h4>
            <small class="text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</small>
        </div>
        <div>
            @if($order->status == 'paid')
                <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800">Paid</span>
            @elseif($order->status == 'pending_payment')
                <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Pending Payment</span>
            @elseif($order->status == 'void')
                <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-800">Void</span>
            @else
                <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800">Open</span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 shadow-sm mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2 space-y-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h6 class="font-semibold text-gray-900 mb-4">Customer Info</h6>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <small class="text-gray-500">Name</small>
                        <div class="font-bold">{{ $order->customer_name ?? '-' }}</div>
                    </div>
                    <div>
                        <small class="text-gray-500">Phone</small>
                        <div>{{ $order->customer_phone ?? '-' }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <small class="text-gray-500">Note</small>
                        <div>{{ $order->customer_note ?? '-' }}</div>
                    </div>
                    <div>
                        @if($order->order_type === 'takeaway')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                <i class="bi bi-bag-check"></i> Takeaway
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                <i class="bi bi-cup-hot"></i> Dine In
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h6 class="font-semibold text-gray-900 mb-4">Order Items</h6>
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center py-3 border-b border-dashed border-gray-200 last:border-0">
                        <div>
                            <div class="font-bold">{{ $item->menuItem->name ?? 'Menu' }}</div>
                            <small class="text-gray-500">{{ $item->qty }} x Rp {{ number_format($item->price,0,',','.') }}</small>
                        </div>
                        <div class="font-bold">Rp {{ number_format($item->qty * $item->price,0,',','.') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm sticky top-5">
                <h6 class="font-semibold text-gray-900 mb-4">Payment Summary</h6>
                <div class="flex justify-between mt-2"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal,0,',','.') }}</span></div>
                <div class="flex justify-between"><span>Discount</span><span>- Rp {{ number_format($order->discount,0,',','.') }}</span></div>
                <div class="flex justify-between"><span>Tax</span><span>Rp {{ number_format($order->tax,0,',','.') }}</span></div>
                <div class="flex justify-between"><span>Service</span><span>Rp {{ number_format($order->service,0,',','.') }}</span></div>
                <hr class="my-3">
                <div class="flex justify-between font-bold"><span>Total</span><span>Rp {{ number_format($order->grand_total,0,',','.') }}</span></div>
                <hr class="my-3">
                <div class="mb-2"><small class="text-gray-500">Payment Method</small><div class="font-bold">{{ $order->payment_method ?? '-' }}</div></div>
                <div class="mb-3"><small class="text-gray-500">Cashier</small><div>{{ $order->cashier->name ?? '-' }}</div></div>

                @if($order->status != 'paid' && $order->status != 'void')
                    <button class="w-full mb-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg font-medium transition-colors" onclick="openPaymentModal()">
                        Bayar Sekarang
                    </button>
                @endif


                @if($order->status == 'paid')
                    <a href="{{ route('tenant.admin.orders.receipt', [$currentTenant->slug, $order->id]) }}"
                        target="_blank"
                        class="w-full mb-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors inline-flex items-center justify-center gap-2">
                            <i class="bi bi-printer"></i>
                            Print Receipt
                    </a>
                    <form method="POST"
                          action="{{ route('tenant.admin.orders.void', [$currentTenant->slug, $order->id]) }}"
                          onsubmit="return confirm('Void order ini? Stock akan dikembalikan.')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                            Void Order & Restore Stock
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="paymentModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePaymentModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md pointer-events-auto">
            <div class="flex justify-between items-center border-b border-gray-200 px-5 py-4">
                <h5 class="font-semibold">Konfirmasi Pembayaran</h5>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="px-5 py-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select id="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none">
                        <option value="cash">Cash</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Dibayar</label>
                    <input id="paid_amount" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none" value="{{ (int) $order->grand_total }}" min="0">
                </div>
                <div id="paymentError" class="hidden bg-red-50 border border-red-200 text-red-800 rounded-lg p-3 text-sm"></div>
            </div>
            <div class="flex justify-end gap-2 border-t border-gray-200 px-5 py-4">
                <button onclick="closePaymentModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-medium transition-colors">Batal</button>
                <button onclick="submitPayment()" class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg font-medium transition-colors">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

<script>
function openPaymentModal(){document.getElementById('paymentModal').classList.remove('hidden');}
function closePaymentModal(){document.getElementById('paymentModal').classList.add('hidden');}
function submitPayment(){
    const errorBox=document.getElementById('paymentError');
    errorBox.classList.add('hidden');
    errorBox.classList.remove('flex');
    fetch('{{ route('tenant.admin.orders.updatePayment', [$currentTenant->slug, $order->id]) }}',{
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({payment_method:document.getElementById('payment_method').value,paid_amount:document.getElementById('paid_amount').value})
    }).then(async res=>{const data=await res.json();if(!res.ok){console.error('[Payment] Error:', data);throw data;}return data;})
    .then(()=>location.reload())
    .catch(err=>{
        console.error('[Payment] Full error:', err);
        let msg='Pembayaran gagal.';
        if(err.message){
            msg=err.message;
        }else if(err.errors){
            msg=Object.values(err.errors)[0][0];
        }else if(err.exception){
            msg=err.exception;
        }
        errorBox.innerHTML=msg;
        errorBox.classList.remove('hidden');
        errorBox.classList.add('flex');
    });
}
</script>

@endsection
