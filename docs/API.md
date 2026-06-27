# 🔌 API Reference — VenResto

> **Versi:** 1.0
> **Tanggal:** 27 Juni 2026
> **Base URL:** `/api`
> **Auth:** Laravel Sanctum (Bearer Token)
> **Middleware:** `resolveTenant` (semua endpoint)

---

## 1. Overview

VenResto menyediakan REST API untuk integrasi dengan mobile app atau sistem eksternal. Semua endpoint berada di bawah middleware `resolveTenant` untuk multi-tenant isolation.

### Authentication
- API menggunakan **Laravel Sanctum** (token-based)
- Login via `/api/auth/login` → mendapatkan Bearer token
- Token dikirim via header: `Authorization: Bearer {token}`
- Endpoint publik: `/api/auth/login`, `/api/auth/register`
- Endpoint protected: semua endpoint lain (middleware `auth:sanctum`)

---

## 2. Auth Endpoints

### `POST /api/auth/login`
Login dan dapatkan access token.

**Request Body:**
```json
{
  "email": "kasir@resto.com",
  "password": "password123"
}
```

**Response 200:**
```json
{
  "user": {
    "id": 1,
    "name": "Kasir 1",
    "email": "kasir@resto.com",
    "tenant_id": 1
  },
  "token": "1|abc123def456..."
}
```

---

### `POST /api/auth/register`
Registrasi user baru.

