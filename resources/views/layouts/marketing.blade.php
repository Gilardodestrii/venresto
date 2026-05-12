<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title', 'VenResto — POS & QR Menu untuk Restoran')</title>
  <meta name="description" content="@yield('meta_description', 'POS + QR Menu modern untuk restoran. Multi-tenant, kasir cepat, cetak dapur, laporan lengkap. Coba gratis 7 hari.')">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Open Graph / Twitter Card --}}
  <meta property="og:title" content="@yield('og_title', 'VenResto — POS & QR Menu untuk Restoran')">
  <meta property="og:description" content="@yield('og_description', 'Tingkatkan operasional restoran Anda dengan VenResto. Coba gratis 7 hari!')">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset('images/og-venresto.jpg') }}">
  <meta name="twitter:card" content="summary_large_image">

  {{-- Favicon (sesuaikan aset Anda) --}}
  <link rel="icon" href="{{ asset('favicon.ico') }}">

  {{-- Bootstrap & Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Custom minimal styles --}}
  <style>
    :root{
      --vr-primary:#0ea5e9; /* sky-500 tone */
      --vr-dark:#0b1220;
    }
    .btn-primary{
      background:var(--vr-primary); border-color:var(--vr-primary);
    }
    .hero{
      background: linear-gradient(180deg, #f8fbff 0%, #ffffff 60%);
    }
    .logo-text{
      font-weight:700; letter-spacing:.2px;
    }
    .feature-icon{
      width:3rem; height:3rem; border-radius:.75rem; display:inline-flex; align-items:center; justify-content:center;
      background:#eef7ff;
    }
    .shadow-soft{ box-shadow: 0 10px 30px rgba(2, 32, 71, .06); }
    .badge-soft{ background:#eef7ff; color:#0b66c3; }
    .footer-link{ color:#6b7280; text-decoration:none }
    .footer-link:hover{ text-decoration:underline; color:#111827 }
  </style>

  {{-- JSON-LD (Organization) --}}
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"SoftwareApplication",
    "name":"VenResto",
    "applicationCategory":"BusinessApplication",
    "operatingSystem":"Web",
    "offers":{"@type":"Offer","price":"0","priceCurrency":"IDR"},
    "description":"POS & QR Menu untuk restoran, lengkap dengan kasir, inventory, laporan, dan integrasi printer.",
    "url":"{{ url('/') }}"
  }
  </script>
  @stack('head')
</head>
<body>

  {{-- Navbar --}}
  <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
        {{-- SVG logo minimal --}}
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <rect x="3" y="3" width="18" height="18" rx="4" fill="url(#g)"/>
          <path d="M8 12h8M8 16h5M8 8h8" stroke="#fff" stroke-width="1.6" stroke-linecap="round"/>
          <defs>
            <linearGradient id="g" x1="3" x2="21" y1="3" y2="21">
              <stop stop-color="#38bdf8"/><stop offset="1" stop-color="#0ea5e9"/>
            </linearGradient>
          </defs>
        </svg>
        <span class="logo-text">VenResto</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div id="nav" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="{{ url('/features') }}">Fitur</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/pricing') }}">Harga</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/docs') }}">Dokumentasi</a></li>
          <li class="nav-item ms-lg-3 my-2 my-lg-0">
            <a class="btn btn-outline-primary"
            href="{{ isset($currentTenant) ? url($currentTenant->slug.'/login') : url('/login') }}">
            Masuk
          </a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-primary" href="{{ url('/signup') }}">Mulai Trial</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  {{-- Page Content --}}
  <main>
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="border-top mt-5 py-4">
    <div class="container">
      <div class="row gy-3 align-items-center">
        <div class="col-md-6">
          <div class="d-flex align-items-center gap-2">
            <strong>VenResto</strong><span class="text-secondary">© {{ date('Y') }}</span>
          </div>
          <div class="small text-secondary mt-1">POS & QR Menu untuk restoran, warung, dan kafe.</div>
        </div>
        <div class="col-md-6 text-md-end">
          <a class="footer-link me-3" href="{{ url('/privacy') }}">Kebijakan Privasi</a>
          <a class="footer-link me-3" href="{{ url('/terms') }}">Syarat Layanan</a>
          <a class="footer-link" href="{{ url('/contact') }}">Kontak</a>
        </div>
      </div>
    </div>
  </footer>

  {{-- Bootstrap --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  @stack('scripts')
</body>
</html>
