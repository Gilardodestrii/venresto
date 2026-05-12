@extends('layouts.marketing')

@section('title', 'VenResto — POS & QR Menu untuk Restoran')
@section('meta_description', 'POS + QR Menu modern untuk restoran. Kasir cepat, tiket dapur, inventory, laporan, multi-tenant. Coba gratis 7 hari!')

@section('content')
  {{-- Hero --}}
  <section class="hero">
    <div class="container py-5 py-lg-6">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <span class="badge badge-soft mb-3">Trial 7 hari • Tanpa kartu kredit</span>
          <h1 class="display-5 fw-bold text-dark">POS & QR Menu modern untuk restoran yang ingin tumbuh cepat</h1>
          <p class="lead text-secondary mt-3">
            Kelola pesanan dari QR per meja, kasir, dapur, hingga laporan—semua terintegrasi. Multi-tenant & siap scale.
          </p>
          <div class="d-flex gap-2 mt-3">
            <a href="{{ url('/signup') }}" class="btn btn-primary btn-lg">Mulai Trial Gratis</a>
            <a href="{{ url('/pricing') }}" class="btn btn-outline-primary btn-lg">Lihat Harga</a>
          </div>
          <div class="d-flex align-items-center gap-3 mt-3 small text-secondary">
            <i class="bi bi-shield-check"></i><span>Keamanan & isolasi data per tenant</span>
            <i class="bi bi-cloud-check"></i><span>Online 24/7</span>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="ratio ratio-16x9 shadow-soft rounded-4 border">
            {{-- Ganti dengan screenshot/preview app Anda --}}
            <img src="{{ asset('assets/img/venresto.png') }}" class="w-100 h-100 object-fit-cover rounded-4" alt="Tampilan VenResto">
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Trust bar / brand row --}}
  <section>
    <div class="container py-4">
      <div class="text-center text-secondary mb-3">Dipercaya tim F&B dan UMKM</div>
      <div class="d-flex justify-content-center flex-wrap gap-4 opacity-75">
        <img src="{{ asset('assets/img/sapore.png') }}" alt="Brand 1" height="200">
        <img src="{{ asset('assets/img/warung-kita.png') }}" alt="Brand 2" height="250">
        <img src="{{ asset('images/brand-3.png') }}" alt="Brand 3" height="28">
      </div>
    </div>
  </section>

  {{-- Fitur utama --}}
  <section class="bg-white">
    <div class="container py-5">
      <div class="text-center mb-4">
        <h2 class="h1 fw-bold">Fitur yang bikin kerja makin efisien</h2>
        <p class="text-secondary">Dari meja pelanggan sampai laporan owner—semua satu alur.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-qr-code fs-4 text-primary"></i></div>
            <h3 class="h5">QR Menu per Meja</h3>
            <p class="text-secondary">Pelanggan scan, pilih menu, buat pesanan; tiket otomatis ke dapur.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-cash-coin fs-4 text-primary"></i></div>
            <h3 class="h5">POS Kasir Lengkap</h3>
            <p class="text-secondary">Open/close shift, hold, split bill, diskon, pajak, service charge.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-box-seam fs-4 text-primary"></i></div>
            <h3 class="h5">Inventory Sederhana</h3>
            <p class="text-secondary">Recipe & bahan, pergerakan stok, low-stock alert.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-people fs-4 text-primary"></i></div>
            <h3 class="h5">RBAC Multi-Role</h3>
            <p class="text-secondary">Owner, manager, cashier, kitchen, waiter; akses rapi & aman.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-printer fs-4 text-primary"></i></div>
            <h3 class="h5">Cetak Dapur & Struk</h3>
            <p class="text-secondary">ESC/POS (LAN/Bluetooth), PDF share siap kirim.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="p-4 border rounded-4 h-100 shadow-soft">
            <div class="feature-icon mb-3"><i class="bi bi-graph-up-arrow fs-4 text-primary"></i></div>
            <h3 class="h5">Laporan Real-time</h3>
            <p class="text-secondary">Penjualan per hari/meja/kategori, best seller, COGS sederhana.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Langkah mulai --}}
  <section class="bg-light">
    <div class="container py-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6">
          <h2 class="h1 fw-bold">Mulai dalam 3 langkah</h2>
          <ul class="list-unstyled mt-3">
            <li class="d-flex gap-3 mb-3">
              <div class="feature-icon"><span class="fw-bold">1</span></div>
              <div><strong>Daftar tenant</strong><br><span class="text-secondary">Isi nama usaha & subdomain.</span></div>
            </li>
            <li class="d-flex gap-3 mb-3">
              <div class="feature-icon"><span class="fw-bold">2</span></div>
              <div><strong>Atur menu & meja</strong><br><span class="text-secondary">Tambahkan kategori, harga, & QR per meja.</span></div>
            </li>
            <li class="d-flex gap-3">
              <div class="feature-icon"><span class="fw-bold">3</span></div>
              <div><strong>Terima pesanan</strong><br><span class="text-secondary">Pesanan masuk ke kasir & dapur otomatis.</span></div>
            </li>
          </ul>
          <a href="{{ url('/signup') }}" class="btn btn-primary btn-lg mt-2">Coba Gratis 7 Hari</a>
        </div>
        <div class="col-lg-6">
          <div class="p-4 border rounded-4 bg-white shadow-soft">
            <h3 class="h5 mb-3">Quick Signup</h3>
            {{-- Contoh form singkat (opsional) --}}
            <form method="POST" action="{{ url('/signup') }}">
              @csrf
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nama Usaha</label>
                  <input name="tenant_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Nama Anda</label>
                  <input name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" minlength="8" required>
                </div>
              </div>
              <button class="btn btn-dark mt-3 w-100" type="submit">
                Buat Akun & Tenant
              </button>
              <p class="small text-secondary mt-2">Dengan melanjutkan, Anda menyetujui <a href="{{ url('/terms') }}">Syarat</a> & <a href="{{ url('/privacy') }}">Kebijakan Privasi</a>.</p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Testimoni singkat --}}
  <section>
    <div class="container py-5">
      <div class="text-center mb-4">
        <h2 class="h1 fw-bold">Apa kata mereka</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="p-4 border rounded-4 h-100 bg-white shadow-soft">
            <p class="mb-3">“Pesanan via QR jauh mengurangi antrian. Laporan harian jadi rapi.”</p>
            <div class="small text-secondary">— Aji, Owner Warung Aji</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 border rounded-4 h-100 bg-white shadow-soft">
            <p class="mb-3">“Kasir cepat, split bill & service charge mantap. Dapur langsung cetak.”</p>
            <div class="small text-secondary">— Rina, Manager Kafe Nusantara</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 border rounded-4 h-100 bg-white shadow-soft">
            <p class="mb-3">“Setup 1 hari sudah jalan. Support responsif.”</p>
            <div class="small text-secondary">— Bima, Restoran Suki</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FAQ --}}
  <section class="bg-light">
    <div class="container py-5">
      <div class="text-center mb-4">
        <h2 class="h1 fw-bold">Pertanyaan Umum</h2>
      </div>
      <div class="accordion" id="faq">
        <div class="accordion-item">
          <h2 class="accordion-header" id="q1">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#a1">
              Apakah ada trial gratis?
            </button>
          </h2>
          <div id="a1" class="accordion-collapse collapse show" data-bs-parent="#faq">
            <div class="accordion-body">
              Ya, Anda mendapat trial 7 hari tanpa kartu kredit. Bisa dibatalkan kapan saja.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="q2">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a2">
              Apakah mendukung printer kasir & dapur?
            </button>
          </h2>
          <div id="a2" class="accordion-collapse collapse" data-bs-parent="#faq">
            <div class="accordion-body">
              Ya, kami mendukung ESC/POS via LAN/Bluetooth serta export PDF untuk dibagikan.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="q3">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a3">
              Bisakah pakai subdomain khusus per tenant?
            </button>
          </h2>
          <div id="a3" class="accordion-collapse collapse" data-bs-parent="#faq">
            <div class="accordion-body">
              Bisa. Setiap tenant mendapatkan subdomain, misalnya <code>warung-aji.appku.com</code>.
            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-4">
        <a class="btn btn-primary btn-lg" href="{{ url('/signup') }}">Mulai Trial Sekarang</a>
      </div>
    </div>
  </section>
@endsection
