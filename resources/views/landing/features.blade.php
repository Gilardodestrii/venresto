@extends('layouts.marketing')

@section('title','Fitur VenResto — POS & QR Menu untuk Restoran')
@section('meta_description','Daftar lengkap fitur VenResto: QR Menu per meja, POS kasir, inventory, laporan, RBAC, printer, multi-tenant, subscription, dan lainnya.')

@section('content')
  {{-- Hero --}}
  <section class="hero border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
        <div>
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700 mb-3">Produk</span>
          <h1 class="text-4xl font-bold">Fitur lengkap untuk operasional restoran modern</h1>
          <p class="text-lg text-slate-500 mt-2">
            Dari pesanan via QR hingga laporan penjualan—semua terhubung, aman, dan siap scale.
          </p>
          <div class="flex gap-2 mt-3">
            <a href="{{ url('/signup') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-sky-500 text-white font-bold text-base hover:bg-sky-600 shadow-lg transition">Coba Gratis 7 Hari</a>
            <a href="{{ url('/pricing') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl border-2 border-sky-500 text-sky-500 font-bold text-base hover:bg-sky-50 shadow-lg transition">Lihat Harga</a>
          </div>
        </div>
        <div>
          <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-md h-full">
            <div class="flex items-center gap-3">
              <i class="bi bi-stars fs-2 text-sky-500"></i>
              <div>
                <div class="font-semibold">Sorotan</div>
                <div class="text-sm text-slate-500">Multi-tenant • Printer ESC/POS • Laporan real-time • RBAC</div>
              </div>
            </div>
            <hr class="my-4">
            <ul class="text-sm text-slate-500 mb-0 space-y-1">
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
    <div class="max-w-7xl mx-auto px-4 py-12">
      <div class="text-center mb-6">
        <h2 class="text-3xl md:text-4xl font-bold">Semua yang Anda butuhkan, satu platform</h2>
        <p class="text-slate-500">Pilih fitur untuk melihat detailnya.</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- QR Menu --}}
        <div>
          <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-md">
            <div class="feature-icon mb-3"><i class="bi bi-qr-code fs-4 text-sky-500"></i></div>
            <h3 class="text-lg font-semibold">QR Menu per Meja</h3>
            <p class="text-slate-500">Pelanggan scan, pilih menu, order langsung. Dukungan modifier, catatan, & antrian meja.</p>
            <ul class="text-sm text-slate-500 mb-0 space-y-1">
              <li>Subdomain tenant</li><li>Kategori, variasi, & stok indikator</li><li>Kode meja unik</li>
            </ul>
          </div>
        </div>
        {{-- POS Kasir --}}
        <div>
          <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-md">
            <div class="feature-icon mb-3"><i class="bi bi-cash-coin fs-4 text-sky-500"></i></div>
            <h3 class="text-lg font-semibold">POS Kasir</h3>
            <p class="text-slate-500">Open/close shift, hold order, split bill, diskon, pajak, service charge, tip.</p>
            <ul class="text-sm text-slate-500 mb-0 space-y-1">
              <li>Metode bayar multi-channel</li><li>Refund & void berizin</li><li>Catatan kas</li>
            </ul>
          </div>
        </div>
        {{-- Inventory --}}
        <div>
          <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-md">
            <div class="feature-icon mb-3"><i class="bi bi-box-seam fs-4 text-sky-500"></i></div>
            <h3 class="text-lg font-semibold">Inventory & Recipe</h3>
            <p class="text-slate-500">Bahan, resep sederhana, pergerakan stok, dan low-stock alert untuk kontrol COGS.</p>
            <ul class="text-sm text-slate-500 mb-0 space-y-1">
              <li>Pengurangan stok per item</li><li>Penyesuaian & mutasi</li><li>Supplier ringkas</li>
            </ul>
          </div>
        </div>
        {{-- Dapur & Printer --}}
        <div>
          <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-md">
            <div class="feature-icon mb-3"><i class="bi bi-printer fs-4 text-sky-500"></i></div>
            <h3 class="text-lg font-semibold">Dapur & Printer</h3>
            <p class="text-slate-500">Tiket dapur otomatis. Dukungan ESC/POS LAN/Bluetooth & export PDF/WhatsApp.</p>
            <ul class="text-sm text-slate-500 mb-0 space-y-1">
              <li>Routing tiket per kategori</li><li>Reprint & re-route</li><li>Template struk</li>
            </ul>
          </div>
        </div>
        {{-- Laporan --}}
        <div>
          <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-md">
            <div class="feature-icon mb-3"><i class="bi bi-graph-up-arrow fs-4 text-sky-500"></i></div>
            <h3 class="text-lg font-semibold">Laporan Real-time</h3>
            <p class="text-slate-500">Penjualan per hari/meja/kategori, best seller, performa kasir, dan COGS sederhana.</p>
            <ul class="text-sm text-slate-500 mb-0 space-y-1">
              <li>Ekspor PDF/Excel</li><li>Filter periode & outlet</li><li>KPI ringkas</li>
            </ul>
          </div>
        </div>
        {{-- RBAC --}}
        <div>
          <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-md">
            <div class="feature-icon mb-3"><i class="bi bi-people fs-4 text-sky-500"></i></div>
            <h3 class="text-lg font-semibold">RBAC Multi-Role</h3>
            <p class="text-slate-500">Role owner, manager, cashier, kitchen, waiter. Hak akses granular & audit trail.</p>
            <ul class="text-sm text-slate-500 mb-0 space-y-1">
              <li>Log aktivitas</li><li>Kebijakan password</li><li>2FA (opsional)</li>
            </ul>
          </div>
        </div>
        {{-- Subscription SaaS --}}
        <div>
          <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-md">
            <div class="feature-icon mb-3"><i class="bi bi-credit-card fs-4 text-sky-500"></i></div>
            <h3 class="text-lg font-semibold">Subscription SaaS</h3>
            <p class="text-slate-500">Plan, billing cycle, grace period, webhook sinkron status (Stripe/Midtrans).</p>
            <ul class="text-sm text-slate-500 mb-0 space-y-1">
              <li>Trial 7 hari</li><li>Pembatalan & upgrade</li><li>Invoice otomatis</li>
            </ul>
          </div>
        </div>
        {{-- Multi-tenant & Domain --}}
        <div>
          <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-md">
            <div class="feature-icon mb-3"><i class="bi bi-hdd-network fs-4 text-sky-500"></i></div>
            <h3 class="text-lg font-semibold">Multi-tenant & Domain</h3>
            <p class="text-slate-500">Isolasi data kuat (schema/tenant_id), subdomain per tenant, siap scale.</p>
            <ul class="text-sm text-slate-500 mb-0 space-y-1">
              <li>Isolasi query middleware</li><li>Seeder per tenant</li><li>Backup per tenant</li>
            </ul>
          </div>
        </div>
        {{-- Offline-first (opsional) --}}
        <div>
          <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-md">
            <div class="feature-icon mb-3"><i class="bi bi-cloud-slash fs-4 text-sky-500"></i></div>
            <h3 class="text-lg font-semibold">Offline-first (Opsional)</h3>
            <p class="text-slate-500">Cache order sementara pada device; sinkron otomatis saat online.</p>
            <ul class="text-sm text-slate-500 mb-0 space-y-1">
              <li>Retry & conflict policy</li><li>Queue lokal</li><li>Notifikasi sinkron</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Detail Teknis --}}
  <section class="bg-slate-100 border-t border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-12">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div>
          <h2 class="text-xl font-bold">Arsitektur & Teknologi</h2>
          <p class="text-slate-500">Didesain untuk kecepatan, keamanan, dan kemudahan perawatan.</p>
        </div>
        <div class="lg:col-span-2">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <div class="p-4 bg-white border border-slate-200 rounded-2xl h-full shadow-md">
                <div class="flex items-center gap-3 mb-2">
                  <i class="bi bi-braces-asterisk fs-4 text-sky-500"></i>
                  <div class="font-semibold">Backend API Laravel</div>
                </div>
                <ul class="text-sm text-slate-500 mb-0 space-y-1">
                  <li>Sanctum, Pest/PHPUnit, Telescope</li>
                  <li>Queues via Horizon, Mail</li>
                  <li>Cashier (Stripe/Midtrans), Webhook</li>
                </ul>
              </div>
            </div>
            <div>
              <div class="p-4 bg-white border border-slate-200 rounded-2xl h-full shadow-md">
                <div class="flex items-center gap-3 mb-2">
                  <i class="bi bi-phone fs-4 text-sky-500"></i>
                  <div class="font-semibold">Frontend Flutter</div>
                </div>
                <ul class="text-sm text-slate-500 mb-0 space-y-1">
                  <li>BLoC, Freezed, Dio/Retrofit</li>
                  <li>JsonSerializable, Hydrated BLoC</li>
                  <li>GoRouter, Isar/Drift (cache)</li>
                </ul>
              </div>
            </div>
            <div>
              <div class="p-4 bg-white border border-slate-200 rounded-2xl h-full shadow-md">
                <div class="flex items-center gap-3 mb-2">
                  <i class="bi bi-shield-check fs-4 text-sky-500"></i>
                  <div class="font-semibold">Keamanan</div>
                </div>
                <ul class="text-sm text-slate-500 mb-0 space-y-1">
                  <li>Rate limit & CSRF aware (web)</li>
                  <li>Encryption at rest (opsional)</li>
                  <li>Log audit & masking data sensitif</li>
                </ul>
              </div>
            </div>
            <div>
              <div class="p-4 bg-white border border-slate-200 rounded-2xl h-full shadow-md">
                <div class="flex items-center gap-3 mb-2">
                  <i class="bi bi-gear-wide-connected fs-4 text-sky-500"></i>
                  <div class="font-semibold">DevOps</div>
                </div>
                <ul class="text-sm text-slate-500 mb-0 space-y-1">
                  <li>Docker, env multi-stage</li>
                  <li>CI/CD (GitHub Actions)</li>
                  <li>Versioning & changelog</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="bg-sky-50 border border-sky-200 rounded-xl p-4 text-sm text-sky-800 mt-4 mb-0">
            Butuh integrasi tambahan (akuntansi, e-invoice, loyalty, marketplace)? <a href="{{ url('/contact') }}" class="font-semibold underline">Hubungi kami</a>.
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section>
    <div class="max-w-7xl mx-auto px-4 py-12 text-center">
      <h2 class="text-3xl md:text-4xl font-bold">Siap mengoptimalkan operasional restoran Anda?</h2>
      <p class="text-slate-500">Coba gratis 7 hari. Tanpa kartu kredit. Batalkan kapan saja.</p>
      <div class="flex justify-center gap-2 mt-4">
        <a class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-sky-500 text-white font-bold text-base hover:bg-sky-600 shadow-lg transition" href="{{ url('/signup') }}">Mulai Trial</a>
        <a class="inline-flex items-center justify-center px-6 py-3 rounded-2xl border-2 border-sky-500 text-sky-500 font-bold text-base hover:bg-sky-50 shadow-lg transition" href="{{ url('/pricing') }}">Lihat Harga</a>
      </div>
    </div>
  </section>
@endsection
