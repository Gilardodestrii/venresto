@extends('layouts.qr-clean')

@section('page-title', 'Pesan Menu — ' . ($tenant->name ?? 'VenResto'))

@section('content')

<div id="qrOrderApp" class="app-shell">

    {{-- HALAMAN MENU --}}
    <div class="page-view active" id="pageMenu">
        <div class="hero-header">
            <div class="hero-top">
                <div class="table-pill">🍽️ Meja {{ $table->table_code }}</div>

                <button type="button" class="cart-icon" onclick="QrCustomerOrder.showPage('cart')" aria-label="Buka keranjang">
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
            <button type="button"
                class="category-chip active"
                data-category-filter="semua">
                Semua
            </button>

            @foreach($categories as $cat)
                <button type="button"
                    class="category-chip"
                    data-category-filter="{{ \Illuminate\Support\Str::slug($cat->name) }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <div class="section-title">
            <h6>Menu</h6>
            <small id="menuCountText">{{ $menus->count() }} menu</small>
        </div>

        <div class="menu-grid" id="menuGrid">
            @forelse($menus as $menu)
                @php
                    $categoryName = optional($menu->category)->name ?? 'Tanpa Kategori';
                    $categorySlug = \Illuminate\Support\Str::slug($categoryName);
                    $imageUrl = $menu->image_url ?: asset('assets/img/no-image.png');
                @endphp

                <div class="menu-card"
                    data-name="{{ \Illuminate\Support\Str::lower($menu->name) }}"
                    data-category="{{ $categorySlug }}">

                    <div class="menu-img-wrap">
                        <img src="{{ $imageUrl }}"
                            class="menu-img"
                            alt="{{ $menu->name }}"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='{{ asset('assets/img/no-image.png') }}';">

                        <div class="price-floating">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="menu-body">
                        <div class="menu-title">{{ $menu->name }}</div>
                        <div class="menu-desc">{{ $categoryName }}</div>

                        <button type="button"
                            class="btn-add js-add-cart"
                            data-id="{{ $menu->id }}"
                            data-name="{{ $menu->name }}"
                            data-price="{{ $menu->price }}">
                            + Tambah
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <h5>Belum ada menu</h5>
                    <p>Menu aktif belum tersedia.</p>
                </div>
            @endforelse
        </div>

        <div class="no-menu-result" id="noMenuResult">
            Menu tidak ditemukan
        </div>
    </div>

    {{-- HALAMAN KERANJANG --}}
    <div class="page-view" id="pageCart">
        <div class="blue-page-header">
            <h4>Keranjang</h4>
            <small>Meja {{ $table->table_code }}</small>
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
        <button type="button" class="bottom-nav-item active" id="navMenu" onclick="QrCustomerOrder.showPage('menu')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 7h16M4 12h16M4 17h16"></path>
            </svg>
            Menu
        </button>

        <button type="button" class="bottom-nav-item" id="navCart" onclick="QrCustomerOrder.showPage('cart')">
            <span class="nav-badge" id="navCartBadge">0</span>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 4h2l2.5 13h10L21 8H7"></path>
                <circle cx="10" cy="20" r="1"></circle>
                <circle cx="18" cy="20" r="1"></circle>
            </svg>
            Keranjang
        </button>

        <button type="button" class="bottom-nav-item" id="navOrders" onclick="QrCustomerOrder.showPage('orders')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M7 3h7l5 5v13H7z"></path>
                <path d="M14 3v5h5"></path>
                <path d="M10 13h6M10 17h6M10 9h2"></path>
            </svg>
            Pesanan
        </button>
    </div>

</div>


@endsection

@push('scripts')
<script>
    window.QrCustomerConfig = {
        tenantSlug: @json($tenant->slug),
        outletId: @json($outlet->id),
        tableId: @json($table->id),
        tableNumber: @json($table->table_code),
        csrfToken: @json(csrf_token()),
        <!-- DEBUG: Route URL = {{ route('qr.order.store', ['tenant' => $tenant->slug, 'outlet' => $outlet->id]) }} -->
        checkoutUrl: "{{ route('qr.order.store', ['tenant' => $tenant->slug, 'outlet' => $outlet->id]) }}",
        cartStorageKey: 'qr_cart_' + @json($tenant->id) + '_' + @json($outlet->id) + '_' + @json($table->id),
        orderStorageKey: 'qr_orders_' + @json($tenant->id) + '_' + @json($outlet->id) + '_' + @json($table->id),
        paymentOptions: @json($paymentOptions),
        tax_enabled: @json((bool) $settings->tax_enabled),
        tax_rate: Number(@json((float) $settings->tax_rate)),
        tax_inclusive: @json((bool) $settings->tax_inclusive),
        service_enabled: @json((bool) $settings->service_enabled),
        service_rate: Number(@json((float) $settings->service_rate)),
        service_inclusive: @json((bool) $settings->service_inclusive),
        receiptUrl: @json(session('receipt_url')),
    };
</script>

<script src="{{ asset('assets/js/qr/customer-order.js') }}"></script>
@endpush