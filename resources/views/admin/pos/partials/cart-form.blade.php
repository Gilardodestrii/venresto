<div class="mb-3">
    <label class="form-label fw-semibold">Tipe Pesanan</label>

    <div class="order-type-grid">
        <label class="order-type-box active">
            <input type="radio"
                   name="order_type"
                   value="dine_in"
                   checked
                   class="order-type-input">

            <span class="order-type-icon">
                <i class="bi bi-cup-hot"></i>
            </span>

            <span>
                <strong>Dine In</strong>
                <small>Makan di tempat</small>
            </span>
        </label>

        <label class="order-type-box">
            <input type="radio"
                   name="order_type"
                   value="takeaway"
                   class="order-type-input">

            <span class="order-type-icon">
                <i class="bi bi-bag-check"></i>
            </span>

            <span>
                <strong>Takeaway</strong>
                <small>Bungkus</small>
            </span>
        </label>
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Pilih Meja</label>
    <select name="table_code" class="form-select form-select-lg" required>
        <option value="">-- Pilih Meja --</option>
        @foreach($tables as $table)
            <option value="{{ $table->table_code }}">{{ $table->table_code }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Nama Customer</label>
    <input type="text" name="customer_name" class="form-control form-control-lg" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">No HP</label>
    <input type="text" name="customer_phone" class="form-control form-control-lg">
</div>

<hr>

<div id="cartContainer" class="cart-scroll"></div>

<hr>

<div class="summary-row">
    <span>Subtotal</span>
    <strong id="subtotalText">Rp 0</strong>
</div>

<div class="summary-row">
    <span>Discount</span>
    <input type="number"
           class="form-control w-50"
           name="discount"
           id="discountInput"
           value="0"
           min="0">
</div>

<div class="summary-row">
    <span>
        Service
        <small class="text-muted">
            ({{ $settings->service_enabled ? number_format((float)$settings->service_rate * 100, 1) . '%' : 'Off' }})
        </small>
    </span>
    <strong id="serviceText">Rp 0</strong>
</div>

<div class="summary-row">
    <span>
        Tax
        <small class="text-muted">
            ({{ $settings->tax_enabled ? number_format((float)$settings->tax_rate * 100, 1) . '%' : 'Off' }})
        </small>
    </span>
    <strong id="taxText">Rp 0</strong>
</div>

<input type="hidden" name="service" id="serviceInput" value="0">
<input type="hidden" name="tax" id="taxInput" value="0">

<hr>

<div class="summary-row summary-total">
    <span>Grand Total</span>
    <span id="grandTotalText">Rp 0</span>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Customer Note</label>
    <textarea name="customer_note" class="form-control form-control-lg"></textarea>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">Payment Method</label>

    <select name="payment_method"
            class="form-select form-select-lg"
            {{ count($paymentOptions) ? 'required' : 'disabled' }}>

        @forelse($paymentOptions as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
        @empty
            <option value="">Tidak ada payment aktif</option>
        @endforelse
    </select>

    @if(count($paymentOptions) === 0)
        <div class="small text-danger mt-2">
            Belum ada metode pembayaran yang aktif. Aktifkan Cash atau QRIS di Tenant Settings.
        </div>
    @endif
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">Nominal Dibayar</label>
    <input type="number"
           name="paid_amount"
           class="form-control form-control-lg"
           value="0"
           min="0">
</div>

<div class="d-grid gap-3">
    <button type="submit" name="action" value="hold" class="checkout-btn btn-hold">
        Simpan / Hold
    </button>

    <button type="submit" name="action" value="paid" class="checkout-btn btn-pay">
        Bayar
    </button>
</div>