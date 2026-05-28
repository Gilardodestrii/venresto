@extends('layouts.marketing')

@section('title', 'Syarat Layanan - VenResto')

@section('content')
<style>
:root{--primary:#0ea5e9;--bg:#f8fbff;--card:#fff;--border:#e5e7eb;--text:#0f172a;--muted:#64748b;} body{background:var(--bg);} .hero{padding:110px 0 60px;background:linear-gradient(180deg,#f8fbff 0%,#fff 70%);} .badge-soft{display:inline-flex;align-items:center;gap:10px;padding:10px 18px;border-radius:999px;background:#fff;border:1px solid var(--border);font-weight:800;color:var(--primary);} .title{font-size:54px;font-weight:900;line-height:1.08;margin-top:22px;color:var(--text);} .subtitle{max-width:780px;color:var(--muted);line-height:1.9;font-size:18px;margin-top:20px;} .doc-card{background:#fff;border:1px solid var(--border);border-radius:28px;padding:32px;box-shadow:0 18px 45px rgba(15,23,42,.05);} .doc-card h4{font-weight:800;margin-bottom:16px;} .doc-card p,.doc-card li{color:var(--muted);line-height:1.9;} @media(max-width:768px){.title{font-size:38px}}
</style>

<section class="hero">
<div class="container">
<div class="badge-soft"><i class="bi bi-file-earmark-text"></i> Terms of Service</div>
<h1 class="title">Syarat & Ketentuan Layanan</h1>
<p class="subtitle">Dengan menggunakan VenResto, Anda menyetujui syarat penggunaan layanan SaaS POS restoran yang berlaku pada platform kami.</p>
</div>
</section>

<section class="pb-5">
<div class="container">
<div class="doc-card mb-4">
<h4>1. Penggunaan Layanan</h4>
<p>VenResto menyediakan platform POS restoran berbasis cloud yang dapat digunakan untuk pengelolaan transaksi, QR menu, inventory, kitchen display, dan operasional outlet.</p>
</div>
<div class="doc-card mb-4">
<h4>2. Akun Tenant</h4>
<p>Pengguna bertanggung jawab menjaga keamanan akun tenant, password, dan akses staff pada sistem VenResto.</p>
</div>
<div class="doc-card mb-4">
<h4>3. Pembayaran & Subscription</h4>
<p>Beberapa fitur dapat memerlukan paket berlangganan aktif. Kegagalan pembayaran dapat membatasi akses terhadap layanan tertentu.</p>
</div>
<div class="doc-card mb-4">
<h4>4. Larangan Penggunaan</h4>
<ul>
<li>Dilarang menggunakan layanan untuk aktivitas ilegal.</li>
<li>Dilarang mencoba mengakses sistem tanpa izin.</li>
<li>Dilarang menyalahgunakan layanan sehingga mengganggu tenant lain.</li>
</ul>
</div>
<div class="doc-card">
<h4>5. Perubahan Layanan</h4>
<p>VenResto dapat memperbarui fitur, harga, dan kebijakan layanan sewaktu-waktu untuk meningkatkan kualitas platform.</p>
</div>
</div>
</section>
@endsection