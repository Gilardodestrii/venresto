@extends('layouts.marketing')

@section('title', 'Documentation - VenResto')

@section('content')

<style>
:root{
    --primary:#0ea5e9;
    --bg:#f8fbff;
    --card:#ffffff;
    --border:#e5e7eb;
    --text:#0f172a;
    --muted:#64748b;
}

body{
    background:var(--bg);
}

.documentation-hero{
    padding:120px 0 80px;
}

.doc-badge{
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding:10px 18px;
    border-radius:999px;
    background:rgba(255,255,255,.8);
    border:1px solid rgba(255,255,255,.5);
    backdrop-filter:blur(16px);
    color:var(--primary);
    font-weight:700;
}

.doc-title{
    font-size:58px;
    font-weight:800;
    line-height:1.1;
    margin-top:24px;
    color:var(--text);
}

.doc-subtitle{
    max-width:760px;
    font-size:18px;
    line-height:1.8;
    color:var(--muted);
    margin-top:24px;
}

.doc-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:24px;
    margin-top:60px;
}

.doc-card{
    background:rgba(255,255,255,.88);
    border:1px solid rgba(255,255,255,.5);
    backdrop-filter:blur(16px);
    border-radius:28px;
    padding:28px;
    box-shadow:0 10px 30px rgba(15,23,42,.05);
}

.doc-icon{
    width:64px;
    height:64px;
    border-radius:20px;
    background:rgba(14,165,233,.12);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    color:var(--primary);
    margin-bottom:20px;
}

.doc-card h4{
    font-size:22px;
    font-weight:700;
    margin-bottom:12px;
}

.doc-card p{
    color:var(--muted);
    line-height:1.8;
    margin:0;
}

.doc-section{
    padding:30px 0 90px;
}

.doc-item{
    background:white;
    border-radius:24px;
    padding:28px;
    border:1px solid var(--border);
    margin-bottom:20px;
}

.doc-item h5{
    font-weight:700;
    margin-bottom:12px;
}

.doc-item p{
    color:var(--muted);
    line-height:1.8;
    margin:0;
}

@media(max-width:768px){

    .doc-title{
        font-size:38px;
    }

}
</style>

<section class="documentation-hero">
    <div class="container">

        <div class="doc-badge">
            <i class="bi bi-book"></i>
            VenResto Documentation
        </div>

        <h1 class="doc-title">
            Panduan Lengkap<br>
            Menggunakan VenResto
        </h1>

        <p class="doc-subtitle">
            Pelajari cara menggunakan sistem kasir restoran modern VenResto,
            mulai dari setup outlet, QR menu, POS cashier, kitchen display,
            laporan penjualan, hingga manajemen operasional restoran.
        </p>

        <div class="doc-grid">

            <div class="doc-card">
                <div class="doc-icon">
                    <i class="bi bi-shop"></i>
                </div>

                <h4>Setup Outlet</h4>

                <p>
                    Tambahkan cabang restoran, meja, dan QR code customer.
                </p>
            </div>

            <div class="doc-card">
                <div class="doc-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>

                <h4>POS Cashier</h4>

                <p>
                    Kelola transaksi restoran dengan sistem kasir modern.
                </p>
            </div>

            <div class="doc-card">
                <div class="doc-icon">
                    <i class="bi bi-display"></i>
                </div>

                <h4>Kitchen Display</h4>

                <p>
                    Pantau pesanan dapur realtime tanpa kertas.
                </p>
            </div>

        </div>

    </div>
</section>

<section class="doc-section">
    <div class="container">

        <div class="doc-item">
            <h5>1. Membuat Outlet</h5>
            <p>
                Owner dapat membuat banyak outlet/cabang restoran dalam satu akun tenant.
            </p>
        </div>

        <div class="doc-item">
            <h5>2. Generate QR Meja</h5>
            <p>
                Setiap meja memiliki QR unik untuk customer ordering.
            </p>
        </div>

        <div class="doc-item">
            <h5>3. Menggunakan POS</h5>
            <p>
                Cashier dapat membuat order, split bill, hold order, dan menerima pembayaran.
            </p>
        </div>

        <div class="doc-item">
            <h5>4. Monitoring Kitchen</h5>
            <p>
                Kitchen staff dapat menerima order realtime melalui kitchen display system.
            </p>
        </div>

        <div class="doc-item">
            <h5>5. Laporan Penjualan</h5>
            <p>
                Pantau omzet, best seller menu, dan performa outlet realtime.
            </p>
        </div>

    </div>
</section>

@endsection
