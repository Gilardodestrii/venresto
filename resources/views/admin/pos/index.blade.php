@extends('layouts.admin')

@section('content')

<style>
:root{
    --primary:#0ea5e9;
    --bg:#f4f8fc;
    --card:#ffffff;
    --border:#e5e7eb;
}

body{
    background:var(--bg);
    overflow-x:hidden;
}

.pos-page{
    padding-bottom:32px;
}

.pos-wrapper{
    display:grid;
    grid-template-columns:minmax(0,1fr) 420px;
    gap:24px;
    align-items:start;
}

.pos-card{
    background:rgba(255,255,255,.88);
    border:1px solid rgba(255,255,255,.5);
    backdrop-filter:blur(16px);
    border-radius:24px;
    box-shadow:0 10px 30px rgba(15,23,42,.05);
}

.pos-cart-card{
    position:sticky;
    top:90px;
    overflow:visible;
}


.search-box{ position:relative; }

.search-box i{
    position:absolute;
    left:16px;
    top:50%;
    transform:translateY(-50%);
    color:#94a3b8;
}

.search-input{
    border:none;
    width:100%;
    height:54px;
    border-radius:18px;
    padding-left:46px;
    background:#fff;
}

.search-input:focus{ outline:none; }

.category-scroll{
    display:flex;
    gap:12px;
    overflow-x:auto;
    padding-bottom:6px;
    scrollbar-width:none;
}

.category-scroll::-webkit-scrollbar{
    display:none;
}

.category-btn{
    border:none;
    background:#fff;
    border-radius:16px;
    padding:10px 18px;
    white-space:nowrap;
    font-weight:600;
    transition:.2s;
    border:1px solid #e2e8f0;
}

.category-btn.active,
.category-btn:hover{
    background:var(--primary);
    color:white;
}

.menu-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:18px;
}

.menu-item{
    overflow:hidden;
    transition:.2s;
}

.menu-item:hover{
    transform:translateY(-4px);
}

.menu-image{
    width:100%;
    height:170px;
    object-fit:cover;
}

.menu-content{
    padding:16px;
}

.menu-title{
    font-weight:700;
    font-size:16px;
}

.menu-price{
    font-weight:700;
    color:var(--primary);
}

.btn-add{
    width:44px;
    height:44px;
    border:none;
    border-radius:14px;
    background:var(--primary);
    color:white;
    font-size:22px;
    flex:0 0 auto;
}

.order-type-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
}

.order-type-box{
    position:relative;
    cursor:pointer;
    min-height:64px;
    border-radius:18px;
    border:1px solid #e5e7eb;
    background:rgba(255,255,255,.92);
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px;
    transition:.2s ease;
    user-select:none;
}

.order-type-box:hover{
    border-color:var(--primary);
    transform:translateY(-1px);
}

.order-type-box.active{
    border-color:var(--primary);
    background:rgba(14,165,233,.08);
    box-shadow:0 12px 26px rgba(14,165,233,.12);
}

.order-type-input{
    display:none;
}

.order-type-icon{
    width:38px;
    height:38px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#f1f5f9;
    color:#0f172a;
    flex:0 0 auto;
}

.order-type-box.active .order-type-icon{
    background:var(--primary);
    color:#fff;
}

.order-type-box strong{
    display:block;
    font-size:14px;
    line-height:1.1;
}

.order-type-box small{
    display:block;
    font-size:11px;
    color:#64748b;
    margin-top:3px;
}

.cart-item{
    padding:14px;
    border-radius:18px;
    background:#f8fafc;
    margin-bottom:14px;
}

.qty-box{
    display:flex;
    align-items:center;
    gap:10px;
}

.qty-btn{
    width:28px;
    height:28px;
    border:none;
    border-radius:8px;
    background:white;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
    gap:12px;
}

.summary-total{
    font-size:22px;
    font-weight:800;
}

.checkout-btn{
    height:56px;
    border:none;
    border-radius:18px;
    font-weight:700;
}

