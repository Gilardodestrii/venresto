# 🎨 UI Design Specification — VenResto

> **Versi:** 1.0
> **Tanggal:** 27 Juni 2026
> **Tech:** Tailwind CSS v4, Blade Templates, Alpine.js

---

## 1. Design System

### 1.1 Color Palette

```
Primary (Brand)
├── primary-50:   #eff6ff    (bg sangat terang)
├── primary-100:  #dbeafe
├── primary-500:  #3b82f6    (tombol utama, link)
├── primary-600:  #2563eb    (hover)
├── primary-700:  #1d4ed8    (active/pressed)
└── primary-900:  #1e3a5f    (sidebar bg)

Secondary (Accent)
├── amber-400:    #fbbf24    (highlight, badge promo)
├── amber-500:    #f59e0b
└── amber-600:    #d97706

Neutral
├── gray-50:      #f9fafb    (page background)
├── gray-100:     #f3f4f6    (card background)
├── gray-200:     #e5e7eb    (border)
├── gray-500:     #6b7280    (secondary text)
├── gray-700:     #374151    (body text)
└── gray-900:     #111827    (heading text)

Semantic
├── success:      #10b981    (green - paid, ready, aktif)
├── warning:      #f59e0b    (amber - pending, preparing)
├── danger:       #ef4444    (red - void, hapus, error)
├── info:         #3b82f6    (blue - info, new order)
└── muted:        #9ca3af    (gray - disabled, inactive)
```

### 1.2 Typography

```
Font Family:      'Inter', sans-serif (via Google Fonts / Tailwind default)
Font Fallback:    system-ui, -apple-system, sans-serif

Heading 1:        text-2xl  (1.5rem)   font-bold     — Page title
Heading 2:        text-xl   (1.25rem)  font-semibold  — Section title
Heading 3:        text-lg   (1.125rem) font-semibold  — Card title
Body:             text-sm   (0.875rem) font-normal    — Default text
Caption:          text-xs   (0.75rem)  font-normal    — Label, hint
```

### 1.3 Spacing & Grid

```
Base unit:        4px (Tailwind default)
Page padding:     p-4 (mobile) / p-6 (desktop)
Card padding:     p-4 / p-6
Gap antar card:   gap-4 / gap-6
Grid columns:     grid-cols-1 (mobile) → grid-cols-2 (md) → grid-cols-3/4 (lg)
Sidebar width:    w-64 (256px) — fixed di desktop, overlay di mobile
Content max-w:    max-w-7xl (1280px) untuk landing pages
```

### 1.4 Border & Shadow

```
Border radius:    rounded-lg (8px) — card, button
                  rounded-xl (12px) — modal, hero card
                  rounded-full — avatar, badge

Shadow:           shadow-sm — card default
                  shadow-md — dropdown, modal
                  shadow-lg — floating element

Border:           border border-gray-200 — card, input
                  border-l-4 — status accent pada order card
```

### 1.5 Component Library

#### Buttons
```
Primary:     bg-primary-500 text-white hover:bg-primary-600 rounded-lg px-4 py-2
Secondary:   bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg px-4 py-2
Danger:      bg-red-500 text-white hover:bg-red-600 rounded-lg px-4 py-2
Ghost:       text-primary-500 hover:bg-primary-50 rounded-lg px-4 py-2
Icon:        p-2 rounded-lg hover:bg-gray-100 (icon-only button)
Disabled:    opacity-50 cursor-not-allowed
Size SM:     text-xs px-3 py-1.5
Size LG:     text-base px-6 py-3
```

#### Badges
```
Success:     bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full
Warning:     bg-amber-100 text-amber-800
Danger:      bg-red-100 text-red-800
Info:        bg-blue-100 text-blue-800
Neutral:     bg-gray-100 text-gray-600
```

#### Form Inputs
```
Input:       w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
             focus:ring-2 focus:ring-primary-500 focus:border-primary-500
Select:      same as input + appearance-none + custom chevron
Textarea:    same as input + resize-y
Label:       text-sm font-medium text-gray-700 mb-1
Error:       text-xs text-red-500 mt-1
```

