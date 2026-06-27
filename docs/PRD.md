# 📋 Product Requirements Document (PRD) — VenResto

> **Versi:** 1.0
> **Tanggal:** 27 Juni 2026
> **Author:** Tim VenResto
> **Status:** Living Document

---

## 1. Overview & Tujuan Produk

**VenResto** adalah aplikasi Point of Sale (POS) berbasis web untuk restoran, kafe, dan usaha kuliner. Sistem ini dirancang sebagai platform **multi-tenant** — satu instalasi melayani banyak bisnis (tenant), masing-masing dengan data terpisah.

### Tujuan Utama
- Menyediakan sistem POS lengkap yang mudah digunakan untuk UMKM kuliner
- Mendukung operasional dari pemesanan hingga pembayaran dalam satu platform
- Menghadirkan QR Menu agar pelanggan bisa memesan langsung dari meja
- Menyediakan Kitchen Display System (KDS) untuk koordinasi dapur
- Mengelola inventory bahan baku, resep, dan food cost

### Value Proposition
- **All-in-one:** POS + QR Menu + Kitchen Display + Inventory dalam satu sistem
- **Multi-tenant:** Satu platform, banyak bisnis — efisien untuk SaaS
- **Self-service:** Pelanggan bisa order sendiri via scan QR, mengurangi beban pelayan
- **Real-time:** Kitchen display terupdate otomatis untuk koordinasi dapur-kasir

---

## 2. Target Pengguna (Personas)

### 👤 Owner / Pemilik Bisnis
- **Deskripsi:** Pemilik restoran/kafe yang mendaftar dan mengelola bisnis
- **Kebutuhan:** Dashboard penjualan, manajemen menu, kelola staff, laporan keuangan, pengaturan tenant
- **Akses:** Semua fitur admin

### 👤 Kasir
- **Deskripsi:** Staff yang mengoperasikan POS dan memproses pembayaran
- **Kebutuhan:** Buat order cepat, proses pembayaran (tunai/QRIS), buka/tutup sesi kasir, cetak struk
- **Akses:** POS, Order, Cashier Session

### 👤 Staff Dapur
- **Deskripsi:** Koki/staff dapur yang menyiapkan pesanan
- **Kebutuhan:** Lihat order masuk real-time, update status item (preparing → ready)
- **Akses:** Kitchen Display System

### 👤 Pelanggan (via QR Menu)
- **Deskripsi:** Pelanggan yang duduk di meja dan scan QR code
- **Kebutuhan:** Lihat menu digital, pilih item, masukkan ke keranjang, submit order
- **Akses:** Halaman QR menu (tanpa login, tanpa navbar aplikasi)

---

## 3. Fitur Utama

### 3.1 🌐 Landing Page & Marketing
| Aspek | Detail |
|-------|--------|
| Halaman | Home, Pricing, Features, Documentation, Privacy, Terms, Contact |
| Tujuan | Menampilkan informasi produk dan menarik tenant baru mendaftar |
| CTA | Tombol signup menuju halaman registrasi |

### 3.2 🔐 Autentikasi & Registrasi
- **Signup tenant:** Form registrasi dengan nama bisnis, slug unik, email, password
- **Google OAuth:** Signup & Login via akun Google (Socialite)
- **Slug check:** Validasi real-time ketersediaan slug tenant
- **Login:** Login per tenant (`/{tenant}/login`) dan central (`/login`)
- **Logout:** Hapus session, redirect ke landing
- **Tenant Switcher:** Superadmin bisa switch antar tenant

### 3.3 📊 Dashboard Admin
- Ringkasan penjualan hari ini
- Jumlah order, total revenue
- Order terbaru
- Akses cepat ke fitur utama

### 3.4 🏪 Manajemen Outlet
- **CRUD outlet:** Nama, alamat
- Setiap tenant bisa punya multiple outlet
- Data inventory, order, dan cashier session terikat per outlet

### 3.5 📂 Manajemen Menu
#### Kategori Menu
- CRUD kategori (nama)
- Tenant-scoped (tiap tenant punya kategori sendiri)

#### Menu Item
- CRUD menu item: nama, harga, SKU, gambar, status aktif/nonaktif
- Terikat ke kategori
- Gambar diupload dan disimpan via storage

### 3.6 📱 QR Menu (Customer-Facing)
- **Generate QR:** Admin generate QR code per meja per outlet
- **Download QR:** QR bisa didownload sebagai gambar
- **Customer flow:**
  1. Pelanggan scan QR di meja
  2. Halaman menu terbuka (layout minimal, tanpa navbar/login)
  3. Pilih item, atur qty, tambah ke keranjang
  4. Isi nama & nomor HP (opsional)
  5. Submit order
