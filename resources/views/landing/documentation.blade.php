@extends('layouts.marketing')

@section('title', 'Panduan Penggunaan VenResto - POS Restoran & QR Menu')
@section('meta_description', 'Panduan lengkap penggunaan VenResto: QR menu, order management, POS cashier, kitchen display, outlet, meja, kategori menu, inventory, pembayaran, role staff, dan laporan.')

@section('content')
<style>
:root{
    --primary:#0ea5e9;
    --bg:#f8fbff;
    --card:#ffffff;
    --border:#e5e7eb;
    --text:#0f172a;
    --muted:#64748b;
    --soft:#eef7ff;
}

html{scroll-behavior:smooth;}
body{background:var(--bg);}

.documentation-hero{
    padding:110px 0 70px;
    background:
        radial-gradient(circle at top left, rgba(14,165,233,.14), transparent 32%),
        linear-gradient(180deg,#f8fbff 0%,#ffffff 72%);
}

.doc-badge{
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding:10px 18px;
    border-radius:999px;
    background:rgba(255,255,255,.86);
    border:1px solid rgba(226,232,240,.9);
    backdrop-filter:blur(16px);
    color:var(--primary);
    font-weight:800;
    box-shadow:0 10px 30px rgba(15,23,42,.05);
}

.doc-title{
    font-size:58px;
    font-weight:900;
    letter-spacing:-.045em;
    line-height:1.08;
    margin-top:24px;
    color:var(--text);
}

.doc-subtitle{
    max-width:860px;
    font-size:18px;
    line-height:1.85;
    color:var(--muted);
    margin-top:24px;
}

.doc-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:22px;
    margin-top:52px;
}

.doc-card,
.doc-panel,
.doc-sidebar{
    background:rgba(255,255,255,.9);
    border:1px solid rgba(226,232,240,.9);
    backdrop-filter:blur(18px);
    border-radius:28px;
    box-shadow:0 18px 45px rgba(15,23,42,.06);
}

.doc-card{padding:26px;}
.doc-icon{
    width:58px;
    height:58px;
    border-radius:20px;
    background:rgba(14,165,233,.12);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:26px;
    color:var(--primary);
    margin-bottom:18px;
}

.doc-card h4{font-size:20px;font-weight:850;margin-bottom:10px;color:var(--text);}
.doc-card p{color:var(--muted);line-height:1.75;margin:0;}

