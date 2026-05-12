@extends('layouts.marketing')

@section('title','Harga VenResto — POS & QR Menu untuk Restoran')
@section('meta_description','Pilih paket VenResto: Starter, Pro, atau Enterprise. Coba gratis 7 hari. Hemat 15% untuk pembayaran tahunan.')

@section('content')
<section class="hero border-bottom">
  <div class="container py-5 text-center">
    <span class="badge badge-soft">Trial 7 hari • Tanpa kartu kredit</span>
    <h1 class="display-5 fw-bold mt-2">Pilih paket sesuai skala restoran Anda</h1>
    <p class="lead text-secondary">Bayar bulanan atau hemat <strong>15%</strong> dengan paket tahunan.</p>

    {{-- Toggle Billing --}}
    <div class="d-inline-flex align-items-center gap-2 border rounded-pill px-2 py-1 mt-3">
      <span class="small text-secondary">Bulanan</span>
      <div class="form-check form-switch m-0">
        <input class="form-check-input" type="checkbox" id="toggle-annual" aria-label="Toggle Tahunan">
      </div>
      <span class="small"><strong>Tahunan</strong> <span class="badge bg-success-subtle text-success border ms-1">Hemat 15%</span></span>
    </div>
  </div>
</section>

<section>
  <div class="container py-5">
    <div class="row g-4 align-items-stretch">

      {{-- STARTER --}}
      <div class="col-md-6 col-lg-4">
        <div class="h-100 p-4 border rounded-4 shadow-soft bg-white d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="h5 m-0">Starter</h3>
            <span class="badge bg-secondary-subtle text-secondary border">Untuk mulai</span>
          </div>
          <p class="text-secondary mt-2">Cocok untuk warung/kafe kecil yang baru go-digital.</p>

          <div class="my-3">
            <div>
              <span class="display-6 fw-bold" data-monthly="Rp 199.000" data-yearly="Rp 169.000">Rp 199.000</span>
              <span class="text-secondary">/bulan</span>
            </div>
            <div class="small text-secondary" id="starter-note">Tagihan tahunan: Rp 2.028.000</div>
          </div>

          <ul class="list-unstyled small text-secondary flex-grow-1">
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>1 outlet, 10 meja</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>QR Menu & pesanan ke dapur</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>POS kasir dasar (hold, diskon)</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Laporan harian dasar</li>
            <li class="mb-2"><i class="bi bi-dash text-secondary me-2"></i>Inventory & recipe sederhana</li>
            <li class="mb-2"><i class="bi bi-dash text-secondary me-2"></i>Split bill, service charge</li>
            <li class="mb-2"><i class="bi bi-dash text-secondary me-2"></i>Role manager & audit log</li>
          </ul>

          <a href="{{ url('/signup') }}" class="btn btn-outline-primary w-100 mt-2">Coba Gratis 7 Hari</a>
        </div>
      </div>

      {{-- PRO (RECOMMENDED) --}}
      <div class="col-md-6 col-lg-4">
        <div class="h-100 p-4 border rounded-4 shadow-soft bg-white position-relative d-flex flex-column">
          <span class="position-absolute top-0 start-50 translate-middle badge bg-primary">Paling Populer</span>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <h3 class="h5 m-0">Pro</h3>
            <span class="badge bg-primary-subtle text-primary border">Untuk berkembang</span>
          </div>
          <p class="text-secondary mt-2">Fitur lengkap untuk restoran yang butuh kontrol & laporan.</p>

          <div class="my-3">
            <div>
              <span class="display-6 fw-bold" data-monthly="Rp 399.000" data-yearly="Rp 339.000">Rp 399.000</span>
              <span class="text-secondary">/bulan</span>
            </div>
            <div class="small text-secondary" id="pro-note">Tagihan tahunan: Rp 4.068.000</div>
          </div>

          <ul class="list-unstyled small text-secondary flex-grow-1">
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>3 outlet, meja tak terbatas</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>QR Menu → dapur, printer ESC/POS</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>POS lengkap (split bill, service charge, tip)</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Inventory & recipe, low-stock alert</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Laporan per outlet/kategori/kasir</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>RBAC: owner, manager, cashier, kitchen, waiter</li>
          </ul>

          <a href="{{ url('/signup') }}" class="btn btn-primary w-100 mt-2">Mulai Trial</a>
        </div>
      </div>

      {{-- ENTERPRISE --}}
      <div class="col-md-6 col-lg-4">
        <div class="h-100 p-4 border rounded-4 shadow-soft bg-white d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="h5 m-0">Enterprise</h3>
            <span class="badge bg-dark-subtle text-dark border">Skala besar</span>
          </div>
          <p class="text-secondary mt-2">Untuk brand multi-cabang yang butuh kustom & SLA.</p>

          <div class="my-3">
            <div>
              <span class="display-6 fw-bold">Hubungi Kami</span>
            </div>
            <div class="small text-secondary">Harga khusus sesuai kebutuhan</div>
          </div>

          <ul class="list-unstyled small text-secondary flex-grow-1">
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Outlet tak terbatas</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Integrasi ERP/akuntansi & SSO/2FA</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Custom workflow dapur & routing printer</li>
            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>SLA, dukungan onboarding, training tim</li>
          </ul>

          <a href="{{ url('/contact') }}" class="btn btn-dark w-100 mt-2">Diskusi Kebutuhan</a>
        </div>
      </div>

    </div>

    {{-- Catatan harga --}}
    <div class="small text-secondary text-center mt-4">
      Harga belum termasuk pajak yang berlaku. Pembayaran tahunan ditagihkan di muka. Trial 7 hari berlaku untuk Starter & Pro.
    </div>
  </div>
