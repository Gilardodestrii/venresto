# 🗄️ Database Design — VenResto

> **Versi:** 1.0
> **Tanggal:** 27 Juni 2026
> **Engine:** MySQL / MariaDB
> **Multi-tenancy:** Single database, tenant_id column strategy

---

## 1. ERD Overview (Relasi Utama)

```
tenants ─────────┬──── users
                 │       └──── cashier_sessions
                 │
                 ├──── outlets
                 │       ├──── outlet_tables
                 │       ├──── orders ──── order_items
                 │       │       └──── payments
                 │       ├──── materials
                 │       ├──── stock_movements
                 │       ├──── stock_transfers ──── stock_transfer_items
                 │       ├──── waste_records ──── waste_record_items
                 │       └──── cashier_sessions
                 │
                 ├──── menu_categories ──── menu_items
                 │                             └──── recipes ──── (material linkage)
                 │
                 ├──── tenant_settings
                 │
                 └──── subscriptions ──── plans

roles ──── model_has_roles ──── users
       └── role_has_permissions ──── permissions
```

---

## 2. Tabel-tabel Utama

### 2.1 `tenants`
> Entitas bisnis/restoran. Setiap tenant punya slug unik untuk URL.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| name | varchar(255) | Nama bisnis |
| slug | varchar(255) UNIQUE | Slug URL (contoh: "warung-bahari") |
| owner_user_id | bigint FK → users.id | User pemilik tenant |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.2 `users`
> Semua user (owner, kasir, staff dapur) di semua tenant.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | Auto increment |
| tenant_id | bigint FK → tenants.id | NULL untuk superadmin |
| name | varchar(255) | Nama lengkap |
| email | varchar(255) UNIQUE | Email login |
| email_verified_at | timestamp NULL | |
| password | varchar(255) | Hashed password |
| google_id | varchar(255) NULL | Google OAuth ID |
| remember_token | varchar(100) NULL | |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.3 `outlets`
> Cabang/lokasi restoran milik tenant.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | Auto-set via TenantContext |
| name | varchar(255) | Nama outlet |
| address | text NULL | Alamat lengkap |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.4 `outlet_tables`
> Meja-meja di setiap outlet untuk QR menu & dine-in order.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| outlet_id | bigint FK → outlets.id | |
| table_code | varchar(50) | Kode meja (A1, A2, B1, ...) |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.5 `menu_categories`
> Kategori menu (Makanan, Minuman, Snack, dll).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | |
| name | varchar(255) | Nama kategori |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.6 `menu_items`
> Item menu yang dijual.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | |
| category_id | bigint FK → menu_categories.id | |
| name | varchar(255) | Nama menu |
| price | decimal(12,2) | Harga jual |
| sku | varchar(100) NULL | Stock Keeping Unit |
| image_url | varchar(500) NULL | Path gambar menu |
| is_active | boolean DEFAULT true | Status aktif/nonaktif |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.7 `orders`
> Transaksi order dari POS atau QR menu.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | |
| outlet_id | bigint FK → outlets.id | |
| code | varchar(50) | Kode order unik (ORD-XXXX) |
| table_code | varchar(50) NULL | Kode meja (NULL jika takeaway) |
| order_type | varchar(20) DEFAULT 'dine_in' | dine_in / takeaway |
| customer_name | varchar(255) NULL | Nama pelanggan (dari QR) |
| customer_phone | varchar(30) NULL | Nomor HP pelanggan |
| customer_note | text NULL | Catatan khusus |
| status | varchar(20) DEFAULT 'open' | open / pending_payment / paid / void |
| subtotal | decimal(12,2) | Total sebelum pajak |
| discount | decimal(12,2) DEFAULT 0 | Diskon |
| tax | decimal(12,2) DEFAULT 0 | Nominal pajak |
| service | decimal(12,2) DEFAULT 0 | Nominal service charge |
| grand_total | decimal(12,2) | Total akhir |
| payment_method | varchar(50) NULL | cash / qris / midtrans |
| cashier_id | bigint FK → users.id NULL | Kasir yang memproses |
| inventory_processed_at | timestamp NULL | Waktu stok terpotong |
| created_at | timestamp | |
| updated_at | timestamp | |

**Scopes:**
- `paid` → status = 'paid'
- `pending` → status = 'pending_payment'
- `open` → status = 'open'

