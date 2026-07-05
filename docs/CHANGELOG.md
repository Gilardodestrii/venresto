# Changelog — VenResto

## v1.1.0 (2026-07-05)

### QR Customer Order — Bug Fixes

**🔴 Bug #1: SQL Error saat Checkout — Missing `payment_status` column**
- **File:** `database/migrations/2026_06_27_000001_add_payment_status_to_orders_table.php` (NEW)
- **Problem:** `QrMenuController@store` set `payment_status => 'unpaid'` tapi kolom tidak ada di tabel `orders`
  → fatal SQL error "Unknown column" setiap customer checkout
- **Fix:** Buat migration menambah kolom `payment_status VARCHAR(20) DEFAULT 'unpaid'`
- **Action:** Jalankan `php artisan migrate` di server production

**🔴 Bug #2: Checkout Gagal (HTTP 419) — CSRF Token Mismatch pada QR Menu**
- **File:** `app/Http/Middleware/VerifyCsrfToken.php`
- **Problem:** Endpoint checkout QR Menu (tanpa login) terkena CSRF verification, cookie session
  mismatch antara GET page dan POST checkout request
- **Fix:** Kecualikan `qr/outlets/*/order` dan `webhooks/*` dari CSRF check
- **Files touched:** `app/Http/Middleware/VerifyCsrfToken.php`

**🔴 Bug #3: Tax & Service Charge Salah — Rate dibagi 100 dua kali**
- **Files:** `app/Http/Controllers/QrMenuController.php`, `public/assets/js/qr/customer-order.js`
- **Problem:** `tax_rate` & `service_rate` disimpan di DB sebagai desimal (0.11 = 11%)
  tetapi code membagi lagi dengan 100 di 2 tempat:
  - PHP: `tax_rate / 100` → 0.11/100 = 0.11% (seharusnya 11%)
  - JS:   `taxRate / 100` → sama
- **Fix:**
  - PHP: hapus `/100` → `afterDiscount * (float) $settings->tax_rate`
  - JS:   hapus `/100` → `afterDiscount * taxRate`
- **Catatan:** Fix ini mengubah hasil kalkulasi (memulai fix ini, customer akan melihat
  tax/service yang BENAR). Pastikan tidak ada data yang rely pada hasil salah sebelumnya.

---

## v1.0.0 (2026-06-27)

- Initial documented release
- Multi-tenant POS, QR Menu, KDS, Inventory