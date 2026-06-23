@extends('layouts.admin')

@section('content')

<div class="min-h-screen bg-slate-100 pb-24 lg:pb-8 px-3 lg:px-4" x-data="{ mobileCartOpen: false }">

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
                <div class="bg-white/88 backdrop-blur-xl border border-white/50 rounded-3xl shadow-[0_10px_30px_rgba(15,23,42,.05)] p-4 sticky top-[72px] overflow-visible pos-card pos-cart-card">
                    @include('admin.pos.partials.cart-form')
                </div>
            </div>
        </div>

        {{-- MOBILE CART DRAWER (Alpine.js based) --}}
        <div x-show="mobileCartOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="mobileCartOpen = false"
             @keydown.escape.window="mobileCartOpen = false"
             class="fixed inset-0 z-50 lg:hidden"
             style="display: none;"
             id="mobileCartDrawer"
             aria-labelledby="mobileCartDrawerLabel">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50"></div>

            {{-- Drawer --}}
            <div x-show="mobileCartOpen"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="translate-y-0"
                 x-transition:leave-end="translate-y-full"
                 class="absolute inset-x-0 bottom-0 bg-white rounded-t-3xl shadow-2xl max-h-[90vh] flex flex-col"
                 @click.stop>

                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                    <div>
                        <h5 class="font-bold text-base mb-0 text-gray-900" id="mobileCartDrawerLabel">
                            Keranjang Order
                        </h5>
                        <small class="text-gray-500 text-xs">Lengkapi data customer dan pembayaran</small>
                    </div>

                    <button type="button"
                            @click="mobileCartOpen = false"
                            class="w-9 h-9 rounded-full hover:bg-gray-100 text-gray-500 flex items-center justify-center transition-colors"
                            aria-label="Close">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-3">
                    @include('admin.pos.partials.cart-form')
                </div>
            </div>
        </div>

        {{-- MOBILE CART BAR --}}
        <div class="fixed left-0 right-0 bottom-0 z-40 p-3 bg-gradient-to-t from-slate-100 via-slate-100/75 to-transparent lg:hidden mobile-cart-bar" id="mobileCartBar">
            <button type="button"
                    @click="mobileCartOpen = true"
                    class="w-full rounded-3xl p-4 bg-sky-500 text-white flex justify-between items-center shadow-[0_18px_45px_rgba(14,165,233,.35)] mobile-cart-btn"
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

@endsection

@push('scripts')
{{-- POS settings (must be first, before modules read it) --}}
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
</script>

{{-- POS JS modules (order matters) --}}
<script src="{{ asset('assets/js/pos/pos-core.js') }}"></script>
<script src="{{ asset('assets/js/pos/pos-cart.js') }}"></script>
<script src="{{ asset('assets/js/pos/pos-ui.js') }}"></script>
<script src="{{ asset('assets/js/pos/pos-payment.js') }}"></script>
<script src="{{ asset('assets/js/pos/pos-receipt.js') }}"></script>
<script src="{{ asset('assets/js/pos/pos-shortcut.js') }}"></script>
<script src="{{ asset('assets/js/pos/pos-offline.js') }}"></script>
<script src="{{ asset('assets/js/pos/pos-init.js') }}"></script>
<script src="{{ asset('assets/js/pos-auto-receipt.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const desktopCart  = document.querySelector('.pos-cart-desktop');
    const mobileDrawer = document.getElementById('mobileCartDrawer');

    // Disable controls in the inactive cart panel so duplicate
    // hidden inputs don't get submitted with the form.
    function setControlsState(container, disabled) {
        if (!container) return;
        container.querySelectorAll('input, select, textarea, button').forEach(el => {
            el.disabled = disabled;
        });
    }

    function syncCartControls() {
        const isMobile = window.innerWidth < 1024; // matches Tailwind lg breakpoint
        setControlsState(desktopCart,  isMobile);
        setControlsState(mobileDrawer, !isMobile);
        window.POS?.cart?.render?.();
    }

    syncCartControls();
    window.addEventListener('resize', syncCartControls);

    // Re-sync when Alpine opens/closes mobile drawer
    document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
            // triggers whenever mobileCartOpen changes (Alpine reactivity)
            setTimeout(syncCartControls, 50);
        });
    });
});
</script>
@endpush