.btn-hold{ background:#f1f5f9; }
.btn-pay{ background:var(--primary); color:white; }

.alert{
    backdrop-filter:blur(14px);
    background:rgba(255,255,255,.9);
}

.cart-scroll{
    max-height:none;
    overflow:visible;
}

.mobile-cart-bar{
    position:fixed;
    left:0;
    right:0;
    bottom:0;
    z-index:1040;
    padding:12px;
    background:linear-gradient(to top, rgba(244,248,252,1), rgba(244,248,252,.75), transparent);
}

.mobile-cart-btn{
    width:100%;
    border:none;
    border-radius:22px;
    padding:14px 18px;
    background:var(--primary);
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 18px 45px rgba(14,165,233,.35);
}

.mobile-cart-btn strong{
    display:block;
    font-size:15px;
}

.mobile-cart-btn div div{
    font-size:13px;
    opacity:.9;
}

.mobile-cart-btn span{
    width:44px;
    height:44px;
    border-radius:16px;
    background:rgba(255,255,255,.2);
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:22px;
}

.mobile-cart-drawer{
    height:100vh !important;
    max-height:100vh !important;
    border-radius:0;
}

.mobile-cart-drawer .offcanvas-body{
    overflow-y:auto;
    padding-bottom:110px;
}

.mobile-cart-action{
    position:sticky;
    bottom:0;
    background:#fff;
    padding-top:12px;
    padding-bottom:12px;
}

/* Tablet */
@media(max-width:991px){
    .pos-wrapper{
        grid-template-columns:1fr;
    }

    .pos-cart-desktop{
        display:none;
    }
}

/* Mobile */
@media(max-width:575px){
    .container-fluid{
        padding-left:12px;
        padding-right:12px;
    }

    .pos-page{
        padding-top:12px !important;
        padding-bottom:115px;
    }

    .pos-header{
        flex-direction:column;
        align-items:flex-start !important;
        gap:4px;
        margin-bottom:14px !important;
    }

    .pos-header h3{
        font-size:22px;
    }

    .pos-card{
        border-radius:20px;
    }

    .pos-filter-card{
        padding:14px !important;
        margin-bottom:14px !important;
        position:sticky;
        top:70px;
        z-index:20;
    }

    .search-input{
        height:48px;
        border-radius:16px;
    }

    .category-btn{
        padding:9px 14px;
        font-size:14px;
        border-radius:14px;
    }

    .menu-grid{
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:12px;
    }

    .menu-image{
        height:110px;
    }

    .menu-content{
        padding:12px;
    }

    .menu-title{
        font-size:14px;
        line-height:1.25;
    }

    .menu-price{
        font-size:14px;
    }

    .btn-add{
        width:36px;
        height:36px;
        border-radius:12px;
        font-size:20px;
    }

    .form-select-lg,
    .form-control-lg{
        font-size:15px;
        min-height:46px;
        border-radius:14px;
    }

    .cart-scroll{
        max-height:260px;
    }

    .summary-row{
        font-size:14px;
    }

    .summary-total{
        font-size:18px;
    }

    .checkout-btn{
        height:52px;
        border-radius:16px;
    }
}

/* Extra small */
@media(max-width:390px){
    .menu-grid{
        grid-template-columns:1fr;
    }

    .menu-image{
        height:150px;
    }
}
</style>

<div class="container-fluid py-4 pos-page">

    <div class="d-flex justify-content-between align-items-center mb-4 pos-header">
        <div>
            <h3 class="fw-bold mb-1">POS Cashier</h3>
            <div class="text-secondary">cashier system</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center mb-4">
            <div class="me-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;border-radius:16px;background:rgba(34,197,94,.15);color:#22c55e;font-size:22px;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="fw-bold">Berhasil</div>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 d-flex align-items-center mb-4">
            <div class="me-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;border-radius:16px;background:rgba(239,68,68,.15);color:#ef4444;font-size:22px;">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div>
                <div class="fw-bold">Error</div>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4">
            <div class="fw-bold mb-2">Validasi gagal</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tenant.admin.pos.store', $currentTenant->slug) }}" method="POST" id="posForm">
        @csrf

        <div class="pos-wrapper">
            <div>
                <div class="pos-card pos-filter-card p-4 mb-4">
                    <div class="search-box mb-4">
                        <i class="bi bi-search"></i>
                        <input type="text" class="search-input" id="searchMenu" placeholder="Cari menu...">
                    </div>

                    <div class="category-scroll">
                        <button type="button" class="category-btn active" data-category="all">Semua</button>
                        @foreach($categories as $category)
                            <button type="button" class="category-btn" data-category="{{ $category->id }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="menu-grid">
                    @foreach($menuItems as $item)
                        <div class="pos-card menu-item menu-filter"
                             data-name="{{ strtolower($item->name) }}"
                             data-category="{{ $item->category_id }}">

                            <img src="{{ $item->image_url ?: 'https://placehold.co/600x400/png' }}"
                                 class="menu-image">

                            <div class="menu-content">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="menu-title">{{ $item->name }}</div>
                                        <div class="small text-secondary mb-2">{{ $item->category?->name }}</div>
                                        <div class="menu-price">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <button type="button"
                                            class="btn-add addToCart"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}"
                                            data-price="{{ $item->price }}">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- DESKTOP CART --}}
            <div class="pos-cart-desktop">
                <div class="pos-card pos-cart-card p-4">
                    @include('admin.pos.partials.cart-form')
                </div>
            </div>
        </div>

        {{-- MOBILE CART DRAWER --}}
        <div class="offcanvas offcanvas-bottom mobile-cart-drawer d-lg-none"
             style="height:100vh;"
             tabindex="-1"
             id="mobileCartDrawer"
             aria-labelledby="mobileCartDrawerLabel">

            <div class="offcanvas-header border-bottom">
                <div>
                    <h5 class="offcanvas-title fw-bold mb-0" id="mobileCartDrawerLabel">
                        Keranjang Order
                    </h5>
                    <small class="text-muted">Lengkapi data customer dan pembayaran</small>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close">
                </button>
            </div>

            <div class="offcanvas-body">
                <div class="pos-card p-3 shadow-none border">
                    @include('admin.pos.partials.cart-form')
                </div>
            </div>
        </div>

        {{-- MOBILE CART BAR --}}
        <div class="mobile-cart-bar d-lg-none" id="mobileCartBar">
            <button type="button"
                    class="mobile-cart-btn"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileCartDrawer"
                    aria-controls="mobileCartDrawer">

                <div>
                    <strong id="mobileCartCount">0 Item</strong>
                    <div id="mobileCartTotal">Rp 0</div>
                </div>

                <span>
                    <i class="bi bi-cart3"></i>
                </span>
            </button>
        </div>
    </form>