**Request Body:**
```json
{
  "name": "Staff Baru",
  "email": "staff@resto.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

---

### `GET /api/me` 🔒
Ambil data user yang sedang login.

**Response 200:**
```json
{
  "id": 1,
  "name": "Kasir 1",
  "email": "kasir@resto.com",
  "tenant_id": 1,
  "roles": ["kasir"]
}
```

---

## 3. Settings Endpoints

### `GET /api/settings` 🔒
Ambil pengaturan tenant (pajak, service charge, dll).

**Response 200:**
```json
{
  "tax_rate": 10,
  "service_rate": 5,
  "qris_static_payload": "...",
  "payments_json": { ... }
}
```

---

### `PUT /api/settings` 🔒
Update pengaturan tenant.

**Request Body:**
```json
{
  "tax_rate": 11,
  "service_rate": 5
}
```

---

## 4. Menu Endpoints

### `GET /api/menu` 🔒
Ambil semua menu item (grouped by category).

**Response 200:**
```json
{
  "categories": [
    {
      "id": 1,
      "name": "Makanan",
      "items": [
        {
          "id": 1,
          "name": "Nasi Goreng",
          "price": 25000,
          "sku": "MKN-001",
          "image_url": "/storage/menu/nasi-goreng.jpg",
          "is_active": true
        }
      ]
    }
  ]
}
```

---

### `POST /api/menu/items` 🔒
Tambah menu item baru.

**Request Body:**
```json
{
  "category_id": 1,
  "name": "Ayam Bakar",
  "price": 35000,
  "sku": "MKN-002",
  "is_active": true
}
```

---

### `PATCH /api/menu/items/{item}` 🔒
Update menu item.

**Request Body:**
```json
{
  "name": "Ayam Bakar Spesial",
  "price": 40000
}
```

---

### `DELETE /api/menu/items/{item}` 🔒
Hapus menu item.

**Response 200:**
```json
{
  "message": "Menu item berhasil dihapus."
}
```

---

### `GET /api/menu/recommended` 🔒
Ambil menu item yang direkomendasikan / best seller.

---

## 5. Order Endpoints

### `GET /api/orders` 🔒
Ambil daftar order.

**Query Parameters:**
| Parameter | Tipe | Keterangan |
|-----------|------|------------|
| status | string | Filter: open, pending_payment, paid, void |
| outlet_id | int | Filter per outlet |
| page | int | Pagination |

**Response 200:**
```json
{
  "data": [
    {
      "id": 1,
      "code": "ORD-0001",
      "table_code": "A1",
      "order_type": "dine_in",
      "status": "open",
      "grand_total": 85000,
      "items": [
        {
          "name": "Nasi Goreng",
          "qty": 2,
          "price": 25000,
          "subtotal": 50000,
          "status": "pending"
        }
      ],
      "created_at": "2026-06-27T10:30:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 45
  }
}
```

---

### `POST /api/orders` 🔒
Buat order baru (dari POS / mobile app).

**Request Body:**
```json
{
  "outlet_id": 1,
  "table_code": "A1",
  "order_type": "dine_in",
  "customer_name": "John",
  "customer_note": "Tidak pedas",
  "items": [
    { "menu_item_id": 1, "qty": 2 },
    { "menu_item_id": 3, "qty": 1 }
  ]
}
```

---

### `POST /api/orders/{id}/pay` 🔒
Proses pembayaran order.

**Request Body:**
```json
{
  "method": "cash",
  "amount": 85000
}
```

---

## 6. Kitchen Endpoints

### `GET /api/kitchen/tickets` 🔒
Ambil tiket dapur (order items yang perlu diproses).

**Response 200:**
```json
{
  "tickets": [
    {
      "order_id": 1,
      "order_code": "ORD-0001",
      "table_code": "A1",
      "items": [
        {
          "id": 42,
          "name": "Nasi Goreng",
          "qty": 2,
          "status": "pending",
          "notes": "Tidak pedas"
        }
      ],
      "created_at": "2026-06-27T10:30:00Z"
    }
  ]
}
```

---

### `PATCH /api/kitchen/tickets/{id}` 🔒
Update status item kitchen.

**Request Body:**
```json
{
  "status": "preparing"
}
```

**Status Flow:** `pending` → `preparing` → `ready` → `served`

---

## 7. Report Endpoints

### `GET /api/reports/sales` 🔒
Laporan penjualan.

**Query Parameters:**
| Parameter | Tipe | Keterangan |
|-----------|------|------------|
| start_date | date | Tanggal mulai (YYYY-MM-DD) |
| end_date | date | Tanggal akhir |
| outlet_id | int | Filter per outlet |

---

### `GET /api/reports/top-items` 🔒
Item terlaris.

---

### `GET /api/reports/cashiers` 🔒
Laporan per kasir.

---

## 8. Web Routes (Non-API)

Selain REST API, VenResto juga memiliki web routes yang melayani halaman Blade:

### Public Routes
| Method | URL | Keterangan |
|--------|-----|------------|
| GET | `/` | Landing page |
| GET | `/pricing` | Halaman pricing |
| GET | `/features` | Halaman fitur |
| GET | `/signup` | Registrasi tenant |
| GET | `/login` | Login central |
| GET | `/{tenant}/login` | Login per tenant |
| POST | `/webhooks/midtrans` | Midtrans webhook |

### QR Menu (Public, No Auth)
| Method | URL | Keterangan |
|--------|-----|------------|
| GET | `/{tenant}/qr/outlets/{outlet}/tables/{table}` | Menu QR pelanggan |
| POST | `/{tenant}/qr/outlets/{outlet}/order` | Submit order QR |

### Admin Panel (Auth Required)
| Method | URL | Keterangan |
|--------|-----|------------|
| GET | `/{tenant}/admin/dashboard` | Dashboard |
| RESOURCE | `/{tenant}/admin/outlets` | CRUD Outlet |
| RESOURCE | `/{tenant}/admin/menu-categories` | CRUD Kategori |
| RESOURCE | `/{tenant}/admin/menu-items` | CRUD Menu Item |
| GET | `/{tenant}/admin/pos` | POS Kasir |
| POST | `/{tenant}/admin/pos/store` | Submit order POS |
| GET | `/{tenant}/admin/kitchen` | Kitchen Display |
| GET | `/{tenant}/admin/kitchen/live` | KDS live polling |
| GET | `/{tenant}/admin/orders` | List orders |
| GET | `/{tenant}/admin/orders/{order}` | Detail order |
| POST | `/{tenant}/admin/orders/{order}/void` | Void order |
| POST | `/{tenant}/admin/orders/{order}/payment` | Update payment |
| GET | `/{tenant}/admin/orders/{order}/receipt` | Cetak struk |
| GET/POST | `/{tenant}/admin/cashier-sessions/*` | Sesi kasir |
| RESOURCE | `/{tenant}/admin/materials` | CRUD Material |
| CRUD | `/{tenant}/admin/recipes/*` | CRUD Resep |
| GET | `/{tenant}/admin/menu-costing` | Food cost overview |
| GET | `/{tenant}/admin/stock-movements` | Log stok |
| CRUD | `/{tenant}/admin/stock-transfers/*` | Transfer stok |
| CRUD | `/{tenant}/admin/waste-records/*` | Waste record |
| GET | `/{tenant}/admin/roles` | Staff list |
| GET/POST | `/{tenant}/admin/roles/create` | Tambah staff |
| GET/PUT | `/{tenant}/admin/roles/{user}/edit` | Edit staff |
| POST | `/{tenant}/admin/roles/{user}/delete` | Hapus staff |
| GET/POST | `/{tenant}/admin/settings` | Tenant settings |
| POST | `/{tenant}/admin/qris-static/generate` | Generate QR QRIS |

---

## 9. Error Responses

### Format Error Standar
```json
{
  "message": "Deskripsi error",
  "errors": {
    "field_name": ["Pesan validasi"]
  }
}
```

### HTTP Status Codes
| Code | Keterangan |
|------|------------|
| 200 | OK — Request berhasil |
| 201 | Created — Resource berhasil dibuat |
| 401 | Unauthorized — Token tidak valid / belum login |
| 403 | Forbidden — Tidak punya akses |
| 404 | Not Found — Resource tidak ditemukan |
| 422 | Unprocessable — Validasi gagal |
| 429 | Too Many Requests — Rate limit |
| 500 | Server Error |

---

## 10. Catatan Penting

### Rate Limiting
- Login endpoint: throttled (Laravel default)
- API endpoints: default Laravel throttle

### Multi-Tenant Scoping
- Semua API endpoint menggunakan middleware `resolveTenant`
- Tenant di-resolve dari request context (token user → tenant_id)
- Data otomatis di-scope per tenant

### Planned Endpoints (Belum Aktif)
```php
// Sync endpoints (untuk offline support di masa depan)
// POST /api/sync/push
// POST /api/sync/pull
```

---

*Dokumen ini menjadi referensi untuk integrasi API VenResto dengan mobile app atau sistem lain.*
