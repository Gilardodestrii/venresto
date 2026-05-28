@extends('layouts.marketing')

@section('title', 'Kebijakan Privasi - VenResto')

@section('content')
<style>
:root{--primary:#0ea5e9;--bg:#f8fbff;--card:#fff;--border:#e5e7eb;--text:#0f172a;--muted:#64748b;} body{background:var(--bg);} .hero{padding:110px 0 60px;background:linear-gradient(180deg,#f8fbff 0%,#fff 70%);} .badge-soft{display:inline-flex;align-items:center;gap:10px;padding:10px 18px;border-radius:999px;background:#fff;border:1px solid var(--border);font-weight:800;color:var(--primary);} .title{font-size:54px;font-weight:900;line-height:1.08;margin-top:22px;color:var(--text);} .subtitle{max-width:780px;color:var(--muted);line-height:1.9;font-size:18px;margin-top:20px;} .doc-card{background:#fff;border:1px solid var(--border);border-radius:28px;padding:32px;box-shadow:0 18px 45px rgba(15,23,42,.05);} .doc-card h4{font-weight:800;margin-bottom:16px;} .doc-card p,.doc-card li{color:var(--muted);line-height:1.9;} @media(max-width:768px){.title{font-size:38px}}
</style>

<section class="hero">
<div class="container">
<div class="badge-soft"><i class="bi bi-shield-check"></i> Privacy Policy</div>
<h1 class="title">Kebijakan Privasi VenResto</h1>
<p class="subtitle">VenResto berkomitmen menjaga keamanan dan kerahasiaan data restoran, outlet, transaksi, dan pengguna yang menggunakan platform SaaS POS kami.</p>
</div>
</section>

<section class="pb-5">
<div class="container">
<div class="doc-card mb-4">
<h4>1. Informasi yang Dikumpulkan</h4>
<p>Kami dapat mengumpulkan informasi seperti nama tenant, email, nomor telepon, data outlet, transaksi penjualan, data inventori, dan aktivitas penggunaan sistem untuk mendukung operasional platform VenResto.</p>
</div>
<div class="doc-card mb-4">
<h4>2. Penggunaan Data</h4>
<ul>
<li>Menyediakan layanan POS dan QR menu.</li>
<li>Mengelola akun tenant dan outlet restoran.</li>
<li>Meningkatkan keamanan dan performa sistem.</li>
<li>Mengirim pemberitahuan layanan dan dukungan pelanggan.</li>
</ul>
</div>
<div class="doc-card mb-4">
<h4>3. Keamanan Data</h4>
<p>VenResto menerapkan langkah keamanan teknis dan administratif untuk melindungi data pengguna dari akses tidak sah, kehilangan data, maupun penyalahgunaan sistem.</p>
</div>
<div class="doc-card mb-4">
<h4>4. Pembagian Informasi</h4>
<p>Kami tidak menjual data pengguna kepada pihak ketiga. Informasi hanya digunakan untuk kebutuhan operasional layanan dan integrasi pembayaran yang diperlukan.</p>
</div>
<div class="doc-card">
<h4>5. Perubahan Kebijakan</h4>
<p>Kebijakan privasi dapat diperbarui sewaktu-waktu sesuai pengembangan layanan VenResto. Pengguna disarankan meninjau halaman ini secara berkala.</p>
</div>
</div>
</section>
@endsection