---

## 2. Layout & Navigation

### 2.1 Landing Pages (Public)

```
┌─────────────────────────────────────────────────┐
│  NAVBAR (fixed top, white, shadow-sm)           │
│  Logo | Home | Features | Pricing | Login | CTA │
├─────────────────────────────────────────────────┤
│                                                 │
│  HERO SECTION                                   │
│  Headline + Subtitle + CTA Button               │
│                                                 │
├─────────────────────────────────────────────────┤
│  FEATURES GRID (3 cols)                         │
│  [Card] [Card] [Card]                           │
├─────────────────────────────────────────────────┤
│  PRICING CARDS (3 cols)                         │
│  [Free] [Pro ★] [Enterprise]                    │
├─────────────────────────────────────────────────┤
│  FOOTER                                         │
│  Links | Social | Copyright                     │
└─────────────────────────────────────────────────┘
```

### 2.2 Admin Panel Layout

```
┌──────────┬──────────────────────────────────────┐
│          │  TOPBAR (h-16)                       │
│          │  ☰ Toggle | Breadcrumb | User Menu   │
│ SIDEBAR  ├──────────────────────────────────────┤
│ (w-64)   │                                      │
│          │  CONTENT AREA                         │
│ Logo     │  ┌──────────────────────────────────┐ │
│ ───────  │  │ Page Title + Action Button       │ │
│ Dashboard│  ├──────────────────────────────────┤ │
│ Outlet   │  │                                  │ │
│ Menu ▸   │  │  Main Content                    │ │
│  ├ Kategori │  (Table / Cards / Form)          │ │
│  └ Item  │  │                                  │ │
│ POS      │  │                                  │ │
│ Kitchen  │  └──────────────────────────────────┘ │
│ Orders   │                                      │
│ Inventory▸│                                     │
│  ├ Material│                                    │
│  ├ Resep  │                                     │
│  ├ Transfer│                                    │
│  └ Waste  │                                     │
│ Staff    │                                      │
│ Settings │                                      │
│ ───────  │                                      │
│ Logout   │                                      │
└──────────┴──────────────────────────────────────┘
```

### 2.3 Sidebar Menu Structure

```
📊 Dashboard              → tenant.admin.dashboard
🏪 Outlet                 → tenant.admin.outlets.index
📂 Menu
   ├── Kategori           → tenant.admin.menu-categories.index
   └── Menu Item          → tenant.admin.menu-items.index
💰 POS                    → tenant.admin.pos.index
🍳 Kitchen Display        → tenant.admin.kitchen.index
📦 Orders                 → tenant.admin.orders.index
💵 Sesi Kasir             → tenant.admin.cashier-sessions.index
📦 Inventory
   ├── Bahan Baku         → tenant.admin.materials.index
   ├── Resep              → tenant.admin.recipes.index
   ├── Food Cost          → tenant.admin.menu-costing.index
   ├── Stok Masuk/Keluar  → tenant.admin.stock-movements.index
   ├── Transfer Stok      → tenant.admin.stock-transfers.index
   └── Waste Record       → tenant.admin.waste-records.index
📈 Laporan
   ├── Penjualan          → tenant.admin.reports.sales
   ├── Profit             → tenant.admin.reports.profit
   └── Inventory          → tenant.admin.reports.inventory
👥 Staff & Role           → tenant.admin.roles.index
⚙️ Settings               → tenant.admin.settings.index
```

### 2.4 Responsive Breakpoints

```
Mobile:     < 768px    — Sidebar hidden (overlay via hamburger), single column
Tablet:     768-1024px — Sidebar collapsed (icons only), 2-column grid
Desktop:    > 1024px   — Sidebar expanded, 3-4 column grid
KDS:        Optimized for landscape tablet/monitor (min 1024px)
```

---

