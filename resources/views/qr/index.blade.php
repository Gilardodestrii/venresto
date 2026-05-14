@extends('layouts.app')

@section('content')

<style>
    body {
        margin: 0;
        background: #f8eef4;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    #qrOrderApp * {
        box-sizing: border-box;
    }

    .app-shell {
        max-width: 480px;
        margin: 0 auto;
        min-height: 100vh;
        background: #eef8ff;
        position: relative;
        padding-bottom: 86px;
        overflow-x: hidden;
    }

    .page-view {
        display: none;
        min-height: calc(100vh - 86px);
    }

    .page-view.active {
        display: block;
    }

    .hero-header,
    .blue-page-header {
        background: #28aeea;
        color: white;
        padding: 26px 20px 22px;
        border-bottom-left-radius: 28px;
        border-bottom-right-radius: 28px;
    }

    .hero-header {
        position: sticky;
        top: 0;
        z-index: 20;
        box-shadow: 0 12px 30px rgba(40, 174, 234, .25);
    }

    .blue-page-header {
        padding-top: 26px;
    }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-pill {
        background: rgba(255,255,255,.18);
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
    }

    .cart-icon {
        width: 44px;
        height: 44px;
        border: 0;
        border-radius: 16px;
        background: rgba(255,255,255,.18);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .cart-icon svg {
        width: 25px;
        height: 25px;
    }

    .cart-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        min-width: 21px;
        height: 21px;
        border-radius: 50%;
        background: #ef4444;
        color: white;
        font-size: 11px;
        display: none;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        border: 2px solid white;
    }

    .cart-badge.show {
        display: flex;
    }

    .hero-title {
        margin-top: 20px;
        font-size: 25px;
        font-weight: 900;
        line-height: 1.15;
    }

    .hero-subtitle {
        color: rgba(255,255,255,.88);
        font-size: 13px;
        margin-top: 6px;
    }

    .blue-page-header h4 {
        margin: 0;
        font-weight: 900;
        font-size: 22px;
    }

    .blue-page-header small {
        color: white;
        font-size: 13px;
    }

    .search-box {
        margin-top: 18px;
        background: white;
        border-radius: 18px;
        padding: 11px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #6b99b6;
    }

    .search-box input {
        border: 0;
        outline: 0;
        width: 100%;
        font-size: 14px;
        background: transparent;
    }

    .category-wrap {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 16px 14px 4px;
        scrollbar-width: none;
    }

    .category-wrap::-webkit-scrollbar {
        display: none;
    }

    .category-chip {
        border: 0;
        background: white;
        padding: 9px 15px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        color: #3c76a0;
        box-shadow: 0 8px 20px rgba(15,23,42,.05);
        white-space: nowrap;
    }

    .category-chip.active {
        background: #02a9ff;
        color: white;
    }

    .section-title {
        padding: 14px 16px 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title h6 {
        margin: 0;
        font-size: 17px;
        font-weight: 900;
        color: #001b36;
    }

    .section-title small {
        color: #4d86ad;
        font-weight: 700;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        padding: 8px 14px 110px;
    }

    .menu-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(15,23,42,.07);
        position: relative;
    }

    .menu-img-wrap {
        position: relative;
        height: 128px;
        background: #dcecf5;
    }

    .menu-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .price-floating {
        position: absolute;
        left: 10px;
        bottom: 10px;
        background: rgba(0, 27, 54, .88);
        color: white;
        font-size: 12px;
        font-weight: 800;
        padding: 6px 9px;
        border-radius: 999px;
    }

    .menu-body {
        padding: 12px;
    }

    .menu-title {
        font-size: 14px;
        font-weight: 900;
        color: #001b36;
        min-height: 38px;
        line-height: 1.35;
    }

    .menu-desc {
        font-size: 12px;
        color: #6b99b6;
        margin-top: 3px;
    }

    .btn-add {
        margin-top: 10px;
        width: 100%;
        border: 0;
        border-radius: 15px;
        padding: 10px;
        font-size: 13px;
        font-weight: 900;
        background: #02a9ff;
        color: white;
    }

    .btn-add:active,
    .btn-blue:active,
    .btn-checkout:active,
    .bottom-nav-item:active,
    .category-chip:active {
        transform: scale(.98);
    }

    .empty-state {
        min-height: calc(100vh - 260px);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 30px;
    }

    .empty-icon {
        color: #5d8dad;
        margin-bottom: 14px;
    }

    .empty-icon svg {
        width: 72px;
        height: 72px;
    }

    .empty-state h5 {
        font-size: 21px;
        font-weight: 900;
        color: #001b36;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #4d86ad;
        font-size: 14px;
        margin-bottom: 18px;
    }

    .btn-blue {
        border: 0;
        background: #28aeea;
        color: white;
        border-radius: 14px;
        padding: 12px 28px;
        font-weight: 900;
    }

    .cart-page-list,
    .order-page-list {
        padding: 16px;
    }

    .cart-item-card,
    .order-item-card {
        background: white;
        border-radius: 18px;
        padding: 14px;
        margin-bottom: 12px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .06);
    }

    .cart-item-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
    }

    .cart-item-name {
        font-weight: 900;
        color: #001b36;
        font-size: 14px;
    }

    .cart-item-price {
        color: #4d86ad;
        font-size: 13px;
        margin-top: 2px;
    }

    .cart-line-total {
        font-weight: 900;
        color: #001b36;
        white-space: nowrap;
        font-size: 13px;
    }

    .qty-control {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
        background: #eef8ff;
        padding: 6px 10px;
        border-radius: 999px;
    }

    .qty-control button {
        border: 0;
        background: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-weight: 900;
        box-shadow: 0 3px 10px rgba(15,23,42,.08);
    }

    .note-input,
    .checkout-form .form-control {
        border-radius: 14px !important;
        border: 1px solid #d8edf8 !important;
        font-size: 13px;
    }

    .checkout-form {
        padding: 0 16px 16px;
    }

    .checkout-form label {
        font-size: 12px;
        font-weight: 900;
        color: #3c76a0;
        margin-top: 8px;
        margin-bottom: 4px;
    }

    .summary-box {
        background: white;
        border-radius: 20px;
        padding: 16px;
        margin: 0 16px 24px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .06);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
        color: #315f82;
    }

    .summary-row b {
        color: #001b36;
    }

    .summary-row.grand {
        border-top: 1px dashed #cbd5e1;
        padding-top: 12px;
        margin-top: 10px;
        font-size: 18px;
        font-weight: 900;
        color: #001b36;
    }

    .btn-checkout {
        border: 0;
        width: 100%;
        background: #22c55e;
        color: white;
        border-radius: 16px;
        padding: 14px;
        font-weight: 900;
        margin-top: 14px;
    }

    .btn-checkout:disabled {
        opacity: .7;
    }

    .order-meta {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .order-title {
        font-weight: 900;
        color: #001b36;
    }

    .order-date {
        color: #6b99b6;
        font-size: 12px;
    }

    .order-total {
        font-weight: 900;
        color: #001b36;
        white-space: nowrap;
    }

    .order-line {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        font-size: 13px;
        margin-bottom: 6px;
        color: #315f82;
    }

    .status-badge {
        display: inline-block;
        margin-top: 8px;
        background: #dff6ff;
        color: #0284c7;
        border-radius: 999px;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 900;
    }

    .bottom-nav {
        position: fixed;
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        max-width: 480px;
        width: 100%;
        height: 78px;
        background: white;
        border-top: 1px solid #bdeaff;
        z-index: 100;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .bottom-nav-item {
        border: 0;
        background: transparent;
        color: #3c76a0;
        font-size: 12px;
        width: 33.333%;
        text-align: center;
        position: relative;
        padding: 6px 0;
    }

    .bottom-nav-item svg {
        width: 27px;
        height: 27px;
        display: block;
        margin: 0 auto 3px;
        stroke-width: 2.2;
    }

    .bottom-nav-item.active {
        color: #02a9ff;
        font-weight: 900;
    }

    .nav-badge {
        position: absolute;
        top: 1px;
        right: 33%;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #ef4444;
        color: white;
        font-size: 10px;
        display: none;
        align-items: center;
        justify-content: center;
        font-weight: 900;
    }

    .nav-badge.show {
        display: flex;
    }

    .no-menu-result {
        display: none;
        text-align: center;
        color: #4d86ad;
        padding: 40px 20px 120px;
        font-weight: 800;
    }

    @media (max-width: 360px) {
        .menu-grid {
            gap: 10px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .menu-img-wrap {
            height: 112px;
        }

        .hero-title {
            font-size: 22px;
        }
    }
</style>

<div id="qrOrderApp" class="app-shell">

    {{-- HALAMAN MENU --}}
    <div class="page-view active" id="pageMenu">
        <div class="hero-header">
            <div class="hero-top">
                <div class="table-pill">🍽️ Meja {{ $table }}</div>

                <button type="button" class="cart-icon" onclick="showPage('cart')" aria-label="Buka keranjang">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 4h2l2.5 13h10L21 8H7"></path>
                        <circle cx="10" cy="20" r="1.4"></circle>
                        <circle cx="18" cy="20" r="1.4"></circle>
                    </svg>
                    <span class="cart-badge" id="cartCount">0</span>
                </button>
            </div>

            <div class="hero-title">Mau pesan apa hari ini?</div>
            <div class="hero-subtitle">Pilih menu favorit, tambah catatan, lalu checkout.</div>

            <div class="search-box">
                🔍
                <input type="text" id="searchMenu" placeholder="Cari menu..." autocomplete="off">
            </div>
        </div>

        <div class="category-wrap">
            <button type="button" class="category-chip active" data-category-filter="semua">Semua</button>
            <button type="button" class="category-chip" data-category-filter="makanan">Makanan</button>
            <button type="button" class="category-chip" data-category-filter="minuman">Minuman</button>
            <button type="button" class="category-chip" data-category-filter="snack">Snack</button>
            <button type="button" class="category-chip" data-category-filter="promo">Promo</button>
        </div>

        <div class="section-title">
            <h6>Menu Populer</h6>
            <small id="menuCountText">{{ count($menus) }} menu</small>
        </div>

        <div class="menu-grid" id="menuGrid">
            @foreach($menus as $menu)
                @php
                    $categoryValue = 'makanan';

                    if (isset($menu->category) && is_object($menu->category)) {
                        $categoryValue = $menu->category->name ?? 'makanan';
                    } elseif (isset($menu->category) && is_scalar($menu->category)) {
                        $categoryValue = $menu->category;
                    } elseif (isset($menu->kategori) && is_scalar($menu->kategori)) {
                        $categoryValue = $menu->kategori;
                    } elseif (isset($menu->category_name) && is_scalar($menu->category_name)) {
                        $categoryValue = $menu->category_name;
                    }

                    $categorySlug = \Illuminate\Support\Str::slug(strtolower($categoryValue));
                    $imageUrl = $menu->image_url ?: asset('images/no-image.png');
                @endphp

                <div class="menu-card"
                    data-name="{{ strtolower($menu->name) }}"
                    data-category="{{ $categorySlug }}">

                    <div class="menu-img-wrap">
                        <img src="{{ $imageUrl }}"
                             class="menu-img"
                             alt="{{ $menu->name }}"
                             loading="lazy"
                             onerror="this.src='{{ asset('images/no-image.png') }}'">

                        <div class="price-floating">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="menu-body">
                        <div class="menu-title">{{ $menu->name }}</div>
                        <div class="menu-desc">{{ ucfirst($categoryValue) }}</div>

                        <button type="button"
                            class="btn-add js-add-cart"
                            data-id="{{ $menu->id }}"
                            data-name="{{ $menu->name }}"
                            data-price="{{ $menu->price }}">
                            + Tambah
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="no-menu-result" id="noMenuResult">
            Menu tidak ditemukan
        </div>
    </div>

    {{-- HALAMAN KERANJANG --}}
    <div class="page-view" id="pageCart">
        <div class="blue-page-header">
            <h4>Keranjang</h4>
            <small>Meja {{ $table }}</small>
        </div>

        <div id="cartPageContent"></div>
    </div>

    {{-- HALAMAN PESANAN --}}
    <div class="page-view" id="pageOrders">
        <div class="blue-page-header">
            <h4>Pesanan Saya</h4>
            <small id="orderCountText">0 pesanan</small>
        </div>

        <div id="ordersPageContent"></div>
    </div>

    {{-- BOTTOM NAV --}}
    <div class="bottom-nav">
        <button type="button" class="bottom-nav-item active" id="navMenu" onclick="showPage('menu')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 7h16M4 12h16M4 17h16"></path>
            </svg>
            Menu
        </button>

        <button type="button" class="bottom-nav-item" id="navCart" onclick="showPage('cart')">
            <span class="nav-badge" id="navCartBadge">0</span>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 4h2l2.5 13h10L21 8H7"></path>
                <circle cx="10" cy="20" r="1"></circle>
                <circle cx="18" cy="20" r="1"></circle>
            </svg>
            Keranjang
        </button>

        <button type="button" class="bottom-nav-item" id="navOrders" onclick="showPage('orders')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M7 3h7l5 5v13H7z"></path>
                <path d="M14 3v5h5"></path>
                <path d="M10 13h6M10 17h6M10 9h2"></path>
            </svg>
            Pesanan
        </button>
    </div>

</div>

<script>
const tableNumber = @json((string) $table);
const csrfToken = @json(csrf_token());
const checkoutUrl = @json(url('/qr/order'));

const cartStorageKey = 'qr_cart_meja_' + tableNumber;
const orderStorageKey = 'qr_orders_meja_' + tableNumber;

let cart = loadJson(cartStorageKey, []);
let orders = loadJson(orderStorageKey, []);
let activeCategory = 'semua';
let isCheckoutLoading = false;

let order = {
    discount: 0,
    tax: 0,
    service: 0,
    payment_method: 'cash',
    customer_note: ''
};

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-add-cart').forEach(button => {
        button.addEventListener('click', function () {
            addToCart(
                Number(this.dataset.id),
                this.dataset.name,
                Number(this.dataset.price)
            );
        });
    });

    document.querySelectorAll('.category-chip').forEach(button => {
        button.addEventListener('click', function () {
            filterCategory(this.dataset.categoryFilter, this);
        });
    });

    const searchInput = document.getElementById('searchMenu');
    if (searchInput) {
        searchInput.addEventListener('input', filterMenu);
    }

    render();
    renderOrdersPage();
    filterMenu();
});