### 2.8 `order_items`
> Detail item dalam order.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| order_id | bigint FK → orders.id | |
| menu_item_id | bigint FK → menu_items.id | |
| name | varchar(255) | Snapshot nama menu |
| price | decimal(12,2) | Snapshot harga saat order |
| qty | int | Jumlah |
| subtotal | decimal(12,2) | price × qty |
| status | varchar(20) DEFAULT 'pending' | pending / preparing / ready / served |
| notes | text NULL | Catatan per item |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.9 `payments`
> Record pembayaran per order. Satu order bisa punya multiple payment attempts.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| order_id | bigint FK → orders.id | |
| method | varchar(50) | cash / qris / midtrans |
| amount | decimal(12,2) | Jumlah dibayar |
| status | varchar(20) | pending / success / failed |
| midtrans_transaction_id | varchar(255) NULL | ID transaksi Midtrans |
| midtrans_payload | json NULL | Raw response Midtrans |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.10 `cashier_sessions`
> Sesi kasir: buka shift → tutup shift.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | |
| outlet_id | bigint FK → outlets.id | |
| user_id | bigint FK → users.id | Kasir |
| opened_at | timestamp | Waktu buka sesi |
| closed_at | timestamp NULL | Waktu tutup sesi |
| opening_cash | decimal(12,2) | Kas awal |
| closing_cash | decimal(12,2) NULL | Kas akhir (diisi saat tutup) |
| notes | text NULL | Catatan sesi |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.11 `materials`
> Bahan baku / inventory item.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | |
| outlet_id | bigint FK → outlets.id | |
| name | varchar(255) | Nama bahan (Beras, Ayam, dll) |
| unit | varchar(50) | Satuan (kg, liter, pcs, gram) |
| stock_qty | decimal(12,3) | Jumlah stok saat ini |
| cost_per_unit | decimal(12,2) DEFAULT 0 | Harga per satuan |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.12 `recipes`
> Resep menu — menghubungkan menu item ke bahan baku yang dibutuhkan.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | |
| menu_item_id | bigint FK → menu_items.id | |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.13 `recipe_items` (pivot)
> Detail bahan dalam resep.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| recipe_id | bigint FK → recipes.id | |
| material_id | bigint FK → materials.id | |
| qty | decimal(12,3) | Jumlah bahan per porsi |

### 2.14 `stock_movements`
> Log pergerakan stok (masuk, keluar, adjustment).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | |
| outlet_id | bigint FK → outlets.id | |
| material_id | bigint FK → materials.id | |
| type | varchar(20) | in / out / adjustment |
| qty | decimal(12,3) | Jumlah (positif = masuk, negatif = keluar) |
| note | text NULL | Keterangan |
| reference_type | varchar(50) NULL | order / transfer / waste / manual |
| reference_id | bigint NULL | ID referensi |
| user_id | bigint FK → users.id NULL | User yang melakukan |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.15 `stock_transfers`
> Header transfer stok antar outlet.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | |
| from_outlet_id | bigint FK → outlets.id | Outlet asal |
| to_outlet_id | bigint FK → outlets.id | Outlet tujuan |
| status | varchar(20) DEFAULT 'pending' | pending / completed / cancelled |
| notes | text NULL | |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.16 `stock_transfer_items`
> Detail item transfer.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| stock_transfer_id | bigint FK → stock_transfers.id | |
| material_id | bigint FK → materials.id | |
| qty | decimal(12,3) | Jumlah ditransfer |

### 2.17 `waste_records`
> Header catatan waste/kerusakan bahan.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | |
| outlet_id | bigint FK → outlets.id | |
| notes | text NULL | |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.18 `waste_record_items`
> Detail item waste.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| waste_record_id | bigint FK → waste_records.id | |
| material_id | bigint FK → materials.id | |
| qty | decimal(12,3) | Jumlah terbuang |
| reason | text NULL | Alasan pembuangan |

### 2.19 `tenant_settings`
> Pengaturan per tenant (pajak, service charge, payment config).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id UNIQUE | Satu row per tenant |
| tax_rate | decimal(5,2) DEFAULT 0 | Persentase pajak (10 = 10%) |
| service_rate | decimal(5,2) DEFAULT 0 | Persentase service charge |
| qris_static_payload | text NULL | QRIS static payload string |
| payments_json | json NULL | Konfigurasi metode pembayaran |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.20 `plans`
> Paket langganan (Free, Pro, Enterprise).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| name | varchar(255) | Nama plan |
| price | decimal(12,2) | Harga per bulan |
| features | json NULL | List fitur |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.21 `subscriptions`
> Langganan tenant ke plan tertentu.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| tenant_id | bigint FK → tenants.id | |
| plan_id | bigint FK → plans.id | |
| starts_at | timestamp | Mulai langganan |
| ends_at | timestamp NULL | Berakhir langganan |
| status | varchar(20) | active / expired / cancelled |
| created_at | timestamp | |
| updated_at | timestamp | |

