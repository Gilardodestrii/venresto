@extends('layouts.marketing')

@section('title','Harga VenResto — POS & QR Menu untuk Restoran')
@section('meta_description','Pilih paket VenResto: Starter, Pro, atau Enterprise. Coba gratis 7 hari. Hemat 15% untuk pembayaran tahunan.')

@section('content')
<section class="hero border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 py-12 text-center">
    <span class="inline-flex items-center px-3 py-1 rounded-full bg-sky-100 text-sky-700 text-xs font-medium">Trial 7 hari • Tanpa kartu kredit</span>
    <h1 class="text-4xl font-bold mt-4">Pilih paket sesuai skala restoran Anda</h1>
    <p class="text-gray-500 mt-2">Bayar bulanan atau hemat <strong>15%</strong> dengan paket tahunan.</p>

    {{-- Toggle Billing --}}
    <div class="inline-flex items-center gap-3 border border-gray-200 rounded-full px-3 py-2 mt-6">
      <span class="text-sm text-gray-500">Bulanan</span>
      <div class="flex items-center m-0">
        <input class="w-4 h-4 text-sky-500 bg-gray-100 border-gray-300 rounded focus:ring-sky-500" type="checkbox" id="toggle-annual" aria-label="Toggle Tahunan">
      </div>
      <span class="text-sm"><strong>Tahunan</strong> <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200 ml-1">Hemat 15%</span></span>
    </div>
  </div>
</section>

<section>
  <div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">

      {{-- STARTER --}}
      <div class="h-full p-6 border border-gray-200 rounded-2xl shadow-sm bg-white flex flex-col">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold m-0">Starter</h3>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">Untuk mulai</span>
        </div>
        <p class="text-gray-500 mt-2">Cocok untuk warung/kafe kecil yang baru go-digital.</p>

        <div class="my-4">
          <div>
            <span class="text-4xl font-bold" data-monthly="Rp 199.000" data-yearly="Rp 169.000">Rp 199.000</span>
            <span class="text-gray-500">/bulan</span>
          </div>
          <div class="text-sm text-gray-500" id="starter-note">Tagihan tahunan: Rp 2.028.000</div>
        </div>

        <ul class="list-none space-y-2 text-sm text-gray-500 flex-grow-1">
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>1 outlet, 10 meja</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>QR Menu & pesanan ke dapur</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>POS kasir dasar (hold, diskon)</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>Laporan harian dasar</li>
          <li class="flex items-center"><i class="bi bi-dash text-gray-400 mr-2"></i>Inventory & recipe sederhana</li>
          <li class="flex items-center"><i class="bi bi-dash text-gray-400 mr-2"></i>Split bill, service charge</li>
          <li class="flex items-center"><i class="bi bi-dash text-gray-400 mr-2"></i>Role manager & audit log</li>
        </ul>

        <a href="{{ url('/signup') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-sky-500 text-sky-600 font-bold text-sm mt-4 hover:bg-sky-50 transition-colors">Coba Gratis 7 Hari</a>
      </div>

      {{-- PRO (RECOMMENDED) --}}
      <div class="h-full p-6 border border-gray-200 rounded-2xl shadow-sm bg-white relative flex flex-col">
        <span class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center px-3 py-1 rounded-full bg-sky-500 text-white text-xs font-semibold">Paling Populer</span>
        <div class="flex justify-between items-center mt-2">
          <h3 class="text-lg font-semibold m-0">Pro</h3>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-600 border border-sky-200">Untuk berkembang</span>
        </div>
        <p class="text-gray-500 mt-2">Fitur lengkap untuk restoran yang butuh kontrol & laporan.</p>

        <div class="my-4">
          <div>
            <span class="text-4xl font-bold" data-monthly="Rp 399.000" data-yearly="Rp 339.000">Rp 399.000</span>
            <span class="text-gray-500">/bulan</span>
          </div>
          <div class="text-sm text-gray-500" id="pro-note">Tagihan tahunan: Rp 4.068.000</div>
        </div>

        <ul class="list-none space-y-2 text-sm text-gray-500 flex-grow-1">
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>3 outlet, meja tak terbatas</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>QR Menu → dapur, printer ESC/POS</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>POS lengkap (split bill, service charge, tip)</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>Inventory & recipe, low-stock alert</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>Laporan per outlet/kategori/kasir</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>RBAC: owner, manager, cashier, kitchen, waiter</li>
        </ul>

        <a href="{{ url('/signup') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-sky-500 text-white font-bold text-sm mt-4 hover:bg-sky-600 transition-colors">Mulai Trial</a>
      </div>

      {{-- ENTERPRISE --}}
      <div class="h-full p-6 border border-gray-200 rounded-2xl shadow-sm bg-white flex flex-col">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold m-0">Enterprise</h3>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-100 border border-gray-600">Skala besar</span>
        </div>
        <p class="text-gray-500 mt-2">Untuk brand multi-cabang yang butuh kustom & SLA.</p>

        <div class="my-4">
          <div>
            <span class="text-4xl font-bold">Hubungi Kami</span>
          </div>
          <div class="text-sm text-gray-500">Harga khusus sesuai kebutuhan</div>
        </div>

        <ul class="list-none space-y-2 text-sm text-gray-500 flex-grow-1">
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>Outlet tak terbatas</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>Integrasi ERP/akuntansi & SSO/2FA</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>Custom workflow dapur & routing printer</li>
          <li class="flex items-center"><i class="bi bi-check2 text-green-500 mr-2"></i>SLA, dukungan onboarding, training tim</li>
        </ul>

        <a href="{{ url('/contact') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-gray-800 text-white font-bold text-sm mt-4 hover:bg-gray-900 transition-colors">Diskusi Kebutuhan</a>
      </div>

    </div>

    {{-- Catatan harga --}}
    <div class="text-sm text-gray-500 text-center mt-8">
      Harga belum termasuk pajak yang berlaku. Pembayaran tahunan ditagihkan di muka. Trial 7 hari berlaku untuk Starter & Pro.
    </div>
  </div>