- **Teknologi:** Vanilla JS (`public/assets/js/qr/customer-order.js`), layout khusus (`layouts.qr-clean`)

### 3.7 💰 POS Kasir
- **Layout split:** Kiri = grid menu, Kanan = keranjang/order
- **Pilih outlet** sebelum mulai transaksi
- **Pilih tipe order:** Dine-in (pilih meja) / Takeaway
- **Tambah item** ke keranjang, atur qty
- **Kalkulasi otomatis:** Subtotal, pajak, service charge, grand total
- **Metode pembayaran:** Cash, QRIS (static & dynamic via Midtrans)
- **Submit order** → simpan ke database

### 3.8 🍳 Kitchen Display System (KDS)
- **Tampilan card-based:** Setiap order ditampilkan sebagai card
- **Status per item:** `pending` → `preparing` → `ready` → `served`
- **Update status:** Koki tap/klik untuk update status item
- **Live polling:** Halaman `/kitchen/live` auto-refresh untuk data terbaru
- **Filter:** Per outlet

### 3.9 📦 Manajemen Order
- **List order:** Dengan filter status (open, pending_payment, paid, void)
- **Detail order:** Info pelanggan, item, total, status pembayaran
- **Void order:** Batalkan order
- **Update payment:** Ubah status pembayaran manual
- **Struk/Receipt:** Halaman cetak struk per order

### 3.10 🏦 Cashier Session
- **Buka sesi:** Set opening cash, terikat ke outlet & user
- **Tutup sesi:** Input closing cash, kalkulasi selisih
- **Histori sesi:** List sesi kasir dengan detail

### 3.11 💳 Pembayaran & Midtrans
- **Cash:** Input langsung di POS
- **QRIS Static:** Generate QR pembayaran dari payload QRIS yang disimpan di settings
- **Midtrans Webhook:** Endpoint `POST /webhooks/midtrans` menerima notifikasi pembayaran
- **Payment model:** Simpan method, amount, status, midtrans_transaction_id

### 3.12 📦 Inventory & Bahan Baku
#### Material (Bahan Baku)
- CRUD material: nama, unit, stock qty, cost per unit
- Terikat ke tenant & outlet

#### Resep (Recipe)
- CRUD resep: terikat ke menu item
- Recipe items: material + qty yang dibutuhkan
- Kalkulasi food cost otomatis

#### Menu Costing
- Halaman overview food cost per menu item berdasarkan resep

#### Stock Movement
- Log pergerakan stok: masuk, keluar, adjustment
- Otomatis terpotong saat order dibayar (via `inventory_processed_at`)

#### Stock Transfer
- Transfer bahan baku antar outlet
- Status: pending → completed / cancelled
- Detail per item material & qty

#### Waste Record
- Catat pembuangan/kerusakan bahan baku
- Detail per item material & qty

### 3.13 👥 Manajemen Staff & Role
- **List staff** per tenant
- **Tambah staff** baru (nama, email, password, role)
- **Edit staff** (update profil & role)
- **Hapus staff**
- **Role assignment:** Menggunakan Spatie Permission dengan teams mode
- **Role default:** owner, kasir, dapur (custom role bisa ditambah)

### 3.14 ⚙️ Tenant Settings
- **Pajak (tax_rate):** Persentase pajak otomatis
- **Service charge (service_rate):** Persentase service charge
- **QRIS Static Payload:** Payload QR untuk pembayaran QRIS static
- **Payments JSON:** Konfigurasi metode pembayaran

### 3.15 📈 Laporan (Planned/Partial)
- **Inventory Report:** Stok bahan baku per outlet
- **Sales Report:** Penjualan per periode (controller exists)
- **Profit Report:** Margin keuntungan (controller exists)

---

## 4. User Stories

### Owner
- Sebagai owner, saya ingin **mendaftar bisnis baru** agar saya punya tenant sendiri
- Sebagai owner, saya ingin **menambah outlet** agar bisa kelola banyak cabang
- Sebagai owner, saya ingin **mengelola menu** (kategori & item) agar menu selalu update
- Sebagai owner, saya ingin **menambah staff** dengan role tertentu agar pembagian tugas jelas
- Sebagai owner, saya ingin **melihat laporan penjualan** agar tahu performa bisnis
- Sebagai owner, saya ingin **mengatur pajak dan service charge** agar perhitungan otomatis
- Sebagai owner, saya ingin **mengelola resep & food cost** agar kontrol HPP

