@extends('layouts.marketing')

@section('title', 'VenResto — POS & QR Menu untuk Restoran')
@section('meta_description', 'POS + QR Menu modern untuk restoran. Kasir cepat, tiket dapur, inventory, laporan, multi-tenant. Coba gratis 7 hari!')

@section('content')
  {{-- Hero --}}
  <section class="hero">
    <div class="max-w-7xl mx-auto px-4 py-5 lg:py-6">
      <div class="grid lg:grid-cols-2 gap-4 items-center">
        <div>
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700 mb-3">Trial 7 hari • Tanpa kartu kredit</span>
          <h1 class="text-4xl lg:text-5xl font-bold text-slate-900">POS & QR Menu modern untuk restoran yang ingin tumbuh cepat</h1>
          <p class="text-lg text-slate-600 mt-3">
            Kelola pesanan dari QR per meja, kasir, dapur, hingga laporan—semua terintegrasi. Multi-tenant & siap scale.
          </p>
          <div class="flex gap-2 mt-3">
            <a href="{{ url('/signup') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-sky-500 text-white font-bold text-base hover:bg-sky-600 shadow-lg transition">Mulai Trial Gratis</a>
            <a href="{{ url('/pricing') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl border-2 border-sky-500 text-sky-600 font-bold text-base hover:bg-sky-50 transition">Lihat Harga</a>
          </div>
          <div class="flex items-center gap-3 mt-3 text-sm text-slate-600">
            <i class="bi bi-shield-check"></i><span>Keamanan & isolasi data per tenant</span>
            <i class="bi bi-cloud-check"></i><span>Online 24/7</span>
          </div>
        </div>
        <div>
          <div class="aspect-video rounded-2xl border border-slate-200 shadow-lg overflow-hidden">
            {{-- Ganti dengan screenshot/preview app Anda --}}
            <img src="{{ asset('assets/img/venresto.png') }}" class="w-full h-full object-cover" alt="Tampilan VenResto">
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Trust bar / brand row --}}
  <section>
    <div class="max-w-7xl mx-auto px-4 py-4">
      <div class="text-center text-slate-600 mb-3">Dipercaya tim F&B dan UMKM</div>
      <div class="flex justify-center flex-wrap gap-4 opacity-75">
        <img src="{{ asset('assets/img/sapore.png') }}" alt="Brand 1" height="200">
        <img src="{{ asset('assets/img/warung-kita.png') }}" alt="Brand 2" height="250">
        <img src="{{ asset('assets/img/nuvora.png') }}" alt="Brand 3" height="200">
      </div>
    </div>
  </section>

  {{-- Fitur utama --}}
  <section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 py-5">
      <div class="text-center mb-4">
        <h2 class="text-3xl font-bold">Fitur yang bikin kerja makin efisien</h2>
        <p class="text-slate-600">Dari meja pelanggan sampai laporan owner—semua satu alur.</p>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-lg">
          <div class="mb-3"><i class="bi bi-qr-code text-2xl text-sky-500"></i></div>
          <h3 class="text-lg font-semibold">QR Menu per Meja</h3>
          <p class="text-slate-600">Pelanggan scan, pilih menu, buat pesanan; tiket otomatis ke dapur.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-lg">
          <div class="mb-3"><i class="bi bi-cash-coin text-2xl text-sky-500"></i></div>
          <h3 class="text-lg font-semibold">POS Kasir Lengkap</h3>
          <p class="text-slate-600">Open/close shift, hold, split bill, diskon, pajak, service charge.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-lg">
          <div class="mb-3"><i class="bi bi-box-seam text-2xl text-sky-500"></i></div>
          <h3 class="text-lg font-semibold">Inventory Sederhana</h3>
          <p class="text-slate-600">Recipe & bahan, pergerakan stok, low-stock alert.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-lg">
          <div class="mb-3"><i class="bi bi-people text-2xl text-sky-500"></i></div>
          <h3 class="text-lg font-semibold">RBAC Multi-Role</h3>
          <p class="text-slate-600">Owner, manager, cashier, kitchen, waiter; akses rapi & aman.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-lg">
          <div class="mb-3"><i class="bi bi-printer text-2xl text-sky-500"></i></div>
          <h3 class="text-lg font-semibold">Cetak Dapur & Struk</h3>
          <p class="text-slate-600">ESC/POS (LAN/Bluetooth), PDF share siap kirim.</p>
        </div>
        <div class="p-4 border border-slate-200 rounded-2xl h-full shadow-lg">
          <div class="mb-3"><i class="bi bi-graph-up-arrow text-2xl text-sky-500"></i></div>
          <h3 class="text-lg font-semibold">Laporan Real-time</h3>
          <p class="text-slate-600">Penjualan per hari/meja/kategori, best seller, COGS sederhana.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Langkah mulai --}}
  <section class="bg-slate-100">
    <div class="max-w-7xl mx-auto px-4 py-5">
      <div class="grid lg:grid-cols-2 gap-4 items-center">
        <div>
          <h2 class="text-3xl font-bold">Mulai dalam 3 langkah</h2>
          <ul class="list-none mt-3">
            <li class="flex gap-3 mb-3">
              <div><span class="font-bold">1</span></div>
              <div><strong>Daftar tenant</strong><br><span class="text-slate-600">Isi nama usaha & subdomain.</span></div>
            </li>
            <li class="flex gap-3 mb-3">
              <div><span class="font-bold">2</span></div>
              <div><strong>Atur menu & meja</strong><br><span class="text-slate-600">Tambahkan kategori, harga, & QR per meja.</span></div>
            </li>
            <li class="flex gap-3">
              <div><span class="font-bold">3</span></div>
              <div><strong>Terima pesanan</strong><br><span class="text-slate-600">Pesanan masuk ke kasir & dapur otomatis.</span></div>
            </li>
          </ul>
          <a href="{{ url('/signup') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-sky-500 text-white font-bold text-base hover:bg-sky-600 shadow-lg transition mt-2">Coba Gratis 7 Hari</a>
        </div>
        <div>
          <div class="p-4 border border-slate-200 rounded-2xl bg-white shadow-lg">
            <h3 class="text-lg font-semibold mb-3">Quick Signup</h3>
            {{-- Contoh form singkat (opsional) --}}
            <form method="POST" action="{{ url('/signup') }}">
              @csrf
              <div class="grid md:grid-cols-2 gap-3">
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Usaha</label>
                  <input name="tenant_name" class="w-full h-11 px-4 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                  <input type="email" name="email" class="w-full h-11 px-4 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Anda</label>
                  <input name="name" class="w-full h-11 px-4 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                  <input type="password" name="password" class="w-full h-11 px-4 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-500" minlength="8" required>
                </div>
              </div>
              <button class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-slate-900 text-white font-bold text-base hover:bg-slate-800 shadow-lg transition mt-3 w-full" type="submit">
                Buat Akun & Tenant
              </button>
              <p class="text-sm text-slate-600 mt-2">Dengan melanjutkan, Anda menyetujui <a href="{{ url('/terms') }}">Syarat</a> & <a href="{{ url('/privacy') }}">Kebijakan Privasi</a>.</p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Testimoni singkat --}}
  <section>
    <div class="max-w-7xl mx-auto px-4 py-5">
      <div class="text-center mb-4">
        <h2 class="text-3xl font-bold">Apa kata mereka</h2>
      </div>
      <div class="grid md:grid-cols-3 gap-4">
        <div class="p-4 border border-slate-200 rounded-2xl h-full bg-white shadow-lg">
          <p class="mb-3">"Pesanan via QR jauh mengurangi antrian. Laporan harian jadi rapi."</p>
          <div class="text-sm text-slate-600">— Aji, Owner Warung Aji</div>
        </div>
        <div class="p-4 border border-slate-200 rounded-2xl h-full bg-white shadow-lg">
          <p class="mb-3">"Kasir cepat, split bill & service charge mantap. Dapur langsung cetak."</p>
          <div class="text-sm text-slate-600">— Rina, Manager Kafe Nusantara</div>
        </div>
        <div class="p-4 border border-slate-200 rounded-2xl h-full bg-white shadow-lg">
          <p class="mb-3">"Setup 1 hari sudah jalan. Support responsif."</p>
          <div class="text-sm text-slate-600">— Bima, Restoran Suki</div>
        </div>
      </div>
    </div>
  </section>

  {{-- FAQ --}}
  <section class="bg-slate-100">
    <div class="max-w-7xl mx-auto px-4 py-5">
      <div class="text-center mb-4">
        <h2 class="text-3xl font-bold">Pertanyaan Umum</h2>
      </div>
      <div class="border border-slate-200 rounded-2xl overflow-hidden">
        <div>
          <h2 class="accordion-header">
            <button class="w-full flex items-center justify-between px-4 py-3 text-left font-semibold text-slate-900 hover:bg-slate-50 transition" type="button" data-bs-toggle="collapse" data-bs-target="#a1">
              Apakah ada trial gratis?
            </button>
          </h2>
          <div id="a1" class="accordion-collapse collapse show" data-bs-parent="#faq">
            <div class="px-4 py-3 text-slate-600">
              Ya, Anda mendapat trial 7 hari tanpa kartu kredit. Bisa dibatalkan kapan saja.
            </div>
          </div>
        </div>
        <div>
          <h2 class="accordion-header">
            <button class="w-full flex items-center justify-between px-4 py-3 text-left font-semibold text-slate-900 hover:bg-slate-50 transition" type="button" data-bs-toggle="collapse" data-bs-target="#a2">
              Apakah mendukung printer kasir & dapur?
            </button>
          </h2>
          <div id="a2" class="accordion-collapse collapse" data-bs-parent="#faq">
            <div class="px-4 py-3 text-slate-600">
              Ya, kami mendukung ESC/POS via LAN/Bluetooth serta export PDF untuk dibagikan.
            </div>
          </div>
        </div>
        <div>
          <h2 class="accordion-header">
            <button class="w-full flex items-center justify-between px-4 py-3 text-left font-semibold text-slate-900 hover:bg-slate-50 transition" type="button" data-bs-toggle="collapse" data-bs-target="#a3">
              Bisakah pakai subdomain khusus per tenant?
            </button>
          </h2>
          <div id="a3" class="accordion-collapse collapse" data-bs-parent="#faq">
            <div class="px-4 py-3 text-slate-600">
              Bisa. Setiap tenant mendapatkan subdomain, misalnya <code>warung-aji.appku.com</code>.
            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-4">
        <a class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-sky-500 text-white font-bold text-base hover:bg-sky-600 shadow-lg transition" href="{{ url('/signup') }}">Mulai Trial Sekarang</a>
      </div>
    </div>
  </section>
@endsection
