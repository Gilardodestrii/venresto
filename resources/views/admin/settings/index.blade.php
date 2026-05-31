@extends('layouts.admin')

@section('page-title', 'Settings')

@section('content')

@php
    $payments = is_array($settings->payments_json ?? null)
        ? $settings->payments_json
        : (json_decode($settings->payments_json ?? '{}', true) ?: []);

    $cashEnabled = old('cash_enabled', $payments['cash_enabled'] ?? false);
    $qrisEnabled = old('qris_enabled', $payments['qris_enabled'] ?? false);
    $qrisMode = old('qris_mode', $payments['qris_mode'] ?? 'snap');

    $qrisSnap = $payments['qris_snap'] ?? [];
    $qrisStatic = $payments['qris_static'] ?? [];
@endphp

<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Tenant Settings</h3>
            <div class="text-muted">Kelola pajak, service charge, metode pembayaran, kitchen ticket, inventory, dan QRIS static.</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tenant.admin.settings.update', $currentTenant->slug) }}">
        @csrf

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-5 h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Tax Settings</h5>
                        <div class="text-muted small mb-4">Atur pajak penjualan tenant.</div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="tax_enabled" value="1" id="tax_enabled" {{ $settings->tax_enabled ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="tax_enabled">Aktifkan Pajak</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tax Rate</label>
                            <input type="number" step="0.001" min="0" max="1" name="tax_rate" value="{{ old('tax_rate', $settings->tax_rate) }}" class="form-control rounded-4">
                            <div class="form-text">Contoh: 0.11 untuk 11%.</div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="tax_inclusive" value="1" id="tax_inclusive" {{ $settings->tax_inclusive ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="tax_inclusive">Harga sudah termasuk pajak</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-5 h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Service Charge</h5>
                        <div class="text-muted small mb-4">Atur biaya layanan restoran.</div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="service_enabled" value="1" id="service_enabled" {{ $settings->service_enabled ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="service_enabled">Aktifkan Service Charge</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Service Rate</label>
                            <input type="number" step="0.001" min="0" max="1" name="service_rate" value="{{ old('service_rate', $settings->service_rate) }}" class="form-control rounded-4">
                            <div class="form-text">Contoh: 0.05 untuk 5%.</div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="service_inclusive" value="1" id="service_inclusive" {{ $settings->service_inclusive ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="service_inclusive">Harga sudah termasuk service</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-5">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Payment Methods</h5>
                        <div class="text-muted small mb-4">
                            Atur metode pembayaran yang muncul di POS cashier.
                        </div>

                        <div class="row g-4">
                            <div class="col-lg-4">
                                <div class="p-4 rounded-5 border bg-light h-100">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="cash_enabled"
                                            value="1"
                                            id="cash_enabled"
                                            {{ $cashEnabled ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="cash_enabled">
                                            Cash
                                        </label>
                                    </div>
                                    <div class="text-muted small">
                                        Jika aktif, POS akan menampilkan metode pembayaran Cash.
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div class="p-4 rounded-5 border bg-light h-100">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="qris_enabled"
                                            value="1"
                                            id="qris_enabled"
                                            {{ $qrisEnabled ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="qris_enabled">
                                            QRIS
                                        </label>
                                    </div>

                                    <label class="form-label fw-semibold">QRIS Mode</label>
                                    <select name="qris_mode"
                                            id="qris_mode"
                                            class="form-select rounded-4 mb-3">
                                        <option value="snap" {{ $qrisMode === 'snap' ? 'selected' : '' }}>
                                            QRIS Snap
                                        </option>
                                        <option value="static" {{ $qrisMode === 'static' ? 'selected' : '' }}>
                                            QRIS Static
                                        </option>
                                    </select>

                                    <div class="text-muted small">
                                        Jika QRIS aktif, POS hanya akan menampilkan QRIS sesuai mode yang dipilih.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div id="qrisSnapBox">
                            <h6 class="fw-bold mb-3">QRIS Snap Configuration</h6>

                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <label class="form-label">Client Key</label>
                                    <input type="text"
                                        name="qris_snap_client_key"
                                        value="{{ old('qris_snap_client_key', $qrisSnap['client_key'] ?? '') }}"
                                        class="form-control rounded-4">
                                </div>

                                <div class="col-lg-4">
                                    <label class="form-label">Server Key</label>
                                    <input type="text"
                                        name="qris_snap_server_key"
                                        value="{{ old('qris_snap_server_key', $qrisSnap['server_key'] ?? '') }}"
                                        class="form-control rounded-4">
                                </div>

                                <div class="col-lg-4">
                                    <label class="form-label">Expiry Minutes</label>
                                    <input type="number"
                                        name="qris_snap_expiry_minutes"
                                        min="1"
                                        max="1440"
                                        value="{{ old('qris_snap_expiry_minutes', $qrisSnap['expiry_minutes'] ?? 15) }}"
                                        class="form-control rounded-4">
                                </div>
                            </div>
                        </div>

                        <div id="qrisStaticBox">
                            <h6 class="fw-bold mb-3">QRIS Static Configuration</h6>
                            <div class="text-muted small mb-4">
                                Paste payload QRIS static merchant. Sistem akan generate QRIS nominal otomatis saat payment method QRIS Static dipilih.
                            </div>
                            <div class="mb-3">
                                <label class="form-label">QR Payload</label>
                                <textarea name="qris_static_payload"
                                        rows="5"
                                        class="form-control rounded-4"
                                        placeholder="000201010211...">{{ old('qris_static_payload', $qrisStatic['qr_payload'] ?? $settings->qris_static_payload ?? '') }}</textarea>
                            </div>
                            <div class="form-text">
                                Ambil payload dari QRIS static merchant. Jangan paste gambar, tapi string payload QRIS.
                            </div>

                            <div class="mb-0">
                                <label class="form-label">QR Image URL</label>
                                <input type="url"
                                    name="qris_static_image_url"
                                    value="{{ old('qris_static_image_url', $qrisStatic['qr_image_url'] ?? '') }}"
                                    class="form-control rounded-4"
                                    placeholder="https://example.com/qris.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-5 h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Operational Behavior</h5>
                        <div class="text-muted small mb-4">Atur perilaku kitchen dan inventory.</div>

                        <div class="mb-4">
                            <label class="form-label">Stock Deduct On</label>
                            <select name="stock_deduct_on" class="form-select rounded-4">
                                <option value="paid" {{ $settings->stock_deduct_on === 'paid' ? 'selected' : '' }}>Saat order dibayar</option>
                                <option value="open" {{ $settings->stock_deduct_on === 'open' ? 'selected' : '' }}>Saat order dibuat/open</option>
                            </select>
                            <div class="form-text">Rekomendasi: paid untuk mencegah stok berkurang pada order batal.</div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="kitchen_ticket_on_open_for_cash" value="1" id="kitchen_ticket_on_open_for_cash" {{ $settings->kitchen_ticket_on_open_for_cash ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="kitchen_ticket_on_open_for_cash">Kitchen ticket langsung muncul untuk cash order</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button class="btn btn-primary rounded-4 px-5 py-2">
                <i class="bi bi-save me-1"></i>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const qrisEnabled = document.getElementById('qris_enabled');
    const qrisMode = document.getElementById('qris_mode');
    const qrisSnapBox = document.getElementById('qrisSnapBox');
    const qrisStaticBox = document.getElementById('qrisStaticBox');

    function syncQrisFields() {
        const enabled = qrisEnabled.checked;
        const mode = qrisMode.value;

        qrisMode.disabled = !enabled;

        qrisSnapBox.style.display = enabled && mode === 'snap' ? 'block' : 'none';
        qrisStaticBox.style.display = enabled && mode === 'static' ? 'block' : 'none';
    }

    qrisEnabled.addEventListener('change', syncQrisFields);
    qrisMode.addEventListener('change', syncQrisFields);

    syncQrisFields();
});
</script>
@endsection