### Kasir
- Sebagai kasir, saya ingin **membuka sesi kasir** agar transaksi tercatat rapi
- Sebagai kasir, saya ingin **membuat order baru** dari POS dengan cepat
- Sebagai kasir, saya ingin **memproses pembayaran** tunai atau QRIS
- Sebagai kasir, saya ingin **mencetak struk** untuk pelanggan
- Sebagai kasir, saya ingin **menutup sesi kasir** dan menghitung selisih kas

### Staff Dapur
- Sebagai staff dapur, saya ingin **melihat order masuk** secara real-time di layar dapur
- Sebagai staff dapur, saya ingin **mengupdate status item** (preparing/ready) agar kasir tahu

### Pelanggan
- Sebagai pelanggan, saya ingin **scan QR di meja** dan langsung lihat menu
- Sebagai pelanggan, saya ingin **memilih item dan checkout** tanpa perlu install aplikasi
- Sebagai pelanggan, saya ingin **memasukkan catatan khusus** untuk pesanan saya

---

## 5. Acceptance Criteria

### Registrasi Tenant
- ✅ User bisa signup dengan email/password atau Google OAuth
- ✅ Slug tenant harus unik, dicek real-time
- ✅ Setelah signup, otomatis jadi owner dengan role & permission
- ✅ Redirect ke dashboard setelah registrasi berhasil

### POS
- ✅ Kasir bisa pilih outlet, tipe order, dan meja
- ✅ Menu ditampilkan per kategori, bisa search
- ✅ Kalkulasi subtotal + tax + service otomatis
- ✅ Order tersimpan dengan status "open"
- ✅ Pembayaran cash/QRIS mengubah status ke "paid"

### QR Menu
- ✅ QR code terbuka tanpa login
- ✅ Halaman menggunakan layout minimal (tanpa navbar aplikasi)
- ✅ Pelanggan bisa tambah item ke cart dan submit order
- ✅ Order masuk ke sistem dengan status "open"

### Kitchen Display
- ✅ Order baru muncul otomatis (polling)
- ✅ Klik status item mengubah ke tahap berikutnya
- ✅ Visual berbeda per status (warna card)

### Inventory
- ✅ Stok terpotong otomatis saat order dibayar (berdasarkan resep)
- ✅ Transfer antar outlet tercatat lengkap
- ✅ Waste record mengurangi stok

---

## 6. Out of Scope (v1)

- ❌ Mobile app native (iOS/Android)
- ❌ Multi-bahasa / i18n (saat ini Bahasa Indonesia only)
- ❌ Loyalty program / membership pelanggan
- ❌ Reservasi meja online
- ❌ Integrasi delivery (GoFood, GrabFood, ShopeeFood)
- ❌ Accounting / pembukuan lengkap
- ❌ Multi-currency
- ❌ Offline mode / PWA

---

## 7. Asumsi & Dependensi

### Asumsi
- Tenant memiliki koneksi internet stabil
- Browser modern (Chrome, Firefox, Safari, Edge)
- Device untuk KDS berupa tablet/monitor terpisah
- Satu tenant minimal punya satu outlet

### Dependensi
- **Midtrans** untuk payment gateway (server key & client key)
- **Google Cloud** untuk OAuth (client ID & secret)
- **MySQL/MariaDB** sebagai database utama
- **Server** dengan PHP 8.2+, Node.js, Composer
- **Domain** untuk akses production

---

## 8. Metrics Keberhasilan

| Metric | Target |
|--------|--------|
| Waktu registrasi tenant | < 2 menit |
| Waktu buat order (POS) | < 30 detik |
| Waktu load QR menu | < 3 detik |
| Latency Kitchen Display update | < 5 detik |
| Uptime sistem | > 99% |
| Tenant aktif (6 bulan pertama) | 50+ tenant |
| Order per hari per tenant aktif | 20+ order |

---

## 9. Roadmap (Planned Features)

### v1.1
- Dashboard analytics yang lebih detail
- Laporan penjualan & profit yang lengkap
- Export laporan ke PDF/Excel
- Notifikasi order baru (bell/sound)

### v1.2
- PWA support untuk offline basic
- Customer feedback/rating per order
- Promo & diskon (voucher code)
- Multi-printer support untuk struk

### v2.0
- Mobile app (React Native / Flutter)
- Integrasi marketplace delivery
- Loyalty program
- Multi-bahasa

---

*Dokumen ini akan terus diperbarui seiring perkembangan project VenResto.*