function loadJson(key, fallback) {
    try {
        return JSON.parse(localStorage.getItem(key)) || fallback;
    } catch (e) {
        return fallback;
    }
}

function saveCart() {
    localStorage.setItem(cartStorageKey, JSON.stringify(cart));
}

function saveOrders() {
    localStorage.setItem(orderStorageKey, JSON.stringify(orders));
}

function showPage(page) {
    document.querySelectorAll('.page-view').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.bottom-nav-item').forEach(el => el.classList.remove('active'));

    if (page === 'menu') {
        document.getElementById('pageMenu').classList.add('active');
        document.getElementById('navMenu').classList.add('active');
    }

    if (page === 'cart') {
        document.getElementById('pageCart').classList.add('active');
        document.getElementById('navCart').classList.add('active');
        renderCartPage();
    }

    if (page === 'orders') {
        document.getElementById('pageOrders').classList.add('active');
        document.getElementById('navOrders').classList.add('active');
        renderOrdersPage();
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function addToCart(id, name, price) {
    if (!id || !name || price < 0) return;

    const item = cart.find(x => Number(x.id) === Number(id));

    if (item) {
        item.qty += 1;
    } else {
        cart.push({
            id: Number(id),
            name: String(name),
            price: Number(price),
            qty: 1,
            note: ''
        });
    }

    saveCart();
    render();
}

function changeQty(id, type) {
    const item = cart.find(x => Number(x.id) === Number(id));
    if (!item) return;

    if (type === 'plus') item.qty += 1;
    if (type === 'minus') item.qty -= 1;

    if (item.qty <= 0) {
        cart = cart.filter(x => Number(x.id) !== Number(id));
    }

    saveCart();
    render();
    renderCartPage();
}

function updateItemNote(id, value) {
    const item = cart.find(x => Number(x.id) === Number(id));
    if (!item) return;

    item.note = value;
    saveCart();
}

function updateOrderField(field, value) {
    if (['discount', 'tax', 'service'].includes(field)) {
        order[field] = Number(value) || 0;
    } else {
        order[field] = value || '';
    }

    renderCartSummaryOnly();
}

function calculate() {
    const subtotal = cart.reduce((total, item) => {
        return total + (Number(item.price) * Number(item.qty));
    }, 0);

    const discount = Math.max(Number(order.discount) || 0, 0);
    const taxRate = Math.max(Number(order.tax) || 0, 0);
    const serviceRate = Math.max(Number(order.service) || 0, 0);

    const tax = subtotal * taxRate / 100;
    const service = subtotal * serviceRate / 100;
    const grand_total = Math.max(subtotal - discount + tax + service, 0);

    return { subtotal, discount, tax, service, grand_total };
}

function formatRupiah(value) {
    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
}

function getTotalQty() {
    return cart.reduce((total, item) => total + Number(item.qty || 0), 0);
}

function render() {
    const totalQty = getTotalQty();

    const cartCount = document.getElementById('cartCount');
    const navCartBadge = document.getElementById('navCartBadge');

    if (cartCount) {
        cartCount.innerText = totalQty;
        cartCount.classList.toggle('show', totalQty > 0);
    }

    if (navCartBadge) {
        navCartBadge.innerText = totalQty;
        navCartBadge.classList.toggle('show', totalQty > 0);
    }
}

function renderCartPage() {
    const el = document.getElementById('cartPageContent');
    if (!el) return;

    if (cart.length === 0) {
        el.innerHTML = `
            <div class="empty-state">
                <div>
                    <div class="empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 4h2l2.5 13h10L21 8H7" stroke-width="1.8"></path>
                            <circle cx="10" cy="20" r="1.2"></circle>
                            <circle cx="18" cy="20" r="1.2"></circle>
                        </svg>
                    </div>
                    <h5>Keranjang Kosong</h5>
                    <p>Tambahkan menu favorit kamu</p>
                    <button type="button" class="btn-blue" onclick="showPage('menu')">Lihat Menu</button>
                </div>
            </div>
        `;
        return;
    }

    let html = `<div class="cart-page-list">`;

    cart.forEach(item => {
        html += `
            <div class="cart-item-card">
                <div class="cart-item-row">
                    <div style="flex:1; min-width:0;">
                        <div class="cart-item-name">${escapeHtml(item.name)}</div>
                        <div class="cart-item-price">${item.qty} x ${formatRupiah(item.price)}</div>

                        <div class="qty-control">
                            <button type="button" onclick="changeQty(${item.id}, 'minus')">-</button>
                            <b>${item.qty}</b>
                            <button type="button" onclick="changeQty(${item.id}, 'plus')">+</button>
                        </div>

                        <input type="text"
                            class="form-control form-control-sm note-input mt-2"
                            placeholder="Catatan item..."
                            value="${escapeHtml(item.note || '')}"
                            oninput="updateItemNote(${item.id}, this.value)">
                    </div>

                    <div class="cart-line-total">
                        ${formatRupiah(item.qty * item.price)}
                    </div>
                </div>
            </div>
        `;
    });

    html += `</div>`;

    html += `
        <div class="checkout-form">
            <label>Discount (Rp)</label>
            <input type="number" min="0" class="form-control mb-2"
                value="${order.discount}"
                oninput="updateOrderField('discount', this.value)">

            <label>Tax (%)</label>
            <input type="number" min="0" class="form-control mb-2"
                value="${order.tax}"
                oninput="updateOrderField('tax', this.value)">

            <label>Service (%)</label>
            <input type="number" min="0" class="form-control mb-2"
                value="${order.service}"
                oninput="updateOrderField('service', this.value)">

            <label>Payment Method</label>
            <select class="form-control mb-2"
                onchange="updateOrderField('payment_method', this.value)">
                <option value="cash" ${order.payment_method === 'cash' ? 'selected' : ''}>Cash</option>
                <option value="qris" ${order.payment_method === 'qris' ? 'selected' : ''}>QRIS</option>
                <option value="card" ${order.payment_method === 'card' ? 'selected' : ''}>Card</option>
            </select>

            <label>Customer Note</label>
            <textarea class="form-control mb-2"
                placeholder="Contoh: jangan pedas, tanpa es..."
                oninput="updateOrderField('customer_note', this.value)">${escapeHtml(order.customer_note || '')}</textarea>
        </div>

        <div class="summary-box" id="cartSummaryBox"></div>
    `;

    el.innerHTML = html;
    renderCartSummaryOnly();
}

function renderCartSummaryOnly() {
    const summary = document.getElementById('cartSummaryBox');
    if (!summary) return;

    const calc = calculate();

    summary.innerHTML = `
        <div class="summary-row">
            <span>Subtotal</span>
            <b>${formatRupiah(calc.subtotal)}</b>
        </div>
        <div class="summary-row">
            <span>Discount</span>
            <b>${formatRupiah(calc.discount)}</b>
        </div>
        <div class="summary-row">
            <span>Tax</span>
            <b>${formatRupiah(calc.tax)}</b>
        </div>
        <div class="summary-row">
            <span>Service</span>
            <b>${formatRupiah(calc.service)}</b>
        </div>
        <div class="summary-row grand">
            <span>Total</span>
            <span>${formatRupiah(calc.grand_total)}</span>
        </div>

        <button type="button"
            class="btn-checkout"
            id="checkoutButton"
            onclick="checkout()"
            ${isCheckoutLoading ? 'disabled' : ''}>
            ${isCheckoutLoading ? 'Memproses...' : 'Checkout Sekarang'}
        </button>
    `;
}

function renderOrdersPage() {
    const el = document.getElementById('ordersPageContent');
    const countText = document.getElementById('orderCountText');

    if (countText) {
        countText.innerText = orders.length + ' pesanan';
    }

    if (!el) return;

    if (orders.length === 0) {
        el.innerHTML = `
            <div class="empty-state">
                <div>
                    <div class="empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M7 3h7l5 5v13H7z" stroke-width="1.8"></path>
                            <path d="M14 3v5h5" stroke-width="1.8"></path>
                            <path d="M10 13h6M10 17h6M10 9h2" stroke-width="1.8"></path>
                        </svg>
                    </div>
                    <h5>Belum Ada Pesanan</h5>
                    <p>Pesanan kamu akan muncul di sini</p>
                </div>
            </div>
        `;
        return;
    }

    let html = `<div class="order-page-list">`;

    orders.slice().reverse().forEach(orderItem => {
        html += `
            <div class="order-item-card">
                <div class="order-meta">
                    <div>
                        <div class="order-title">Pesanan #${escapeHtml(orderItem.order_code)}</div>
                        <div class="order-date">${escapeHtml(orderItem.date)}</div>
                    </div>
                    <div class="order-total">${formatRupiah(orderItem.grand_total)}</div>
                </div>

                <hr>

                ${orderItem.items.map(item => `
                    <div class="order-line">
                        <span>${item.qty}x ${escapeHtml(item.name)}</span>
                        <b>${formatRupiah(item.qty * item.price)}</b>
                    </div>
                `).join('')}

                <span class="status-badge">Diproses</span>
            </div>
        `;
    });

    html += `</div>`;
    el.innerHTML = html;
}

function filterCategory(category, button) {
    activeCategory = category || 'semua';

    document.querySelectorAll('.category-chip').forEach(btn => {
        btn.classList.remove('active');
    });

    if (button) {
        button.classList.add('active');
    }

    filterMenu();
}

function filterMenu() {
    const searchInput = document.getElementById('searchMenu');
    const keyword = (searchInput ? searchInput.value : '').toLowerCase().trim();
    const cards = document.querySelectorAll('.menu-card');
    const noResult = document.getElementById('noMenuResult');
    const menuCountText = document.getElementById('menuCountText');

    let visibleCount = 0;

    cards.forEach(card => {
        const name = (card.getAttribute('data-name') || '').toLowerCase();
        const category = (card.getAttribute('data-category') || '').toLowerCase();

        const matchSearch = name.includes(keyword);
        const matchCategory = activeCategory === 'semua' || category.includes(activeCategory);

        const show = matchSearch && matchCategory;

        card.style.display = show ? 'block' : 'none';

        if (show) {
            visibleCount++;
        }
    });

    if (noResult) {
        noResult.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    if (menuCountText) {
        menuCountText.innerText = visibleCount + ' menu';
    }
}

function checkout() {
    if (cart.length === 0) {
        alert('Cart kosong');
        return;
    }

    if (isCheckoutLoading) return;

    isCheckoutLoading = true;
    renderCartSummaryOnly();

    const calc = calculate();

    fetch(checkoutUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            table: tableNumber,
            items: cart,
            subtotal: calc.subtotal,
            discount: calc.discount,
            tax: calc.tax,
            service: calc.service,
            grand_total: calc.grand_total,
            payment_method: order.payment_method,
            customer_note: order.customer_note
        })
    })
    .then(async response => {
        let data = {};

        try {
            data = await response.json();
        } catch (e) {
            data = {};
        }

        if (!response.ok) {
            throw new Error(data.message || 'Checkout gagal');
        }

        return data;
    })
    .then(res => {
        const newOrder = {
            order_code: res.order_code || (res.data && (res.data.order_code || res.data.id)) || Date.now(),
            date: new Date().toLocaleString('id-ID'),
            items: JSON.parse(JSON.stringify(cart)),
            subtotal: calc.subtotal,
            discount: calc.discount,
            tax: calc.tax,
            service: calc.service,
            grand_total: calc.grand_total,
            payment_method: order.payment_method,
            customer_note: order.customer_note
        };

        orders.push(newOrder);
        saveOrders();

        cart = [];
        saveCart();

        order = {
            discount: 0,
            tax: 0,
            service: 0,
            payment_method: 'cash',
            customer_note: ''
        };

        alert(res.message || 'Pesanan berhasil dibuat');

        render();
        renderCartPage();
        renderOrdersPage();
        showPage('orders');
    })
    .catch(error => {
        alert(error.message || 'Checkout gagal. Silakan coba lagi.');
    })
    .finally(() => {
        isCheckoutLoading = false;
        renderCartSummaryOnly();
    });
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
</script>

@endsection