.doc-section{padding:64px 0 90px;}
.doc-layout{display:grid;grid-template-columns:300px 1fr;gap:28px;align-items:start;}
.doc-sidebar{position:sticky;top:96px;padding:18px;}
.doc-sidebar-title{font-weight:900;color:var(--text);padding:10px 12px;}
.doc-nav{display:grid;gap:6px;}
.doc-nav a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:11px 12px;
    border-radius:16px;
    color:#475569;
    text-decoration:none;
    font-weight:650;
    transition:.18s ease;
}
.doc-nav a:hover{background:var(--soft);color:#0369a1;transform:translateX(2px);}

.doc-panel{padding:34px;margin-bottom:24px;scroll-margin-top:105px;}
.section-kicker{display:inline-flex;align-items:center;gap:8px;color:var(--primary);font-weight:850;font-size:14px;margin-bottom:10px;}
.doc-panel h2{font-weight:900;letter-spacing:-.03em;color:var(--text);margin-bottom:12px;}
.doc-panel .lead-text{color:var(--muted);line-height:1.85;font-size:16px;margin-bottom:24px;}

.step-list{counter-reset:step;display:grid;gap:14px;margin:0;padding:0;}
.step-item{list-style:none;display:grid;grid-template-columns:42px 1fr;gap:14px;padding:18px;border:1px solid var(--border);border-radius:22px;background:#fff;}
.step-item:before{counter-increment:step;content:counter(step);width:42px;height:42px;border-radius:15px;background:linear-gradient(135deg,#38bdf8,#0ea5e9);color:white;display:flex;align-items:center;justify-content:center;font-weight:900;box-shadow:0 12px 26px rgba(14,165,233,.22);}
.step-item h5{font-weight:850;margin-bottom:6px;color:var(--text);}
.step-item p{margin:0;color:var(--muted);line-height:1.75;}

.feature-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:14px;margin-top:18px;}
.feature-box{padding:18px;border:1px solid var(--border);border-radius:22px;background:#fff;}
.feature-box i{color:var(--primary);font-size:22px;margin-bottom:10px;display:inline-block;}
.feature-box h5{font-weight:850;font-size:16px;margin-bottom:8px;color:var(--text);}
.feature-box p{color:var(--muted);line-height:1.7;margin:0;font-size:14px;}

.callout{border-radius:22px;padding:20px;background:linear-gradient(135deg,rgba(14,165,233,.1),rgba(255,255,255,.9));border:1px solid rgba(14,165,233,.18);color:#0f172a;margin-top:20px;}
.callout strong{color:#0369a1;}

.table-doc{border:1px solid var(--border);border-radius:22px;overflow:hidden;background:#fff;}
.table-doc table{margin:0;}
.table-doc th{background:#f8fafc;color:#334155;font-size:13px;text-transform:uppercase;letter-spacing:.04em;}
.table-doc td{color:var(--muted);line-height:1.65;vertical-align:top;}
.badge-role{display:inline-flex;align-items:center;padding:7px 10px;border-radius:999px;background:#eef7ff;color:#0369a1;font-weight:800;font-size:12px;}

@media(max-width:991px){.doc-layout{grid-template-columns:1fr}.doc-sidebar{position:relative;top:0}.doc-nav{grid-template-columns:repeat(auto-fit,minmax(180px,1fr))}}
@media(max-width:768px){.documentation-hero{padding:80px 0 45px}.doc-title{font-size:38px}.doc-subtitle{font-size:16px}.doc-panel{padding:24px}.step-item{grid-template-columns:1fr}.step-item:before{margin-bottom:2px}}
</style>

<section class="documentation-hero">
    <div class="max-w-7xl mx-auto px-4">
        <div class="doc-badge"><i class="bi bi-book"></i> VenResto Documentation</div>
        <h1 class="doc-title">Panduan Lengkap<br>Menggunakan VenResto</h1>
        <p class="doc-subtitle">Dokumentasi operasional untuk owner, manager, cashier, kitchen staff, dan waiter. Ikuti alur dari setup awal, QR menu customer, POS cashier, order management, kitchen display, inventory, hingga laporan restoran.</p>

        <div class="doc-grid">
            <div class="doc-card"><div class="doc-icon"><i class="bi bi-qr-code-scan"></i></div><h4>QR Menu Customer</h4><p>Pelanggan scan QR meja, pilih menu, tambah catatan, lalu kirim order ke restoran.</p></div>
            <div class="doc-card"><div class="doc-icon"><i class="bi bi-cash-stack"></i></div><h4>POS Cashier</h4><p>Kasir membuat order, memproses pembayaran, dan mengelola transaksi outlet.</p></div>
            <div class="doc-card"><div class="doc-icon"><i class="bi bi-display"></i></div><h4>Kitchen Display</h4><p>Tim dapur menerima item order, mengubah status proses, dan menyelesaikan pesanan.</p></div>
            <div class="doc-card"><div class="doc-icon"><i class="bi bi-box-seam"></i></div><h4>Inventory</h4><p>Kelola bahan baku, resep menu, stok masuk, stok keluar, dan penyesuaian stok.</p></div>
        </div>
    </div>
</section>

<section class="doc-section">
    <div class="max-w-7xl mx-auto px-4">
        <div class="doc-layout">
            <aside class="doc-sidebar">
                <div class="doc-sidebar-title">Daftar Panduan</div>
                <nav class="doc-nav">
                    <a href="#overview"><i class="bi bi-compass"></i> Overview</a>
                    <a href="#setup"><i class="bi bi-shop"></i> Setup Awal</a>
                    <a href="#qr-order"><i class="bi bi-qr-code"></i> QR Menu Order</a>
                    <a href="#pos"><i class="bi bi-calculator"></i> POS Cashier</a>
                    <a href="#orders"><i class="bi bi-receipt"></i> Order Management</a>
                    <a href="#kitchen"><i class="bi bi-display"></i> Kitchen Display</a>
                    <a href="#menu"><i class="bi bi-journal-richtext"></i> Menu & Kategori</a>
                    <a href="#inventory"><i class="bi bi-boxes"></i> Inventory</a>
                    <a href="#roles"><i class="bi bi-people"></i> Role Staff</a>
                    <a href="#payments"><i class="bi bi-credit-card"></i> Pembayaran</a>
                    <a href="#reports"><i class="bi bi-graph-up"></i> Laporan</a>
                    <a href="#faq"><i class="bi bi-question-circle"></i> FAQ</a>
                </nav>
            </aside>

            <div>
                <article id="overview" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-compass"></i> Overview</div>
                    <h2>Alur kerja utama VenResto</h2>
                    <p class="lead-text">VenResto menghubungkan customer ordering, kasir, dapur, inventory, dan back office dalam satu sistem multi outlet berbasis tenant.</p>
                    <ol class="step-list">
                        <li class="step-item"><div><h5>Owner setup tenant dan outlet</h5><p>Setelah registrasi, owner masuk ke dashboard tenant, membuat outlet/cabang, lalu memilih outlet aktif untuk operasional.</p></div></li>
                        <li class="step-item"><div><h5>Admin membuat kategori, menu, meja, dan QR</h5><p>Menu disusun per kategori, meja dibuat per outlet, lalu QR meja digenerate dan ditempel di meja customer.</p></div></li>
                        <li class="step-item"><div><h5>Customer atau cashier membuat order</h5><p>Customer bisa scan QR meja, sedangkan cashier bisa membuat order langsung melalui POS.</p></div></li>
                        <li class="step-item"><div><h5>Dapur memproses pesanan</h5><p>Item order masuk ke kitchen display untuk diproses dari pending, cooking, ready, hingga served sesuai kebutuhan operasional.</p></div></li>
                        <li class="step-item"><div><h5>Kasir menyelesaikan pembayaran</h5><p>Order dibayar melalui cash atau QRIS, lalu transaksi masuk ke laporan penjualan dan stok dapat dikurangi sesuai konfigurasi.</p></div></li>
                    </ol>
                </article>

                <article id="setup" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-shop"></i> Setup Awal Back Office</div>
                    <h2>1. Setup outlet, meja, dan QR meja</h2>
                    <p class="lead-text">Setup awal dilakukan oleh owner atau manager sebelum restoran mulai menerima order.</p>
                    <div class="feature-list">
                        <div class="feature-box"><i class="bi bi-building"></i><h5>Outlet</h5><p>Buka menu Outlet, tambah nama cabang, alamat, lalu simpan. Outlet digunakan sebagai pemisah meja, order, inventory, dan laporan.</p></div>
                        <div class="feature-box"><i class="bi bi-grid-3x3-gap"></i><h5>Meja</h5><p>Masuk ke QR Meja pada outlet, buat kode meja seperti Meja 1, VIP 2, Takeaway, atau Patio 5.</p></div>
                        <div class="feature-box"><i class="bi bi-download"></i><h5>Download QR</h5><p>Generate QR untuk setiap meja, download PNG, lalu cetak dan tempel di meja customer.</p></div>
                    </div>
                    <div class="callout"><strong>Tips:</strong> gunakan nama meja yang mudah dikenali staff, misalnya “A1”, “VIP 1”, atau “Outdoor 3”. Ini membantu dapur dan kasir membaca asal order.</div>
                </article>

                <article id="qr-order" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-qr-code-scan"></i> Customer Ordering</div>
                    <h2>2. Cara order melalui scan QR menu</h2>
                    <p class="lead-text">QR menu membantu customer memesan langsung dari meja tanpa menunggu waiter mencatat pesanan.</p>
                    <ol class="step-list">
                        <li class="step-item"><div><h5>Customer scan QR di meja</h5><p>Customer membuka kamera HP, scan QR meja, lalu diarahkan ke halaman menu sesuai tenant, outlet, dan meja.</p></div></li>
                        <li class="step-item"><div><h5>Pilih menu dan jumlah</h5><p>Customer memilih item makanan/minuman, menentukan qty, dan menambahkan item ke keranjang.</p></div></li>
                        <li class="step-item"><div><h5>Tambahkan catatan pesanan</h5><p>Customer bisa menulis catatan seperti “tidak pedas”, “tanpa es”, “saus dipisah”, atau alergi tertentu.</p></div></li>
                        <li class="step-item"><div><h5>Kirim order</h5><p>Setelah checkout, order masuk ke sistem restoran. Staff dapat melihatnya di order management atau kitchen display.</p></div></li>
                        <li class="step-item"><div><h5>Bayar di kasir</h5><p>Customer dapat menyelesaikan pembayaran di kasir, atau melalui metode pembayaran yang tersedia seperti QRIS jika diaktifkan.</p></div></li>
                    </ol>
                </article>

                <article id="pos" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-calculator"></i> POS Cashier</div>
                    <h2>3. Menggunakan POS cashier</h2>
                    <p class="lead-text">POS digunakan kasir untuk membuat order langsung, mencari menu, menambah catatan, dan memproses pembayaran.</p>
                    <ol class="step-list">
                        <li class="step-item"><div><h5>Buka menu POS</h5><p>Masuk ke dashboard tenant, pilih outlet aktif, lalu buka menu POS.</p></div></li>
                        <li class="step-item"><div><h5>Pilih menu</h5><p>Gunakan pencarian atau kategori untuk memilih menu. Klik item untuk menambahkan ke cart.</p></div></li>
                        <li class="step-item"><div><h5>Atur qty dan catatan</h5><p>Kasir dapat menambah/mengurangi qty serta mengisi catatan item atau customer note.</p></div></li>
                        <li class="step-item"><div><h5>Simpan order</h5><p>Order dapat dibuat sebagai dine in, takeaway, atau sesuai tipe yang tersedia di sistem.</p></div></li>
                        <li class="step-item"><div><h5>Proses pembayaran</h5><p>Pilih metode pembayaran, simpan status pembayaran, lalu order masuk ke laporan.</p></div></li>
                    </ol>
                    <div class="callout"><strong>Catatan operasional:</strong> untuk restoran ramai, gunakan tablet cashier agar cart, menu, dan pembayaran lebih cepat diakses.</div>
                </article>

                <article id="orders" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-receipt"></i> Order Management</div>
                    <h2>4. Mengelola daftar order</h2>
                    <p class="lead-text">Order management membantu kasir dan manager memantau semua pesanan yang masuk dari QR menu maupun POS.</p>
                    <div class="feature-list">
                        <div class="feature-box"><i class="bi bi-list-check"></i><h5>Daftar order</h5><p>Lihat order terbaru, status pembayaran, total transaksi, cashier, dan waktu order.</p></div>
                        <div class="feature-box"><i class="bi bi-eye"></i><h5>Detail order</h5><p>Buka detail untuk melihat item pesanan, qty, harga, subtotal, catatan item, dan customer note.</p></div>
                        <div class="feature-box"><i class="bi bi-wallet2"></i><h5>Update pembayaran</h5><p>Kasir dapat mengubah status pembayaran ketika customer sudah membayar.</p></div>
                    </div>
                </article>

                <article id="kitchen" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-display"></i> Kitchen Display System</div>
                    <h2>5. Memproses pesanan di dapur</h2>
                    <p class="lead-text">Kitchen display menampilkan item order yang perlu diproses dapur. Cocok untuk tablet dapur atau monitor kitchen.</p>
                    <ol class="step-list">
                        <li class="step-item"><div><h5>Buka halaman kitchen</h5><p>Staff kitchen login ke tenant, lalu buka menu Kitchen Display.</p></div></li>
                        <li class="step-item"><div><h5>Lihat item pending</h5><p>Pesanan dari POS atau QR menu tampil sebagai item dapur beserta nama menu, qty, meja, dan catatan.</p></div></li>
                        <li class="step-item"><div><h5>Ubah status proses</h5><p>Kitchen staff mengubah status item sesuai progres, misalnya pending ke cooking, lalu ready.</p></div></li>
                        <li class="step-item"><div><h5>Serahkan ke waiter/cashier</h5><p>Item ready dapat disajikan ke meja atau dikemas untuk takeaway.</p></div></li>
                    </ol>
                </article>

                <article id="menu" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-journal-richtext"></i> Back Office Menu</div>
                    <h2>6. Manajemen kategori dan menu</h2>
                    <p class="lead-text">Menu yang rapi akan membuat POS dan QR menu lebih mudah digunakan customer maupun cashier.</p>
                    <div class="feature-list">
                        <div class="feature-box"><i class="bi bi-tags"></i><h5>Kategori menu</h5><p>Buat kategori seperti Makanan, Minuman, Coffee, Dessert, Paket Hemat, atau Add-on.</p></div>
                        <div class="feature-box"><i class="bi bi-card-image"></i><h5>Menu item</h5><p>Isi nama menu, harga, kategori, deskripsi, foto, dan status aktif agar muncul di POS/QR menu.</p></div>
                        <div class="feature-box"><i class="bi bi-toggle-on"></i><h5>Status aktif</h5><p>Nonaktifkan menu yang habis agar tidak bisa dipesan customer atau cashier.</p></div>
                    </div>
                    <div class="callout"><strong>Rekomendasi:</strong> gunakan foto menu yang konsisten dan nama menu yang jelas agar customer lebih cepat memesan.</div>
                </article>

                <article id="inventory" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-box-seam"></i> Inventory Management</div>
                    <h2>7. Mengelola inventory dan stok bahan</h2>
                    <p class="lead-text">Inventory digunakan untuk memantau bahan baku, movement stok, dan recipe/costing menu.</p>
                    <ol class="step-list">
                        <li class="step-item"><div><h5>Buat material/bahan baku</h5><p>Tambahkan bahan seperti beras, ayam, kopi, susu, gula, cup, atau packaging beserta satuannya.</p></div></li>
                        <li class="step-item"><div><h5>Catat stok masuk</h5><p>Gunakan stock in saat pembelian bahan dari supplier atau penambahan stok gudang.</p></div></li>
                        <li class="step-item"><div><h5>Catat stok keluar</h5><p>Gunakan stock out untuk bahan rusak, terbuang, transfer, atau penggunaan manual.</p></div></li>
                        <li class="step-item"><div><h5>Buat resep menu</h5><p>Hubungkan menu dengan material agar sistem dapat menghitung kebutuhan bahan dan costing.</p></div></li>
                        <li class="step-item"><div><h5>Lihat stock movements</h5><p>Pantau histori pergerakan stok agar owner dapat audit penggunaan bahan.</p></div></li>
                    </ol>
                </article>

                <article id="roles" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-people"></i> Role & Staff</div>
                    <h2>8. Hak akses staff</h2>
                    <p class="lead-text">VenResto menggunakan role agar setiap staff hanya mengakses menu sesuai tanggung jawabnya.</p>
                    <div class="table-doc overflow-x-auto rounded-2xl">
                        <table class="w-full">
                            <thead><tr><th>Role</th><th>Akses Utama</th><th>Contoh Penggunaan</th></tr></thead>
                            <tbody>
                                <tr><td><span class="badge-role">Owner</span></td><td>Semua fitur tenant</td><td>Mengelola outlet, staff, laporan, setting, billing, dan operasional utama.</td></tr>
                                <tr><td><span class="badge-role">Manager</span></td><td>Back office dan laporan</td><td>Mengelola menu, inventory, order, dan performa outlet.</td></tr>
                                <tr><td><span class="badge-role">Cashier</span></td><td>POS dan order</td><td>Membuat order, menerima pembayaran, membuka/menutup shift.</td></tr>
                                <tr><td><span class="badge-role">Kitchen</span></td><td>Kitchen display</td><td>Memproses item order dari pending sampai ready.</td></tr>
                                <tr><td><span class="badge-role">Waiter</span></td><td>Order meja</td><td>Membantu customer membuat pesanan dan mengantar makanan.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </article>

                <article id="payments" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-credit-card"></i> Pembayaran</div>
                    <h2>9. Pembayaran cash dan QRIS</h2>
                    <p class="lead-text">Kasir dapat menyelesaikan transaksi dengan metode pembayaran yang diaktifkan di setting tenant.</p>
                    <div class="feature-list">
                        <div class="feature-box"><i class="bi bi-cash"></i><h5>Cash</h5><p>Digunakan untuk pembayaran tunai di kasir. Status pembayaran diupdate setelah uang diterima.</p></div>
                        <div class="feature-box"><i class="bi bi-qr-code"></i><h5>QRIS Static</h5><p>Tenant dapat menyimpan payload QRIS static, lalu sistem membuat QR sesuai nominal transaksi jika fitur aktif.</p></div>
                        <div class="feature-box"><i class="bi bi-receipt-cutoff"></i><h5>Receipt</h5><p>Order yang selesai dapat digunakan sebagai dasar cetak struk thermal atau kitchen ticket pada pengembangan printer.</p></div>
                    </div>
                </article>

                <article id="reports" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-graph-up"></i> Reports & Analytics</div>
                    <h2>10. Laporan penjualan dan operasional</h2>
                    <p class="lead-text">Owner dan manager dapat memantau performa restoran dari order, pembayaran, inventory, dan outlet.</p>
                    <div class="feature-list">
                        <div class="feature-box"><i class="bi bi-bar-chart"></i><h5>Sales report</h5><p>Pantau total penjualan, transaksi, dan performa outlet dari data order.</p></div>
                        <div class="feature-box"><i class="bi bi-star"></i><h5>Best seller</h5><p>Analisis menu terlaris untuk membantu promo, stok bahan, dan strategi bundling.</p></div>
                        <div class="feature-box"><i class="bi bi-clipboard-data"></i><h5>Inventory report</h5><p>Lihat kondisi stok dan histori movement untuk mencegah selisih bahan.</p></div>
                    </div>
                </article>

                <article id="faq" class="doc-panel">
                    <div class="section-kicker"><i class="bi bi-question-circle"></i> FAQ</div>
                    <h2>Pertanyaan yang sering muncul</h2>
                    <div class="feature-list">
                        <div class="feature-box"><i class="bi bi-wifi"></i><h5>Apakah customer perlu install aplikasi?</h5><p>Tidak. Customer cukup scan QR meja dan membuka menu melalui browser HP.</p></div>
                        <div class="feature-box"><i class="bi bi-shop-window"></i><h5>Apakah bisa multi outlet?</h5><p>Bisa. Data outlet, meja, order, session kasir, dan inventory dipisahkan berdasarkan tenant dan outlet.</p></div>
                        <div class="feature-box"><i class="bi bi-person-lock"></i><h5>Apakah staff punya akses berbeda?</h5><p>Ya. Role owner, manager, cashier, kitchen, dan waiter digunakan untuk membatasi akses fitur.</p></div>
                        <div class="feature-box"><i class="bi bi-box-arrow-in-right"></i><h5>Login pakai URL apa?</h5><p>Staff bisa login melalui /login. Setelah berhasil, sistem otomatis mengarahkan ke dashboard tenant yang sesuai.</p></div>
                    </div>
                    <div class="callout"><strong>Butuh bantuan?</strong> Buka halaman <a href="{{ url('/contact') }}" class="fw-bold text-decoration-none">Kontak</a> untuk menghubungi tim VenResto.</div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
