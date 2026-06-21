@extends('layouts.admin')

@section('content')

<div class="min-h-screen bg-slate-100 pb-8 px-3 lg:px-4">

    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-2 mb-6 pos-header">
        <div>
            <h3 class="font-bold text-xl lg:text-2xl mb-1">POS Cashier</h3>
            <div class="text-slate-500">cashier system</div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-white/90 backdrop-blur-sm border-0 shadow-sm rounded-4xl flex items-center mb-4 p-4 alert">
            <div class="me-3 flex items-center justify-center" style="width:48px;height:48px;border-radius:16px;background:rgba(34,197,94,.15);color:#22c55e;font-size:22px;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="fw-bold">Berhasil</div>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-white/90 backdrop-blur-sm border-0 shadow-sm rounded-4xl flex items-center mb-4 p-4 alert">
            <div class="me-3 flex items-center justify-center" style="width:48px;height:48px;border-radius:16px;background:rgba(239,68,68,.15);color:#ef4444;font-size:22px;">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div>
                <div class="fw-bold">Error</div>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-white/90 backdrop-blur-sm border-0 shadow-sm rounded-4xl mb-4 p-4">
            <div class="font-bold mb-2">Validasi gagal</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tenant.admin.pos.store', $currentTenant->slug) }}" method="POST" id="posForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_420px] gap-6 items-start pos-wrapper">

            {{-- LEFT: Menu --}}
            <div>
                {{-- Search & Categories Card --}}
                <div class="bg-white/88 backdrop-blur-xl border border-white/50 rounded-3xl shadow-[0_10px_30px_rgba(15,23,42,.05)] p-4 mb-4 pos-card pos-filter-card">
                    {{-- Search Box --}}
                    <div class="relative mb-4 search-box">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" class="search-input w-full h-14 rounded-2xl pl-12 bg-white border-none focus:outline-none" id="searchMenu" placeholder="Cari menu...">
                    </div>

                    {{-- Category Scroll --}}
                    <div class="flex gap-3 overflow-x-auto pb-1.5 category-scroll scrollbar-none">
                        <button type="button" class="shrink-0 px-4 py-2.5 rounded-2xl font-semibold bg-sky-500 text-white border border-slate-200 transition hover:bg-sky-500 hover:text-white category-btn active" data-category="all">Semua</button>
                        @foreach($categories as $category)
                            <button type="button" class="shrink-0 px-4 py-2.5 rounded-2xl font-semibold bg-white text-slate-700 border border-slate-200 transition hover:bg-sky-500 hover:text-white category-btn" data-category="{{ $category->id }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Menu Grid --}}
                <div class="grid grid-cols-1 max-[390px]:grid-cols-1 sm:grid-cols-2 xl:grid-cols-[repeat(auto-fill,minmax(220px,1fr))] gap-4 menu-grid">
                    @foreach($menuItems as $item)
                        <div class="overflow-hidden transition hover:-translate-y-1 bg-white/88 backdrop-blur-xl border border-white/50 rounded-3xl shadow-[0_10px_30px_rgba(15,23,42,.05)] pos-card menu-item menu-filter"
                             data-name="{{ strtolower($item->name) }}"
                             data-category="{{ $item->category_id }}">

                            <img src="{{ $item->image_url ?: 'https://placehold.co/600x400/png' }}"
                                 class="w-full h-[170px] max-[390px]:h-[150px] object-cover menu-image">

                            <div class="p-4 menu-content">
                                <div class="flex justify-between items-start gap-2">
                                    <div>
                                        <div class="font-bold text-base menu-title">{{ $item->name }}</div>
                                        <div class="small text-slate-500 mb-2">{{ $item->category?->name }}</div>
                                        <div class="font-bold text-sky-500 menu-price">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <button type="button"
                                            class="w-11 h-11 rounded-xl bg-sky-500 text-white text-xl flex items-center justify-center shrink-0 btn-add addToCart"
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

            {{-- RIGHT: Desktop Cart --}}
            <div class="pos-cart-desktop hidden lg:block">
                <div class="bg-white/88 backdrop-blur-xl border border-white/50 rounded-3xl shadow-[0_10px_30px_rgba(15,23,42,.05)] p-4 sticky top-[90px] overflow-visible pos-card pos-cart-card">
                    @include('admin.pos.partials.cart-form')
                </div>
            </div>
        </div>

        {{-- MOBILE CART DRAWER --}}
        <div class="offcanvas offcanvas-bottom mobile-cart-drawer d-lg-none lg:hidden"
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
                <div class="bg-white rounded-3xl shadow-none border p-3">
                    @include('admin.pos.partials.cart-form')
                </div>
            </div>
        </div>

        {{-- MOBILE CART BAR --}}
        <div class="fixed left-0 right-0 bottom-0 z-[1040] p-3 bg-gradient-to-t from-slate-100 via-slate-100/75 to-transparent lg:hidden mobile-cart-bar" id="mobileCartBar">
            <button type="button"
                    class="w-full rounded-3xl p-4 bg-sky-500 text-white flex justify-between items-center shadow-[0_18px_45px_rgba(14,165,233,.35)] mobile-cart-btn"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileCartDrawer"
                    aria-controls="mobileCartDrawer">

                <div>
                    <strong id="mobileCartCount" class="block text-base">0 Item</strong>
                    <div id="mobileCartTotal" class="text-sm opacity-90">Rp 0</div>
                </div>

                <span class="w-11 h-11 rounded-2xl bg-white/20 flex items-center justify-center text-2xl">
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
