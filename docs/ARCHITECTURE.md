# 🏗️ Architecture Document — VenResto

> **Versi:** 1.0
> **Tanggal:** 27 Juni 2026
> **Pattern:** Monolith MVC (Laravel)

---

## 1. High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENTS                              │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │ Browser  │  │ QR Scan  │  │ KDS      │  │ Admin    │   │
│  │ (Owner)  │  │ (Customer)│  │ (Tablet) │  │ (Kasir)  │   │
│  └─────┬────┘  └─────┬────┘  └─────┬────┘  └─────┬────┘   │
└────────┼─────────────┼─────────────┼─────────────┼─────────┘
         │             │             │             │
         ▼             ▼             ▼             ▼
┌─────────────────────────────────────────────────────────────┐
│                    WEB SERVER (Nginx/Apache)                 │
│                    PHP 8.2 + Laravel 10                      │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌─────────────────────────────────────────────────────┐    │
│  │                 ROUTING LAYER                        │    │
│  │  /                    → Landing (public)             │    │
│  │  /signup, /login      → Auth (guest)                 │    │
│  │  /{tenant}/admin/*    → Admin Panel (auth)           │    │
│  │  /{tenant}/qr/*       → QR Menu (public)             │    │
│  │  /webhooks/midtrans   → Payment webhook              │    │
│  └─────────────┬───────────────────────────────────────┘    │
│                │                                            │
│  ┌─────────────▼───────────────────────────────────────┐    │
│  │              MIDDLEWARE STACK                         │    │
│  │  guest → auth → TenantContext → Role/Permission      │    │
│  └─────────────┬───────────────────────────────────────┘    │
│                │                                            │
│  ┌─────────────▼───────────────────────────────────────┐    │
│  │              CONTROLLERS (Central/)                  │    │
│  │  Dashboard, Outlet, Menu, POS, Kitchen, Order,       │    │
│  │  CashierSession, Material, Recipe, StockTransfer,    │    │
│  │  WasteRecord, Settings, Role, Receipt, Report        │    │
│  └─────────────┬───────────────────────────────────────┘    │
│                │                                            │
│  ┌─────────────▼───────────────────────────────────────┐    │
│  │              MODELS (Eloquent ORM)                   │    │
│  │  Tenant, User, Outlet, OutletTable, MenuCategory,    │    │
│  │  MenuItem, Order, OrderItem, Payment, CashierSession, │    │
│  │  Material, Recipe, StockMovement, StockTransfer, etc. │    │
│  └─────────────┬───────────────────────────────────────┘    │
│                │                                            │
│  ┌─────────────▼───────────────────────────────────────┐    │
│  │              SERVICES                                │    │
│  │  TenantContext, QrisStatic                            │    │
│  └─────────────────────────────────────────────────────┘    │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│                    FRONTEND (Blade + Vite)                   │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐                  │
│  │ Tailwind │  │ Alpine.js│  │ Axios    │                  │
│  │ CSS v4   │  │ (interac)│  │ (AJAX)   │                  │
│  └──────────┘  └──────────┘  └──────────┘                  │
├─────────────────────────────────────────────────────────────┤
│                    EXTERNAL SERVICES                        │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐                  │
│  │ Midtrans │  │ Google   │  │ QR Code  │                  │
│  │ Payment  │  │ OAuth    │  │ (local)  │                  │
│  └──────────┘  └──────────┘  └──────────┘                  │
├─────────────────────────────────────────────────────────────┤
│                    DATABASE                                 │
│  ┌──────────────────────────────────────────────────────┐   │
│  │             MySQL / MariaDB (Single DB)              │   │
│  │         tenant_id column isolation strategy          │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. Tech Stack Detail

### Backend
| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Language | PHP | ^8.2 |
| Framework | Laravel | ^10.10 |
| Auth | Laravel Sanctum | ^3.3 |
| RBAC | Spatie Laravel Permission | ^6.x (teams mode) |
| QR Code | SimpleSoftwareIO/QrCode | ^4.2 |
| OAuth | Laravel Socialite | ^5.x |

### Frontend
| Komponen | Teknologi | Keterangan |
|----------|-----------|------------|
| Template Engine | Blade | Server-side rendering |
| CSS Framework | Tailwind CSS | v4.x (via @tailwindcss/vite) |
| Build Tool | Vite | ^5.x (via laravel-vite-plugin) |
| JS Interactivity | Alpine.js | Declarative UI interactions |
| HTTP Client | Axios | AJAX calls dari browser |
| QR Menu JS | Vanilla JS | public/assets/js/qr/customer-order.js |

### Database
| Komponen | Teknologi |
|----------|-----------|
| RDBMS | MySQL 8.0+ / MariaDB 10.6+ |
| ORM | Eloquent (Laravel built-in) |
| Migrations | Laravel Migrations |

### External Services
| Service | Kegunaan |
|---------|----------|
| Midtrans | Payment gateway (QRIS, VA, dll) |
| Google OAuth | Login & signup via Google |

---

## 3. Multi-Tenancy Architecture

### Strategy: Single Database, Shared Schema
VenResto menggunakan pendekatan **column-based multi-tenancy** — semua tenant berbagi satu database dan satu set tabel, dibedakan oleh kolom `tenant_id`.

### Tenant Resolution Flow
```
Request masuk: GET /warung-bahari/admin/dashboard
                     ↓
    Route parameter: {tenant} = "warung-bahari"
                     ↓
    Middleware/TenantContext::resolve("warung-bahari")
                     ↓
    Query: SELECT * FROM tenants WHERE slug = 'warung-bahari'
                     ↓
    TenantContext::set($tenant)  ← Simpan di singleton/request scope
                     ↓
    Controller bisa akses: TenantContext::get() → Tenant model
                     ↓
    Semua query discope: ->where('tenant_id', $tenant->id)
```

### TenantContext Service
```php
// app/Services/TenantContext.php
class TenantContext {
    public static function set(Tenant $tenant): void;
    public static function get(): ?Tenant;
}
```

### URL Pattern
```
Public:       /                           (landing)
Auth:         /login, /signup             (central)
              /{tenant}/login             (tenant-specific)
Admin:        /{tenant}/admin/dashboard
              /{tenant}/admin/outlets
              /{tenant}/admin/pos
              /{tenant}/admin/kitchen/*
QR Menu:      /{tenant}/qr/outlets/{outlet}/tables/{table}
Webhook:      /webhooks/midtrans          (no tenant prefix)
```

### Middleware & Route Groups
```php
// routes/web.php — 3 main groups:

// 1. Public (guest middleware)
Route::middleware('guest')->group(fn() => ...);

// 2. Tenant Admin (auth + prefix + name)
Route::middleware(['auth'])
    ->prefix('{tenant}/admin')
    ->name('tenant.admin.')
    ->group(fn() => ...);

// 3. Kitchen (auth + prefix + name)
Route::middleware(['auth'])
    ->prefix('{tenant}/admin/kitchen')
    ->name('tenant.admin.kitchen.')
    ->group(fn() => ...);

// 4. QR Menu (public, no auth)
Route::prefix('{tenant}/qr')->group(fn() => ...);
```

---

## 4. Directory Structure

```
venresto/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Central/              ← Semua admin controllers
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── OutletController.php
│   │   │   │   ├── MenuCategoryController.php
│   │   │   │   ├── MenuItemController.php
│   │   │   │   ├── PosController.php
│   │   │   │   ├── KitchenDisplayController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── CashierSessionController.php
│   │   │   │   ├── MaterialController.php
│   │   │   │   ├── RecipeController.php
│   │   │   │   ├── MenuCostingController.php
│   │   │   │   ├── StockMovementController.php
│   │   │   │   ├── StockTransferController.php
│   │   │   │   ├── WasteRecordController.php
│   │   │   │   ├── TenantSettingController.php
│   │   │   │   ├── ReceiptController.php
│   │   │   │   ├── QrAdminController.php
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── SignupController.php
│   │   │   │   ├── LandingController.php
│   │   │   │   ├── MidtransWebhookController.php
│   │   │   │   ├── RoleManagementController.php
│   │   │   │   ├── SalesReportController.php
│   │   │   │   ├── ProfitReportController.php
│   │   │   │   ├── InventoryReportController.php
│   │   │   │   └── QrisStaticController.php
│   │   │   ├── Api/                  ← API controllers
│   │   │   │   ├── KitchenController.php
│   │   │   │   └── MenuController.php
│   │   │   └── QrMenuController.php  ← Customer QR menu
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Tenant.php
│   │   ├── User.php
│   │   ├── Outlet.php
│   │   ├── OutletTable.php
│   │   ├── MenuCategory.php
│   │   ├── MenuItem.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Payment.php
│   │   ├── CashierSession.php
│   │   ├── Material.php
│   │   ├── Recipe.php
│   │   ├── StockMovement.php
│   │   ├── StockTransfer.php
│   │   ├── StockTransferItem.php
│   │   ├── WasteRecord.php
│   │   ├── WasteRecordItem.php
│   │   ├── TenantSetting.php
│   │   ├── Plan.php
│   │   ├── Subscription.php
│   │   └── Shift.php
│   ├── Services/
│   │   └── TenantContext.php         ← Tenant resolution
│   └── Support/
│       └── QrisStatic.php            ← QRIS payload manipulation
├── resources/
│   ├── css/
│   │   └── app.css                   ← Tailwind v4 entry (@import "tailwindcss")
│   ├── js/
│   │   └── app.js                    ← Alpine.js, Axios
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php         ← Admin layout (sidebar + topbar)
│       │   ├── marketing.blade.php   ← Landing page layout
│       │   └── qr-clean.blade.php    ← QR menu layout (minimal, no navbar)
│       ├── admin/                    ← Admin panel views
│       │   ├── dashboard.blade.php
│       │   ├── outlets/
│       │   ├── menu-categories/
│       │   ├── menu-items/
│       │   ├── pos/
│       │   ├── kitchen/
│       │   ├── orders/
│       │   ├── cashier-sessions/
│       │   ├── materials/
│       │   ├── recipes/
│       │   ├── stock-transfers/
│       │   ├── waste-records/
│       │   ├── roles/
│       │   ├── settings/
│       │   └── reports/
│       ├── landing/                  ← Public marketing pages
│       └── qr/                       ← Customer QR menu views
├── public/
│   ├── assets/
│   │   └── js/
│   │       └── qr/
│   │           └── customer-order.js ← QR menu cart/checkout logic
│   └── build/                        ← Vite compiled assets
├── routes/
│   ├── web.php                       ← All web routes
│   └── api.php                       ← API routes (kitchen, menu)
├── database/
│   └── migrations/                   ← 22 migration files
├── config/
│   ├── permission.php                ← Spatie config (teams: true)
│   └── ...
├── vite.config.js                    ← Vite + Tailwind plugin
├── package.json
├── composer.json
└── docs/                             ← 📄 Dokumentasi ini
```

---

## 5. Request Lifecycle

```
Browser Request
       ↓
  Nginx/Apache → public/index.php
       ↓
  Laravel Bootstrap (bootstrap/app.php)
       ↓
  Service Providers (app/Providers/)
       ↓
  HTTP Kernel → Global Middleware
       ↓
  Router (routes/web.php) → Match route
       ↓
  Route Middleware (auth, guest, throttle)
       ↓
  Controller Method
       ↓
  TenantContext::get() → Resolve tenant dari {tenant} slug
       ↓
  Business Logic (query with tenant_id scope)
       ↓
  Return View (Blade) / JSON Response
       ↓
  Browser renders HTML + Vite-compiled CSS/JS
```

---

## 6. Payment Flow (Midtrans)

```
┌──────────┐     ┌──────────┐     ┌──────────┐
│  Kasir   │     │ VenResto │     │ Midtrans │
│  (POS)   │     │  Server  │     │  API     │
└────┬─────┘     └────┬─────┘     └────┬─────┘
     │                │                │
     │ 1. Submit order│                │
     │ (QRIS payment) │                │
     ├───────────────►│                │
     │                │ 2. Create txn  │
     │                ├───────────────►│
     │                │ 3. QR/URL      │
     │                │◄───────────────┤
     │ 4. Show QR     │                │
     │◄───────────────┤                │
     │                │                │
     │    .... Customer pays via QRIS app ....
     │                │                │
     │                │ 5. Webhook POST│
     │                │  /webhooks/    │
     │                │  midtrans      │
     │                │◄───────────────┤
     │                │ 6. Verify &    │
     │                │ update order   │
     │                │ status → paid  │
     │                │ 7. 200 OK      │
     │                ├───────────────►│
     │ 8. Order paid  │                │
     │◄───────────────┤                │
```

### QRIS Static Flow (Alternatif)
```
1. Owner simpan QRIS payload di Tenant Settings
2. Kasir submit order → POST /qris-static/generate
3. Server inject amount ke payload → generate QR SVG
4. Return QR sebagai data:image/svg+xml;base64
5. Kasir tunjukkan QR ke pelanggan
6. Pembayaran dikonfirmasi manual oleh kasir
```

---

## 7. QR Order Flow

```
┌──────────┐     ┌──────────┐     ┌──────────┐
│ Customer │     │ VenResto │     │ Database │
│  (HP)    │     │  Server  │     │          │
└────┬─────┘     └────┬─────┘     └────┬─────┘
     │                │                │
     │ 1. Scan QR     │                │
     │ (URL: /{tenant}│                │
     │  /qr/outlets/  │                │
     │  {outlet}/     │                │
     │  tables/{table})│                │
     ├───────────────►│                │
     │                │ 2. Load menu   │
     │                ├───────────────►│
     │                │◄───────────────┤
     │ 3. Render menu │                │
     │  (qr-clean     │                │
     │   layout)      │                │
     │◄───────────────┤                │
     │                │                │
     │ 4. Customer pilih item,        │
     │    tambah ke cart (JS lokal)    │
     │                │                │
     │ 5. Submit order│                │
     │ POST /{tenant}/│                │
     │ qr/outlets/    │                │
     │ {outlet}/order │                │
     ├───────────────►│                │
     │                │ 6. Create order│
     │                │ + order_items  │
     │                ├───────────────►│
     │                │◄───────────────┤
     │ 7. Confirmation│                │
     │◄───────────────┤                │
```

**Catatan Penting:**
- QR menu menggunakan layout `qr-clean` — **tanpa navbar, tanpa footer, tanpa login**
- Cart logic sepenuhnya client-side di `customer-order.js`
- Order masuk dengan status `open`, payment diproses oleh kasir

---

## 8. Kitchen Display Flow

```
┌──────────┐     ┌──────────┐     ┌──────────┐
│  KDS     │     │ VenResto │     │ Database │
│ (Tablet) │     │  Server  │     │          │
└────┬─────┘     └────┬─────┘     └────┬─────┘
     │                │                │
     │ 1. Load KDS    │                │
     │ GET /kitchen   │                │
     ├───────────────►│                │
     │ 2. Render page │                │
     │◄───────────────┤                │
     │                │                │
     │ 3. Poll /live  │                │
     │ (setiap N dtk) │                │
     ├───────────────►│                │
     │                │ 4. Query open  │
     │                │ orders + items │
     │                ├───────────────►│
     │                │◄───────────────┤
     │ 5. Return JSON │                │
     │◄───────────────┤                │
     │                │                │
     │ 6. Koki tap    │                │
     │ update status  │                │
     │ POST /item/    │                │
     │ {id}/status    │                │
     ├───────────────►│                │
     │                │ 7. Update      │
     │                │ order_item     │
     │                │ status         │
     │                ├───────────────►│
     │                │◄───────────────┤
     │ 8. Confirmed   │                │
     │◄───────────────┤                │
```

**Mekanisme:** HTTP Polling (bukan WebSocket)
- Halaman `/kitchen/live` di-poll setiap beberapa detik
- Item status: `pending` → `preparing` → `ready` → `served`

---

## 9. Inventory Flow

```
Order dibayar (status → paid)
         ↓
  Check: inventory_processed_at IS NULL?
         ↓ (ya)
  For each order_item:
    ├── Cari recipe untuk menu_item_id
    ├── For each recipe_item:
    │     ├── material_id + qty_needed = recipe_item.qty × order_item.qty
    │     ├── materials.stock_qty -= qty_needed
    │     └── stock_movements.create(type: 'out', reference: order)
    └── Done
         ↓
  Set inventory_processed_at = now()
```

**Stock Transfer Flow:**
```
1. Buat transfer (from_outlet → to_outlet, status: pending)
2. Tambah items (material_id + qty)
3. Complete transfer:
   ├── from_outlet: material.stock_qty -= qty
   ├── to_outlet: material.stock_qty += qty
   ├── stock_movements: create 'out' + 'in' records
   └── status → completed
4. Atau cancel: status → cancelled (no stock change)
```

---

## 10. Security Architecture

### Authentication
- **Session-based auth** (Laravel default)
- **Laravel Sanctum** untuk API token (opsional, tersedia)
- **Google OAuth** via Laravel Socialite
- **Throttle** pada endpoint login (`middleware('throttle:login')`)

### Authorization
- **Spatie Permission** dengan teams mode
- **Roles:** owner, kasir, dapur (per tenant)
- **Permissions:** granular per fitur (assigned via role)
- **Tenant isolation:** Setiap query discope per `tenant_id`

### Data Isolation
```
Setiap request → resolve tenant dari URL slug
              → set TenantContext
              → semua query WHERE tenant_id = ?
              → user hanya bisa akses data tenant sendiri
```

### Security Best Practices
- `.env` tidak di-commit ke git
- Debug routes (`/env-check`, `/db-check`) di-comment out
- Password di-hash via `Hash::make()` (bcrypt)
- CSRF protection pada semua POST/PUT/DELETE
- XSS prevention via Blade `{{ }}` escaping
- `APP_DEBUG=false` untuk production

---

## 11. Deployment Architecture

### Production Setup
```
┌──────────────────────────────────────────┐
│            AAPanel Server                │
│  ┌────────────────────────────────────┐  │
│  │           Nginx                    │  │
│  │  (reverse proxy, SSL termination)  │  │
│  └──────────────┬─────────────────────┘  │
│                 ↓                         │
│  ┌────────────────────────────────────┐  │
│  │      PHP-FPM 8.2                   │  │
│  │      Laravel Application           │  │
│  │      Document Root: public/        │  │
│  └──────────────┬─────────────────────┘  │
│                 ↓                         │
│  ┌────────────────────────────────────┐  │
│  │      MySQL / MariaDB               │  │
│  │      Database: venresto            │  │
│  └────────────────────────────────────┘  │
│                                          │
│  ┌────────────────────────────────────┐  │
│  │      Node.js (build only)          │  │
│  │      npm run build → public/build/ │  │
│  └────────────────────────────────────┘  │
└──────────────────────────────────────────┘
```

### Deployment Steps
```bash
cd /www/wwwroot/app.venresto.biz.id
git pull origin main
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### Environment
- **Development:** Mini PC (hostname: venresto), Ubuntu + CasaOS, port 8000
- **Production:** AAPanel server, domain: app.venresto.biz.id
- **Repository:** GitHub (Gilardodestrii/venresto), branch: main

---

*Dokumen ini menjadi referensi arsitektur untuk pengembangan dan onboarding developer baru di project VenResto.*