</section>

{{-- Tabel Perbandingan Fitur --}}
<section class="bg-slate-50 border-t border-gray-200">
  <div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-xl font-bold text-center mb-6">Perbandingan Fitur</h2>
    <div class="overflow-x-auto shadow-sm rounded-2xl bg-white">
      <table class="w-full align-middle">
        <thead class="bg-slate-50">
          <tr>
            <th>Fitur</th>
            <th class="text-center">Starter</th>
            <th class="text-center">Pro</th>
            <th class="text-center">Enterprise</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Outlet</td>
            <td class="text-center">1</td>
            <td class="text-center">3</td>
            <td class="text-center">Tak terbatas</td>
          </tr>
          <tr>
            <td>QR Menu per meja</td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
          </tr>
          <tr>
            <td>Printer dapur (ESC/POS)</td>
            <td class="text-center"><i class="bi bi-dash text-gray-400"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
          </tr>
          <tr>
            <td>POS: split bill, service charge</td>
            <td class="text-center"><i class="bi bi-dash text-gray-400"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
          </tr>
          <tr>
            <td>Inventory & recipe</td>
            <td class="text-center"><i class="bi bi-dash text-gray-400"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
          </tr>
          <tr>
            <td>Laporan lanjutan (COGS, kasir, kategori)</td>
            <td class="text-center"><i class="bi bi-dash text-gray-400"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
          </tr>
          <tr>
            <td>RBAC & audit log</td>
            <td class="text-center"><i class="bi bi-dash text-gray-400"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
          </tr>
          <tr>
            <td>Integrasi & SLA</td>
            <td class="text-center"><i class="bi bi-dash text-gray-400"></i></td>
            <td class="text-center"><i class="bi bi-dash text-gray-400"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-green-500"></i></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

{{-- FAQ singkat --}}
<section>
  <div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-xl font-bold text-center mb-6">Pertanyaan Umum</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="p-6 border border-gray-200 rounded-2xl h-full">
        <h3 class="text-base font-semibold">Apakah bisa ganti paket kapan saja?</h3>
        <p class="text-sm text-gray-500 mb-0">Bisa. Upgrade/downgrade pro-rata akan dihitung otomatis pada siklus berikutnya.</p>
      </div>
      <div class="p-6 border border-gray-200 rounded-2xl h-full">
        <h3 class="text-base font-semibold">Metode pembayaran?</h3>
        <p class="text-sm text-gray-500 mb-0">Kartu kredit, transfer virtual account, e-wallet (via Stripe/Midtrans). Invoice otomatis.</p>
      </div>
      <div class="p-6 border border-gray-200 rounded-2xl h-full">
        <h3 class="text-base font-semibold">Bagaimana setelah trial?</h3>
        <p class="text-sm text-gray-500 mb-0">Anda bisa lanjut berbayar atau membatalkan. Data tetap aman dan bisa diekspor.</p>
      </div>
      <div class="p-6 border border-gray-200 rounded-2xl h-full">
        <h3 class="text-base font-semibold">Bisa minta demo?</h3>
        <p class="text-sm text-gray-500 mb-0">Tentu. <a href="{{ url('/contact') }}" class="text-sky-600 hover:text-sky-700">Hubungi kami</a> untuk sesi demo & konsultasi singkat.</p>
      </div>
    </div>

    <div class="text-center mt-8">
      <a class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-sky-500 text-white font-bold text-base hover:bg-sky-600 transition-colors" href="{{ url('/signup') }}">Mulai Trial Gratis</a>
    </div>
  </div>
</section>

@push('scripts')
<script>
  // Toggle Bulanan/Tahunan: update semua angka sesuai data attribute
  (function(){
    const sw = document.getElementById('toggle-annual');
    const priceEls = document.querySelectorAll('[data-monthly][data-yearly]');
    const fmtIDR = (n) => n.toLocaleString('id-ID');

    function applyPrices() {
      const annual = sw.checked;
      priceEls.forEach(el => {
        el.textContent = annual ? el.getAttribute('data-yearly') : el.getAttribute('data-monthly');
      });
      // Update catatan tagihan tahunan (Starter & Pro)
      const starterNote = document.getElementById('starter-note');
      const proNote = document.getElementById('pro-note');
      if (starterNote && proNote) {
        if (annual) {
          starterNote.textContent = 'Tagihan tahunan: Rp ' + fmtIDR(169000 * 12);
          proNote.textContent     = 'Tagihan tahunan: Rp ' + fmtIDR(339000 * 12);
        } else {
          starterNote.textContent = 'Tagihan bulanan';
          proNote.textContent     = 'Tagihan bulanan';
        }
      }
    }
    sw.addEventListener('change', applyPrices);
    // default tampil: bulanan. Hitung tagihan tahunan sebagai info awal:
    applyPrices();
  })();
</script>
@endpush
@endsection
