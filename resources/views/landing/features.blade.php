@extends('layouts.marketing')

@section('title','Fitur VenResto — POS & QR Menu untuk Restoran')
@section('meta_description','Daftar lengkap fitur VenResto: QR Menu per meja, POS kasir, inventory, laporan, RBAC, printer, multi-tenant, subscription, dan lainnya.')

@section('content')
  {{-- Hero --}}
  <section class="hero border-bottom">
    <div class="container py-5">
      <div class="row align-items-center gy-4">
        <div class="col-lg-7">
          <span class="badge badge-soft mb-3">Produk</span>
          <h1 class="display-5 fw-bold">Fitur lengkap untuk operasional restoran modern</h1>
          <p class="lead text-secondary mt-2">
            Dari pesanan via QR hingga laporan penjualan—semua terhubung, aman, dan siap scale.
          </p>
          <div class="d-flex gap-2 mt-3">
            <a href="{{ url('/signup') }}" class="btn btn-primary btn-lg">Coba Gratis 7 Hari</a>
            <a href="{{ url('/pricing') }}" class="btn btn-outline-primary btn-lg">Lihat Harga</a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="p-4 bg-white border rounded-4 shadow-soft h-100">
            <div class="d-flex align-items-center gap-3">
              <i class="bi bi-stars fs-2 text-primary"></i>
              <div>
                <div class="fw-semibold">Sorotan</div>
                <div class="small text-secondary">Multi-tenant • Printer ESC/POS • Laporan real-time • RBAC</div>
              </div>
            </div>
            <hr>
            <ul class="small text-secondary mb-0">
              <li>Arsitektur API Laravel (Sanctum) & Flutter POS</li>
              <li>Queue/Horizon, Webhook, Cashier (Stripe/Midtrans)</li>
              <li>CI/CD & Docker siap untuk staging/production</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Grid Fitur Utama --}}
  <section>
    <div class="container py-5">
      <div class="text-center mb-4">
        <h2 class="h1 fw-bold">Semua yang Anda butuhkan, satu platform</h2>
        <p class="text-secondary">Pilih fitur untuk melihat detailnya.</p>
      </div>
      <div class="row g-4">
        {{-- QR Menu --}}
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-qr-code fs-4 text-primary"></i></div>
            <h3 class="h5">QR Menu per Meja</h3>
            <p class="text-secondary">Pelanggan scan, pilih menu, order langsung. Dukungan modifier, catatan, & antrian meja.</p>
            <ul class="small text-secondary mb-0">
              <li>Subdomain tenant</li><li>Kategori, variasi, & stok indikator</li><li>Kode meja unik</li>
            </ul>
          </div>
        </div>
        {{-- POS Kasir --}}
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-cash-coin fs-4 text-primary"></i></div>
            <h3 class="h5">POS Kasir</h3>
            <p class="text-secondary">Open/close shift, hold order, split bill, diskon, pajak, service charge, tip.</p>
            <ul class="small text-secondary mb-0">
              <li>Metode bayar multi-channel</li><li>Refund & void berizin</li><li>Catatan kas</li>
            </ul>
          </div>
        </div>
        {{-- Inventory --}}
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-box-seam fs-4 text-primary"></i></div>
            <h3 class="h5">Inventory & Recipe</h3>
            <p class="text-secondary">Bahan, resep sederhana, pergerakan stok, dan low-stock alert untuk kontrol COGS.</p>
            <ul class="small text-secondary mb-0">
              <li>Pengurangan stok per item</li><li>Penyesuaian & mutasi</li><li>Supplier ringkas</li>
            </ul>
          </div>
        </div>
        {{-- Dapur & Printer --}}
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-printer fs-4 text-primary"></i></div>
            <h3 class="h5">Dapur & Printer</h3>
            <p class="text-secondary">Tiket dapur otomatis. Dukungan ESC/POS LAN/Bluetooth & export PDF/WhatsApp.</p>
            <ul class="small text-secondary mb-0">
              <li>Routing tiket per kategori</li><li>Reprint & re-route</li><li>Template struk</li>
            </ul>
          </div>
        </div>
        {{-- Laporan --}}
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-graph-up-arrow fs-4 text-primary"></i></div>
            <h3 class="h5">Laporan Real-time</h3>
            <p class="text-secondary">Penjualan per hari/meja/kategori, best seller, performa kasir, dan COGS sederhana.</p>
            <ul class="small text-secondary mb-0">
              <li>Ekspor PDF/Excel</li><li>Filter periode & outlet</li><li>KPI ringkas</li>
            </ul>
          </div>
        </div>
        {{-- RBAC --}}
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-people fs-4 text-primary"></i></div>
            <h3 class="h5">RBAC Multi-Role</h3>
            <p class="text-secondary">Role owner, manager, cashier, kitchen, waiter. Hak akses granular & audit trail.</p>
            <ul class="small text-secondary mb-0">
              <li>Log aktivitas</li><li>Kebijakan password</li><li>2FA (opsional)</li>
            </ul>
          </div>
        </div>
        {{-- Subscription SaaS --}}
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-credit-card fs-4 text-primary"></i></div>
            <h3 class="h5">Subscription SaaS</h3>
            <p class="text-secondary">Plan, billing cycle, grace period, webhook sinkron status (Stripe/Midtrans).</p>
            <ul class="small text-secondary mb-0">
              <li>Trial 7 hari</li><li>Pembatalan & upgrade</li><li>Invoice otomatis</li>
            </ul>
          </div>
        </div>
        {{-- Multi-tenant & Domain --}}
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-hdd-network fs-4 text-primary"></i></div>
            <h3 class="h5">Multi-tenant & Domain</h3>
            <p class="text-secondary">Isolasi data kuat (schema/tenant_id), subdomain per tenant, siap scale.</p>
            <ul class="small text-secondary mb-0">
              <li>Isolasi query middleware</li><li>Seeder per tenant</li><li>Backup per tenant</li>
            </ul>
          </div>
        </div>
        {{-- Offline-first (opsional) --}}
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-cloud-slash fs-4 text-primary"></i></div>
            <h3 class="h5">Offline-first (Opsional)</h3>
            <p class="text-secondary">Cache order sementara pada device; sinkron otomatis saat online.</p>
            <ul class="small text-secondary mb-0">
              <li>Retry & conflict policy</li><li>Queue lokal</li><li>Notifikasi sinkron</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Detail Teknis --}}
  <section class="bg-light border-top border-bottom">
    <div class="container py-5">
      <div class="row g-4">
        <div class="col-lg-4">
          <h2 class="h4 fw-bold">Arsitektur & Teknologi</h2>
          <p class="text-secondary">Didesain untuk kecepatan, keamanan, dan kemudahan perawatan.</p>
        </div>
        <div class="col-lg-8">
          <div class="row g-4">
            <div class="col-md-6">
              <div class="p-4 bg-white border rounded-4 h-100 shadow-soft">
                <div class="d-flex align-items-center gap-3 mb-2">
                  <i class="bi bi-braces-asterisk fs-4 text-primary"></i>
                  <div class="fw-semibold">Backend API Laravel</div>
                </div>
                <ul class="small text-secondary mb-0">
                  <li>Sanctum, Pest/PHPUnit, Telescope</li>
                  <li>Queues via Horizon, Mail</li>
                  <li>Cashier (Stripe/Midtrans), Webhook</li>
                </ul>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-4 bg-white border rounded-4 h-100 shadow-soft">
                <div class="d-flex align-items-center gap-3 mb-2">
                  <i class="bi bi-phone fs-4 text-primary"></i>
                  <div class="fw-semibold">Frontend Flutter</div>
                </div>
                <ul class="small text-secondary mb-0">
                  <li>BLoC, Freezed, Dio/Retrofit</li>
                  <li>JsonSerializable, Hydrated BLoC</li>
                  <li>GoRouter, Isar/Drift (cache)</li>
                </ul>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-4 bg-white border rounded-4 h-100 shadow-soft">
                <div class="d-flex align-items-center gap-3 mb-2">
                  <i class="bi bi-shield-check fs-4 text-primary"></i>
                  <div class="fw-semibold">Keamanan</div>
                </div>
                <ul class="small text-secondary mb-0">
                  <li>Rate limit & CSRF aware (web)</li>
                  <li>Encryption at rest (opsional)</li>
                  <li>Log audit & masking data sensitif</li>
                </ul>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-4 bg-white border rounded-4 h-100 shadow-soft">
                <div class="d-flex align-items-center gap-3 mb-2">
                  <i class="bi bi-gear-wide-connected fs-4 text-primary"></i>
                  <div class="fw-semibold">DevOps</div>
                </div>
                <ul class="small text-secondary mb-0">
                  <li>Docker, env multi-stage</li>
                  <li>CI/CD (GitHub Actions)</li>
                  <li>Versioning & changelog</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="alert alert-info mt-4 mb-0">
            Butuh integrasi tambahan (akuntansi, e-invoice, loyalty, marketplace)? <a href="{{ url('/contact') }}" class="alert-link">Hubungi kami</a>.
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section>
    <div class="container py-5 text-center">
      <h2 class="h1 fw-bold">Siap mengoptimalkan operasional restoran Anda?</h2>
      <p class="text-secondary">Coba gratis 7 hari. Tanpa kartu kredit. Batalkan kapan saja.</p>
      <div class="d-flex justify-content-center gap-2">
        <a class="btn btn-primary btn-lg" href="{{ url('/signup') }}">Mulai Trial</a>
        <a class="btn btn-outline-primary btn-lg" href="{{ url('/pricing') }}">Lihat Harga</a>
      </div>
    </div>
  </section>
@endsection