## 3. Page-by-Page UI Spec

### 3.1 Landing Home
- **Hero:** Gradient background, headline besar, subtitle, 2 CTA buttons (Mulai Gratis / Lihat Fitur)
- **Fitur highlight:** 6 cards dalam grid 3x2 dengan icon + judul + deskripsi singkat
- **Testimonial:** Carousel atau grid 3 kolom
- **CTA banner:** Background primary, text + button signup
- **Footer:** 4 kolom (Produk, Perusahaan, Legal, Kontak)

### 3.2 Pricing Page
- **3 pricing cards** dalam satu baris (mobile: stack vertical)
- Card tengah (recommended) diberi border primary + badge "Populer"
- Setiap card: nama plan, harga/bulan, list fitur (✓/✗), CTA button
- FAQ section dibawah pricing cards

### 3.3 Signup Page
- **Centered card** (max-w-md) di atas background gradient
- Fields: Nama Bisnis, Slug (auto-generate + live check ✓/✗), Email, Password, Confirm Password
- Divider "atau"
- Tombol "Daftar dengan Google"
- Link ke halaman login

### 3.4 Login Page
- **Centered card** (max-w-md)
- Fields: Email, Password
- Checkbox "Ingat saya"
- Tombol Login + "Login dengan Google"
- Link ke halaman signup

### 3.5 Admin Dashboard
```
┌─────────────────────────────────────────────┐
│ Selamat Datang, [Nama]!                     │
├────────┬────────┬────────┬─────────────────-┤
│ Total  │ Order  │Revenue │ Menu Item        │
│ Order  │ Hari   │ Hari   │ Aktif            │
│ [123]  │ Ini[8] │[1.2M]  │ [45]             │
├────────┴────────┴────────┴──────────────────┤
│ Order Terbaru (table 5 rows)                │
│ Code | Customer | Total | Status | Waktu    │
├─────────────────────────────────────────────┤
│ Quick Actions                               │
│ [Buka POS] [Kitchen] [Tambah Menu]          │
└─────────────────────────────────────────────┘
```

### 3.6 Outlet Management
- **List view:** Table dengan kolom Nama, Alamat, Jumlah Meja, Aksi (Edit/Hapus/QR)
- **Form (full page):** Input Nama, Textarea Alamat, tombol Simpan

### 3.7 Menu Category & Menu Item
- **Category list:** Table sederhana (Nama, Jumlah Item, Aksi)
- **Item list:** Table/card grid dengan gambar thumbnail, nama, harga, kategori, status badge, aksi
- **Item form (full page):**
  - Select kategori
  - Input nama, SKU, harga
  - File upload gambar (preview)
  - Toggle aktif/nonaktif
  - Tombol Simpan

### 3.8 QR Menu (Customer-Facing)
```
┌─────────────────────────┐
│ 🍽️ [Nama Resto]         │  ← Header minimal
│ Meja: [A1]              │
├─────────────────────────┤
│ [Kategori Tabs]         │  ← Horizontal scroll
│ Makanan | Minuman | ... │
├─────────────────────────┤
│ ┌─────┐ ┌─────┐        │
│ │ IMG │ │ IMG │        │  ← Grid 2 kolom
│ │Nama │ │Nama │        │
│ │Rp.  │ │Rp.  │        │
│ │[+]  │ │[+]  │        │
│ └─────┘ └─────┘        │
├─────────────────────────┤
│ 🛒 Cart (floating bottom)│
│ [3 item — Rp 85.000]   │
│ [Lihat Keranjang]       │
└─────────────────────────┘
```
- **Cart drawer:** Slide up dari bawah, list item + qty +/-, total, input nama/HP, tombol Pesan
- **Layout:** `layouts.qr-clean` — tanpa navbar/footer aplikasi
- **Style:** Mobile-first, font besar, tombol besar untuk touch

