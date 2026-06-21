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

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold text-xl text-gray-900 mb-1">Tenant Settings</h3>
            <div class="text-gray-500 text-sm">Kelola pajak, service charge, metode pembayaran, kitchen ticket, inventory, dan QRIS static.</div>
        </div>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 mb-4 bg-green-50 border border-green-200 text-green-800 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="px-4 py-3 mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
            <ul class="mb-0 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tenant.admin.settings.update', $currentTenant->slug) }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Tax Settings --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 h-fit">
                <h5 class="font-bold text-gray-900 mb-1">Tax Settings</h5>
                <div class="text-gray-500 text-sm mb-4">Atur pajak penjualan tenant.</div>

                <div class="flex items-center gap-3 mb-3">
                    <input class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                           type="checkbox" name="tax_enabled" value="1" id="tax_enabled"
                           {{ $settings->tax_enabled ? 'checked' : '' }}>
                    <label class="font-semibold text-gray-800" for="tax_enabled">Aktifkan Pajak</label>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tax Rate</label>
                    <input type="number" step="0.001" min="0" max="1"
                           name="tax_rate"
                           value="{{ old('tax_rate', $settings->tax_rate) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <div class="text-xs text-gray-500 mt-1">Contoh: 0.11 untuk 11%.</div>
                </div>

                <div class="flex items-center gap-3">
                    <input class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                           type="checkbox" name="tax_inclusive" value="1" id="tax_inclusive"
                           {{ $settings->tax_inclusive ? 'checked' : '' }}>
                    <label class="font-semibold text-gray-800" for="tax_inclusive">Harga sudah termasuk pajak</label>
                </div>
            </div>

            {{-- Service Charge --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 h-fit">
                <h5 class="font-bold text-gray-900 mb-1">Service Charge</h5>
                <div class="text-gray-500 text-sm mb-4">Atur biaya layanan restoran.</div>

                <div class="flex items-center gap-3 mb-3">
                    <input class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                           type="checkbox" name="service_enabled" value="1" id="service_enabled"
                           {{ $settings->service_enabled ? 'checked' : '' }}>
                    <label class="font-semibold text-gray-800" for="service_enabled">Aktifkan Service Charge</label>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Service Rate</label>
                    <input type="number" step="0.001" min="0" max="1"
                           name="service_rate"
                           value="{{ old('service_rate', $settings->service_rate) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <div class="text-xs text-gray-500 mt-1">Contoh: 0.05 untuk 5%.</div>
                </div>

                <div class="flex items-center gap-3">
                    <input class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                           type="checkbox" name="service_inclusive" value="1" id="service_inclusive"
                           {{ $settings->service_inclusive ? 'checked' : '' }}>
                    <label class="font-semibold text-gray-800" for="service_inclusive">Harga sudah termasuk service</label>
                </div>
            </div>

        </div>

        {{-- Payment Methods --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h5 class="font-bold text-gray-900 mb-1">Payment Methods</h5>
            <div class="text-gray-500 text-sm mb-4">
                Atur metode pembayaran yang muncul di POS cashier.
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mb-4">
                <div class="lg:col-span-4">
                    <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50 h-full">
                        <div class="flex items-center gap-3 mb-2">
                            <input class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   type="checkbox" name="cash_enabled" value="1" id="cash_enabled"
                                   {{ $cashEnabled ? 'checked' : '' }}>
                            <label class="font-bold text-gray-800" for="cash_enabled">Cash</label>
                        </div>
                        <div class="text-gray-500 text-sm">
                            Jika aktif, POS akan menampilkan metode pembayaran Cash.
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8">
                    <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50 h-full">
                        <div class="flex items-center gap-3 mb-3">
                            <input class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   type="checkbox" name="qris_enabled" value="1" id="qris_enabled"
                                   {{ $qrisEnabled ? 'checked' : '' }}>
                            <label class="font-bold text-gray-800" for="qris_enabled">QRIS</label>
                        </div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">QRIS Mode</label>
                        <select name="qris_mode" id="qris_mode"
                                class="w-full px-3 py-2 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 mb-3">
                            <option value="snap" {{ $qrisMode === 'snap' ? 'selected' : '' }}>QRIS Snap</option>
                            <option value="static" {{ $qrisMode === 'static' ? 'selected' : '' }}>QRIS Static</option>
                        </select>

                        <div class="text-gray-500 text-sm">
                            Jika QRIS aktif, POS hanya akan menampilkan QRIS sesuai mode yang dipilih.
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 my-5 pt-5">

                <div id="qrisSnapBox" class="{{ ($qrisEnabled && $qrisMode === 'snap') ? '' : 'hidden' }}">
                    <h6 class="font-bold text-gray-800 mb-3">QRIS Snap Configuration</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Client Key</label>
                            <input type="text" name="qris_snap_client_key"
                                   value="{{ old('qris_snap_client_key', $qrisSnap['client_key'] ?? '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Server Key</label>
                            <input type="text" name="qris_snap_server_key"
                                   value="{{ old('qris_snap_server_key', $qrisSnap['server_key'] ?? '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Expiry Minutes</label>
                            <input type="number" name="qris_snap_expiry_minutes"
                                   min="1" max="1440"
                                   value="{{ old('qris_snap_expiry_minutes', $qrisSnap['expiry_minutes'] ?? 15) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                </div>

                <div id="qrisStaticBox" class="{{ ($qrisEnabled && $qrisMode === 'static') ? '' : 'hidden' }}">
                    <h6 class="font-bold text-gray-800 mb-3">QRIS Static Configuration</h6>
                    <div class="text-gray-500 text-sm mb-4">
                        Paste payload QRIS static merchant. Sistem akan generate QRIS nominal otomatis saat payment method QRIS Static dipilih.
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">QR Payload</label>
                        <textarea name="qris_static_payload" rows="5"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                  placeholder="000201010211...">{{ old('qris_static_payload', $qrisStatic['qr_payload'] ?? $settings->qris_static_payload ?? '') }}</textarea>
                        <div class="text-xs text-gray-500 mt-1">Ambil payload dari QRIS static merchant. Jangan paste gambar, tapi string payload QRIS.</div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">QR Image URL</label>
                        <input type="url" name="qris_static_image_url"
                               value="{{ old('qris_static_image_url', $qrisStatic['qr_image_url'] ?? '') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                               placeholder="https://example.com/qris.png">
                    </div>
                </div>

            </div>
        </div>

        {{-- Operational Behavior --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h5 class="font-bold text-gray-900 mb-1">Operational Behavior</h5>
            <div class="text-gray-500 text-sm mb-4">Atur perilaku kitchen dan inventory.</div>

            <div class="mb-4 max-w-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Stock Deduct On</label>
                <select name="stock_deduct_on"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500">
                    <option value="paid" {{ $settings->stock_deduct_on === 'paid' ? 'selected' : '' }}>Saat order dibayar</option>
                    <option value="open" {{ $settings->stock_deduct_on === 'open' ? 'selected' : '' }}>Saat order dibuat/open</option>
                </select>
                <div class="text-xs text-gray-500 mt-1">Rekomendasi: paid untuk mencegah stok berkurang pada order batal.</div>
            </div>

            <div class="flex items-center gap-3">
                <input class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                       type="checkbox" name="kitchen_ticket_on_open_for_cash" value="1"
                       id="kitchen_ticket_on_open_for_cash"
                       {{ $settings->kitchen_ticket_on_open_for_cash ? 'checked' : '' }}>
                <label class="font-semibold text-gray-800" for="kitchen_ticket_on_open_for_cash">
                    Kitchen ticket langsung muncul untuk cash order
                </label>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors">
                <i class="bi bi-save mr-1"></i>
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