</div>

<script>
window.posSettings = {
    tax_enabled: @json((bool) $settings->tax_enabled),
    tax_rate: Number(@json((float) $settings->tax_rate)),
    tax_inclusive: @json((bool) $settings->tax_inclusive),
    service_enabled: @json((bool) $settings->service_enabled),
    service_rate: Number(@json((float) $settings->service_rate)),
    service_inclusive: @json((bool) $settings->service_inclusive),
};

window.receiptUrl = @json(session('receipt_url'));

document.addEventListener('DOMContentLoaded', function () {
    const desktopCart = document.querySelector('.pos-cart-desktop');
    const mobileDrawer = document.getElementById('mobileCartDrawer');

    function setControlsState(container, disabled) {
        if (!container) return;

        container.querySelectorAll('input, select, textarea, button').forEach(el => {
            el.disabled = disabled;
        });
    }

    function syncCartControls() {
        const isMobile = window.innerWidth < 992;

        if (isMobile) {
            setControlsState(desktopCart, true);
            setControlsState(mobileDrawer, false);
        } else {
            setControlsState(desktopCart, false);
            setControlsState(mobileDrawer, true);
        }

        if (window.POS?.cart) {
            window.POS.cart.render();
        }
    }

    syncCartControls();
    document.querySelectorAll('.order-type-box').forEach(box => {
        box.addEventListener('click', function () {
            const wrapper = this.closest('.pos-card, .offcanvas-body, form') || document;

            wrapper.querySelectorAll('.order-type-box').forEach(item => {
                item.classList.remove('active');
            });

            this.classList.add('active');

            const input = this.querySelector('input[type="radio"]');

            if (input) {
                input.checked = true;
            }
        });
    });

    window.addEventListener('resize', syncCartControls);

    if (mobileDrawer) {
        mobileDrawer.addEventListener('shown.bs.offcanvas', syncCartControls);
        mobileDrawer.addEventListener('hidden.bs.offcanvas', syncCartControls);
    }
});
</script>

@endsection