### 2.22 Spatie Permission Tables
> Managed oleh `spatie/laravel-permission` dengan teams mode.

| Tabel | Keterangan |
|-------|------------|
| `roles` | id, name, guard_name, **tenant_id** (team FK) |
| `permissions` | id, name, guard_name, **tenant_id** |
| `model_has_roles` | role_id, model_type, model_id, **tenant_id** |
| `model_has_permissions` | permission_id, model_type, model_id, **tenant_id** |
| `role_has_permissions` | role_id, permission_id, **tenant_id** |

---

## 3. Index & Foreign Keys Penting

### Primary Indexes
- Semua tabel memiliki primary key `id` (auto-increment bigint)

### Foreign Keys
```sql
-- Multi-tenant scoping
users.tenant_id             → tenants.id
outlets.tenant_id           → tenants.id
menu_categories.tenant_id   → tenants.id
menu_items.tenant_id        → tenants.id
orders.tenant_id            → tenants.id
materials.tenant_id         → tenants.id
...semua tabel tenant-scoped

-- Relasi utama
outlet_tables.outlet_id     → outlets.id
menu_items.category_id      → menu_categories.id
orders.outlet_id            → outlets.id
orders.cashier_id           → users.id
order_items.order_id        → orders.id
order_items.menu_item_id    → menu_items.id
payments.order_id           → orders.id
cashier_sessions.outlet_id  → outlets.id
cashier_sessions.user_id    → users.id
recipes.menu_item_id        → menu_items.id
stock_transfers.from_outlet_id → outlets.id
stock_transfers.to_outlet_id   → outlets.id
```

### Composite / Additional Indexes (Recommended)
```sql
-- Fast tenant lookup
INDEX idx_orders_tenant_status (tenant_id, status)
INDEX idx_orders_outlet_date (outlet_id, created_at)
INDEX idx_materials_tenant_outlet (tenant_id, outlet_id)
INDEX idx_stock_movements_material (material_id, created_at)
INDEX idx_outlet_tables_outlet (outlet_id, table_code)
UNIQUE idx_tenant_slug (slug) -- on tenants table
UNIQUE idx_user_email (email) -- on users table
```

---

## 4. Multi-Tenancy Strategy

### Pendekatan: Single Database, Shared Tables
- **Semua tabel** yang berhubungan dengan data bisnis memiliki kolom `tenant_id`
- **Isolasi data** dilakukan di level aplikasi (query scope, middleware)
- **BUKAN** database-per-tenant atau schema-per-tenant

### TenantContext Service
```php
// app/Services/TenantContext.php
// Resolve tenant dari URL parameter {tenant} (slug)
// Set global context yang dipakai seluruh request lifecycle
TenantContext::get(); // → Tenant model atau null
```

### Auto-scoping
- Model `Outlet` menggunakan `booted()` method untuk auto-set `tenant_id` saat creating
- Controller query selalu filter `where('tenant_id', $tenant->id)`

### Catatan Penting
> ⚠️ **Spatie Permission Teams Mode:** `tenant_id` digunakan sebagai `team_foreign_key`. Harus selalu call `setPermissionsTeamId($tenant->id)` sebelum operasi role/permission. `syncPermissions()` TIDAK auto-inject tenant_id — gunakan manual insert untuk `role_has_permissions`.

---

## 5. Catatan Teknis

### Decimal Precision
- **Uang (harga, total, payment):** `decimal(12,2)` — 2 desimal
- **Stok & qty bahan:** `decimal(12,3)` — 3 desimal (gram, ml presisi)
- **Persentase (tax, service):** `decimal(5,2)` — contoh: 10.50%

### Soft Delete
- Saat ini **tidak digunakan** (hard delete)
- Recommended untuk v2: tambah `deleted_at` pada orders, menu_items, users

### Timestamp
- Semua tabel menggunakan `created_at` dan `updated_at` (Laravel default)
- `cashier_sessions` punya `opened_at` dan `closed_at` terpisah

### JSON Columns
- `tenant_settings.payments_json` — konfigurasi payment methods
- `payments.midtrans_payload` — raw Midtrans webhook data
- `plans.features` — list fitur per plan

---

*Dokumen ini menjadi referensi untuk pengembangan fitur dan migrasi database VenResto.*
