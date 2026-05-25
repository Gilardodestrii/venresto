# Venresto

Venresto adalah aplikasi kasir POS berbasis web untuk resto, cafe, dan usaha kuliner. Aplikasi ini membantu pengelolaan outlet, menu, QR menu per meja, transaksi kasir, kitchen display, order, dan pembayaran dalam satu sistem.

## Fitur Utama

- Landing page aplikasi
- Registrasi dan login tenant
- Multi-tenant berbasis URL `{tenant}`
- Manajemen outlet
- Manajemen kategori menu
- Manajemen item menu
- QR menu per meja
- Pemesanan melalui QR menu
- POS kasir
- Kitchen display / tampilan dapur
- Manajemen order
- Update status pembayaran
- Integrasi webhook Midtrans
- Generate dan download QR meja

## Teknologi

Project ini dibangun menggunakan:

- PHP `^8.2`
- Laravel `^10.10`
- Laravel Sanctum
- Spatie Laravel Permission
- Simple QR Code
- Vite
- Axios
- MySQL / MariaDB

## Persyaratan Sistem

Pastikan perangkat/server sudah memiliki:

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- Database MySQL/MariaDB
- Git

## Instalasi Lokal

Clone repository:

```bash
git clone https://github.com/Gilardodestrii/venresto.git
cd venresto
```

Install dependency PHP:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

Copy file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Atur koneksi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=venresto
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migration:

```bash
php artisan migrate
```

Jalankan seeder jika tersedia:

```bash
php artisan db:seed
```

Jalankan server Laravel:

```bash
php artisan serve
```

Jalankan Vite untuk development:

```bash
npm run dev
```

Akses aplikasi melalui browser:

```text
http://127.0.0.1:8000
```

## Build Frontend Production

Untuk membuat asset frontend versi production:

```bash
npm run build
```

## Struktur Route Utama

Beberapa route utama pada aplikasi:

| Route | Keterangan |
|---|---|
| `/` | Landing page |
| `/pricing` | Halaman pricing |
| `/features` | Halaman fitur |
| `/signup` | Registrasi tenant |
| `/{tenant}/login` | Login tenant |
| `/{tenant}/admin/dashboard` | Dashboard admin |
| `/{tenant}/admin/outlets` | Manajemen outlet |
| `/{tenant}/admin/menu-categories` | Manajemen kategori menu |
| `/{tenant}/admin/menu-items` | Manajemen menu item |
| `/{tenant}/admin/pos` | POS kasir |
| `/{tenant}/admin/kitchen` | Kitchen display |
| `/{tenant}/admin/orders` | Manajemen order |
| `/{tenant}/qr/{table}` | QR menu pelanggan |

## Konfigurasi Midtrans

Aplikasi memiliki endpoint webhook Midtrans:

```text
POST /webhooks/midtrans
```

Tambahkan konfigurasi Midtrans pada `.env` sesuai kebutuhan project, misalnya server key, client key, dan mode environment.

## Perintah Artisan yang Sering Digunakan

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan optimize:clear
php artisan route:list
php artisan config:cache
php artisan storage:link
```

## Catatan Keamanan

- Jangan upload file `.env` ke repository.
- Pastikan `APP_DEBUG=false` pada server production.
- Hapus route debug seperti `/env-check` dan `/db-check` sebelum production.
- Gunakan password database yang kuat.
- Batasi akses dashboard admin hanya untuk user yang berwenang.

## Deployment Singkat

Untuk deployment ke server production:

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Pastikan document root server mengarah ke folder:

```text
public/
```

## Lisensi

Project ini dikembangkan untuk kebutuhan aplikasi POS Venresto.

## Developer

Dibuat oleh Gilardo Destri.