### 3.9 POS Kasir
```
┌────────────────────────┬─────────────────────┐
│ MENU AREA              │ ORDER AREA          │
│                        │                     │
│ [Search...........]    │ Outlet: [Dropdown]  │
│ [Kategori Tabs]        │ Tipe: ○Dine ○Take   │
│                        │ Meja: [Dropdown]    │
│ ┌────┐ ┌────┐ ┌────┐  │ ─────────────────── │
│ │Item│ │Item│ │Item│  │ Nasi Goreng  x2  30K│
│ │ Rp │ │ Rp │ │ Rp │  │ Es Teh       x1  8K │
│ └────┘ └────┘ └────┘  │ ─────────────────── │
│ ┌────┐ ┌────┐ ┌────┐  │ Subtotal:     38K   │
│ │Item│ │Item│ │Item│  │ Tax (10%):    3.8K  │
│ └────┘ └────┘ └────┘  │ Service (5%): 1.9K  │
│                        │ ═══════════════════ │
│                        │ TOTAL:        43.7K │
│                        │                     │
│                        │ [Cash] [QRIS]       │
│                        │ [    BAYAR    ]      │
└────────────────────────┴─────────────────────┘
```

### 3.10 Kitchen Display System
```
┌──────────────────────────────────────────────┐
│ 🍳 Kitchen Display — [Outlet Name]    🔴 LIVE │
├──────────────────────────────────────────────┤
│ ┌──────────┐ ┌──────────┐ ┌──────────┐      │
│ │ #ORD-042 │ │ #ORD-043 │ │ #ORD-044 │      │
│ │ Meja A3  │ │ Takeaway │ │ Meja B1  │      │
│ │ 14:32    │ │ 14:35    │ │ 14:38    │      │
│ │──────────│ │──────────│ │──────────│      │
│ │🟡 Nasi   │ │🟢 Es Teh │ │🔴 Ayam   │      │
│ │  Goreng  │ │  Manis   │ │  Bakar   │      │
│ │🟡 Mie    │ │🟡 Nasi   │ │🔴 Nasi   │      │
│ │  Ayam    │ │  Padang  │ │  Putih   │      │
│ │          │ │          │ │          │      │
│ └──────────┘ └──────────┘ └──────────┘      │
└──────────────────────────────────────────────┘

Status Colors:
🔴 Pending (merah)    — Belum dikerjakan
🟡 Preparing (kuning) — Sedang disiapkan
🟢 Ready (hijau)      — Siap disajikan
```

### 3.11 Order Management
- **Table view** dengan kolom: Code, Customer, Outlet, Meja, Total, Status (badge), Waktu, Aksi
- **Filter bar:** Dropdown status, date picker, search
- **Detail (full page):** Info order lengkap, list item, payment info, action buttons (Void, Update Payment)
- **Receipt:** Layout struk termal (max-w-xs, mono font)

### 3.12 Cashier Session
- **Card layout:**
  - Sesi Aktif: card hijau, info kasir, waktu buka, tombol Tutup
  - Buka Sesi Baru: form opening cash + tombol Buka
- **Histori:** Table dengan kolom Kasir, Outlet, Buka, Tutup, Opening Cash, Closing Cash, Selisih

### 3.13 Material & Recipe
- **Material list:** Table (Nama, Unit, Stok, Harga/Unit, Aksi)
- **Material form (full page):** Input nama, unit, stok awal, cost per unit
- **Recipe list:** Table (Menu Item, Jumlah Bahan, Food Cost, Aksi)
- **Recipe form (full page):** Select menu item, dynamic rows (material + qty), auto-calculate cost

### 3.14 Stock Transfer & Waste
- **Transfer list:** Table (Dari, Ke, Status badge, Tanggal, Aksi)
- **Transfer form:** Select outlet asal/tujuan, dynamic rows material + qty
- **Waste list:** Table (Outlet, Tanggal, Jumlah Item, Aksi)
- **Waste form:** Select outlet, dynamic rows material + qty + alasan