</section>

{{-- Tabel Perbandingan Fitur --}}
<section class="bg-light border-top">
  <div class="container py-5">
    <h2 class="h4 fw-bold text-center mb-4">Perbandingan Fitur</h2>
    <div class="table-responsive shadow-soft rounded-4 bg-white">
      <table class="table align-middle mb-0">
        <thead class="table-light">
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
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
          </tr>
          <tr>
            <td>Printer dapur (ESC/POS)</td>
            <td class="text-center"><i class="bi bi-dash text-secondary"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
          </tr>
          <tr>
            <td>POS: split bill, service charge</td>
            <td class="text-center"><i class="bi bi-dash text-secondary"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
          </tr>
          <tr>
            <td>Inventory & recipe</td>
            <td class="text-center"><i class="bi bi-dash text-secondary"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
          </tr>
          <tr>
            <td>Laporan lanjutan (COGS, kasir, kategori)</td>
            <td class="text-center"><i class="bi bi-dash text-secondary"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
          </tr>
          <tr>
            <td>RBAC & audit log</td>
            <td class="text-center"><i class="bi bi-dash text-secondary"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
          </tr>
          <tr>
            <td>Integrasi & SLA</td>
            <td class="text-center"><i class="bi bi-dash text-secondary"></i></td>
            <td class="text-center"><i class="bi bi-dash text-secondary"></i></td>
            <td class="text-center"><i class="bi bi-check2 text-success"></i></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

{{-- FAQ singkat --}}
<section>
  <div class="container py-5">
    <h2 class="h4 fw-bold text-center mb-4">Pertanyaan Umum</h2>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="p-4 border rounded-4 h-100">
          <h3 class="h6">Apakah bisa ganti paket kapan saja?</h3>
          <p class="small text-secondary mb-0">Bisa. Upgrade/downgrade pro-rata akan dihitung otomatis pada siklus berikutnya.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-4 border rounded-4 h-100">
          <h3 class="h6">Metode pembayaran?</h3>
          <p class="small text-secondary mb-0">Kartu kredit, transfer virtual account, e-wallet (via Stripe/Midtrans). Invoice otomatis.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-4 border rounded-4 h-100">
          <h3 class="h6">Bagaimana setelah trial?</h3>
          <p class="small text-secondary mb-0">Anda bisa lanjut berbayar atau membatalkan. Data tetap aman dan bisa diekspor.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-4 border rounded-4 h-100">
          <h3 class="h6">Bisa minta demo?</h3>
          <p class="small text-secondary mb-0">Tentu. <a href="{{ url('/contact') }}">Hubungi kami</a> untuk sesi demo & konsultasi singkat.</p>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <a class="btn btn-primary btn-lg" href="{{ url('/signup') }}">Mulai Trial Gratis</a>
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
