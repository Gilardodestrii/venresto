{{-- Order Type Selection --}}
<div class="mb-4">
    <label class="block font-semibold text-sm text-slate-700 mb-2">Tipe Pesanan</label>
    <div class="grid grid-cols-2 gap-2.5">
        <label class="order-type-box relative flex cursor-pointer items-center gap-2.5 p-3 rounded-2xl border border-slate-200 bg-white/92 hover:border-sky-500 hover:-translate-y-0.5 active:border-sky-500 active:bg-sky-500/10 active:shadow-xl active:shadow-sky-500/20 transition">
            <input type="radio" name="order_type" value="dine_in" checked class="hidden peer">
            <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-900 peer-checked:bg-sky-500 peer-checked:text-white transition-colors">
                <i class="bi bi-cup-hot"></i>
            </span>
            <span class="flex flex-col">
                <strong class="text-sm font-medium">Dine In</strong>
                <small class="text-xs text-slate-500">Makan di tempat</small>
            </span>
        </label>
        <label class="order-type-box relative flex cursor-pointer items-center gap-2.5 p-3 rounded-2xl border border-slate-200 bg-white/92 hover:border-sky-500 hover:-translate-y-0.5 active:border-sky-500 active:bg-sky-500/10 active:shadow-xl active:shadow-sky-500/20 transition">
            <input type="radio" name="order_type" value="takeaway" class="hidden peer">
            <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-900 peer-checked:bg-sky-500 peer-checked:text-white transition-colors">
                <i class="bi bi-bag-check"></i>
            </span>
            <span class="flex flex-col">
                <strong class="text-sm font-medium">Takeaway</strong>
                <small class="text-xs text-slate-500">Bungkus</small>
            </span>
        </label>
    </div>
</div>

{{-- Table Selection --}}
<div class="mb-4">
    <label class="block font-semibold text-sm text-slate-700 mb-2">Pilih Meja</label>
    <select name="table_code" class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" required>
        <option value="">-- Pilih Meja --</option>
        @foreach($tables as $table)
            <option value="{{ $table->table_code }}">{{ $table->table_code }}</option>
        @endforeach
    </select>
</div>

{{-- Customer Name --}}
<div class="mb-4">
    <label class="block font-semibold text-sm text-slate-700 mb-2">Nama Customer</label>
    <input type="text" name="customer_name" class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" required>
</div>

{{-- Customer Phone --}}
<div class="mb-4">
    <label class="block font-semibold text-sm text-slate-700 mb-2">No HP</label>
    <input type="text" name="customer_phone" class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
</div>

<hr class="border-slate-200 my-4">

{{-- Cart Items --}}
<div id="cartContainer" class="cart-scroll max-h-none overflow-visible"></div>

<hr class="border-slate-200 my-4">

{{-- Subtotal --}}
<div class="flex justify-between items-center mb-2.5 gap-3 summary-row">
    <span class="text-slate-600">Subtotal</span>
    <strong id="subtotalText" class="text-slate-900">Rp 0</strong>
</div>

{{-- Discount --}}
<div class="flex justify-between items-center mb-2.5 gap-3 summary-row">
    <span class="text-slate-600">Discount</span>
    <input type="number"
           class="w-1/2 h-10 px-3 rounded-lg border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
           name="discount"
           id="discountInput"
           value="0"
           min="0">
</div>

{{-- Service --}}
<div class="flex justify-between items-center mb-2.5 gap-3 summary-row">
    <span class="text-slate-600">
        Service
        <small class="text-slate-400">
            ({{ $settings->service_enabled ? number_format((float)$settings->service_rate * 100, 1) . '%' : 'Off' }})
        </small>
    </span>
    <strong id="serviceText" class="text-slate-900">Rp 0</strong>
</div>

{{-- Tax --}}
<div class="flex justify-between items-center mb-2.5 gap-3 summary-row">
    <span class="text-slate-600">
        Tax
        <small class="text-slate-400">
            ({{ $settings->tax_enabled ? number_format((float)$settings->tax_rate * 100, 1) . '%' : 'Off' }})
        </small>
    </span>
    <strong id="taxText" class="text-slate-900">Rp 0</strong>
</div>

<input type="hidden" name="service" id="serviceInput" value="0">
<input type="hidden" name="tax" id="taxInput" value="0">

<hr class="border-slate-200 my-4">

{{-- Grand Total --}}
<div class="flex justify-between items-center mb-4 text-xl font-extrabold summary-row summary-total">
    <span>Grand Total</span>
    <span id="grandTotalText">Rp 0</span>
</div>

{{-- Customer Note --}}
<div class="mb-4">
    <label class="block font-semibold text-sm text-slate-700 mb-2">Customer Note</label>
    <textarea name="customer_note" class="w-full h-24 px-4 py-3 rounded-xl border border-slate-200 bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 resize-none"></textarea>
</div>

{{-- Payment Method --}}
<div class="mb-4">
    <label class="block font-semibold text-sm text-slate-700 mb-2">Payment Method</label>

    <select name="payment_method"
            class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
            {{ count($paymentOptions) ? 'required' : 'disabled' }}>

        @forelse($paymentOptions as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
        @empty
            <option value="">Tidak ada payment aktif</option>
        @endforelse
    </select>

    @if(count($paymentOptions) === 0)
        <div class="text-xs text-red-500 mt-1.5">
            Belum ada metode pembayaran yang aktif. Aktifkan Cash atau QRIS di Tenant Settings.
        </div>
    @endif
</div>

{{-- Paid Amount --}}
<div class="mb-4">
    <label class="block font-semibold text-sm text-slate-700 mb-2">Nominal Dibayar</label>
    <input type="number"
           name="paid_amount"
           class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500"
           value="0"
           min="0">
</div>

{{-- Action Buttons --}}
<div class="grid gap-3 mb-2">
    <button type="submit" name="action" value="hold" class="h-14 rounded-2xl font-bold bg-slate-100 text-slate-700 border-none transition hover:bg-slate-200 checkout-btn btn-hold">
        Simpan / Hold
    </button>

    <button type="submit" name="action" value="paid" class="h-14 rounded-2xl font-bold bg-sky-500 text-white border-none transition hover:bg-sky-600 shadow-lg checkout-btn btn-pay">
        Bayar
    </button>
</div>