### 3.15 Staff & Role Management
- **Staff list:** Table (Nama, Email, Role badge, Aksi)
- **Staff form (full page):** Input nama, email, password, select role
- **Hapus:** Konfirmasi dialog sebelum delete

### 3.16 Tenant Settings
- **Card-based sections:**
  - Card 1: Umum (nama bisnis, logo)
  - Card 2: Keuangan (tax rate %, service rate %)
  - Card 3: Pembayaran (QRIS payload, metode aktif)
- Tombol Simpan per card atau satu tombol global

---

## 4. UX Patterns

### 4.1 Form Validation
- **Client-side:** Real-time validation saat blur/input (Alpine.js)
- **Server-side:** Laravel validation, error ditampilkan di bawah field
- **Style error:** Border merah (`border-red-500`), text error merah di bawah input
- **Style success:** Border hijau saat valid (opsional)

### 4.2 Empty States
- **Ilustrasi** sederhana (SVG/icon besar) + judul + deskripsi + CTA
- Contoh: "Belum ada menu item. Tambahkan menu pertamamu!"
- Tombol: "Tambah Menu Item"

### 4.3 Loading States
- **Button:** Spinner icon + text "Menyimpan..." + disabled
- **Table:** Skeleton rows (animated pulse)
- **Page:** Full page spinner (jarang, hanya untuk initial load)
- **AJAX:** Kecil spinner di tempat yang di-update

### 4.4 Toast / Alert Notifications
- **Success:** Background hijau, icon ✓, auto-dismiss 3 detik
- **Error:** Background merah, icon ✗, manual dismiss
- **Warning:** Background amber, icon ⚠
- **Position:** Top-right, fixed, z-50
- **Implementation:** Alpine.js component atau Laravel flash session

### 4.5 Confirmation Dialogs
- **Delete:** Modal dengan icon warning, judul "Yakin hapus?", deskripsi, 2 tombol (Batal / Hapus)
- **Void order:** Modal dengan input alasan (opsional)
- **Style:** Overlay gelap, modal centered, rounded-xl

### 4.6 Pagination
- **Style:** `<< Prev | 1 2 3 ... 10 | Next >>`
- **Info:** "Menampilkan 1-15 dari 142 data"
- **Per page:** Default 15, opsi 15/25/50

---

## 5. Accessibility Guidelines

### 5.1 Keyboard Navigation
- Semua interactive elements harus focusable (tab order logis)
- Modal: focus trap, Escape untuk tutup
- Dropdown: Arrow keys untuk navigasi, Enter untuk select
- POS: keyboard shortcut untuk kategori & submit (future)

### 5.2 Color Contrast
- Text utama: ratio minimal 4.5:1 (WCAG AA)
- Tombol: jangan hanya andalkan warna — gunakan icon/text juga
- Status badge: gunakan text label, bukan hanya warna

### 5.3 ARIA Labels
- Icon-only buttons: `aria-label="Hapus item"`
- Form fields: `<label for="">` yang terhubung
- Modal: `role="dialog"` + `aria-modal="true"`
- Live region untuk notifikasi: `aria-live="polite"`

---

## 6. Mobile Responsiveness

### Prioritas Mobile
1. **QR Menu** — 100% mobile-first (pelanggan pakai HP)
2. **KDS** — Optimized tablet landscape
3. **POS** — Tablet portrait/landscape, usable di mobile
4. **Admin pages** — Desktop-first, responsive ke mobile

### Strategi
- Sidebar → hamburger menu (mobile)
- Table → card list (mobile untuk data sederhana)
- Multi-column form → single column (mobile)
- POS split layout → stacked (menu di atas, cart di bawah) untuk mobile
- Fixed bottom bar untuk CTA di mobile (QR Menu cart, POS submit)

### Touch Targets
- Minimum 44x44px untuk semua touch targets
- POS menu items: card besar, mudah di-tap
- KDS status button: besar, jelas, satu tap

---

*Dokumen ini menjadi referensi untuk konsistensi visual dan UX di seluruh aplikasi VenResto.*
