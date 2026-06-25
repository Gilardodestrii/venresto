@extends('layouts.marketing')

@section('title','Harga VenResto — POS & QR Menu untuk Restoran')
@section('meta_description','Pilih paket VenResto: Starter, Pro, atau Enterprise. Coba gratis 7 hari. Hemat 15% untuk pembayaran tahunan.')

@section('content')
<div x-data="pricingToggle()">

{{-- Hero + Toggle --}}
<section class="border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 py-14 text-center">
    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold tracking-wide uppercase mb-4">Trial 7 hari • Tanpa kartu kredit</span>
    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mt-2">Harga yang transparan,<br class="hidden md:block"> tanpa biaya tersembunyi</h1>
    <p class="text-gray-500 mt-4 text-lg">Bayar bulanan atau hemat <strong class="text-green-600">15%</strong> dengan paket tahunan.</p>

    {{-- Toggle Billing --}}
    <div class="inline-flex items-center gap-4 bg-gray-100 rounded-2xl px-5 py-3 mt-8">
      <span class="text-sm font-medium" :class="!annual ? 'text-gray-900' : 'text-gray-400'">Bulanan</span>

      {{-- Alpine toggle switch --}}
      <button
        @click="annual = !annual"
        :class="annual ? 'bg-blue-600' : 'bg-gray-300'"
        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        role="switch"
        :aria-checked="annual.toString()"
        aria-label="Toggle tahunan"
      >
        <span
          :class="annual ? 'translate-x-6' : 'translate-x-1'"
          class="inline-block h-4 w-4 transform rounded-full bg-white shadow-md transition-transform"
        ></span>
      </button>

      <span class="text-sm font-medium" :class="annual ? 'text-gray-900' : 'text-gray-400'">
        Tahunan
        <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Hemat 15%</span>
      </span>
    </div>
  </div>
</section>

{{-- Pricing Cards --}}
<section>
  <div class="max-w-7xl mx-auto px-4 py-14">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">

      {{-- STARTER --}}
      <div class="relative flex flex-col p-8 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-lg font-bold text-gray-900">Starter</h3>
          <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Untuk mulai</span>
        </div>
        <p class="text-sm text-gray-500 mb-6">Cocok untuk warung & kafe kecil yang baru go-digital.</p>

        <div class="mb-6">
          <div class="flex items-end gap-1">
            <span class="text-4xl font-bold text-gray-900" x-text="annual ? 'Rp 169.000' : 'Rp 199.000'">Rp 199.000</span>
            <span class="text-gray-400 mb-1">/bln</span>
          </div>
          <p class="text-xs text-gray-400 mt-1" x-text="annual ? 'Rp 2.028.000 ditagih per tahun' : 'Ditagih per bulan'">Ditagih per bulan</p>
        </div>

        <ul class="space-y-3 text-sm text-gray-600 flex-1 mb-8">
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i><span>1 outlet, 10 meja</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i><span>QR Menu & pesanan ke dapur</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i><span>POS kasir dasar (hold, diskon)</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i><span>Laporan harian dasar</span></li>
          <li class="flex items-start gap-2 opacity-40"><i class="bi bi-x-circle text-gray-400 mt-0.5 shrink-0"></i><span>Inventory & recipe</span></li>
          <li class="flex items-start gap-2 opacity-40"><i class="bi bi-x-circle text-gray-400 mt-0.5 shrink-0"></i><span>Split bill, service charge</span></li>
          <li class="flex items-start gap-2 opacity-40"><i class="bi bi-x-circle text-gray-400 mt-0.5 shrink-0"></i><span>Role manager & audit log</span></li>
        </ul>

        <a href="{{ route('central.signup', ['plan' => 'starter']) }}" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl border-2 border-blue-600 text-blue-600 font-semibold text-sm hover:bg-blue-50 transition-colors">Coba Gratis 7 Hari</a>
      </div>

      {{-- PRO (RECOMMENDED) --}}
      <div class="relative flex flex-col p-8 bg-blue-600 border-2 border-blue-600 rounded-2xl shadow-xl">
        <span class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 rounded-full bg-amber-400 text-amber-900 text-xs font-bold shadow-md whitespace-nowrap">⭐ Paling Populer</span>
        <div class="flex items-center justify-between mb-2 mt-2">
          <h3 class="text-lg font-bold text-white">Pro</h3>
          <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-500 text-blue-100">Untuk berkembang</span>
        </div>
        <p class="text-sm text-blue-200 mb-6">Fitur lengkap untuk restoran yang butuh kontrol & laporan.</p>

        <div class="mb-6">
          <div class="flex items-end gap-1">
            <span class="text-4xl font-bold text-white" x-text="annual ? 'Rp 339.000' : 'Rp 399.000'">Rp 399.000</span>
            <span class="text-blue-300 mb-1">/bln</span>
          </div>
          <p class="text-xs text-blue-300 mt-1" x-text="annual ? 'Rp 4.068.000 ditagih per tahun' : 'Ditagih per bulan'">Ditagih per bulan</p>
        </div>

        <ul class="space-y-3 text-sm text-blue-100 flex-1 mb-8">
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-300 mt-0.5 shrink-0"></i><span>3 outlet, meja tak terbatas</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-300 mt-0.5 shrink-0"></i><span>QR Menu → dapur, printer ESC/POS</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-300 mt-0.5 shrink-0"></i><span>POS lengkap (split bill, service charge, tip)</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-300 mt-0.5 shrink-0"></i><span>Inventory & recipe, low-stock alert</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-300 mt-0.5 shrink-0"></i><span>Laporan per outlet/kategori/kasir</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-300 mt-0.5 shrink-0"></i><span>RBAC: owner, manager, cashier, kitchen, waiter</span></li>
        </ul>

        <a href="{{ route('central.signup', ['plan' => 'pro']) }}" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white text-blue-600 font-bold text-sm hover:bg-blue-50 transition-colors shadow-lg">Mulai Trial Pro</a>
      </div>

      {{-- ENTERPRISE --}}
      <div class="relative flex flex-col p-8 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-lg font-bold text-gray-900">Enterprise</h3>
          <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-900 text-gray-100">Skala besar</span>
        </div>
        <p class="text-sm text-gray-500 mb-6">Untuk brand multi-cabang yang butuh kustom & SLA.</p>

        <div class="mb-6">
          <div class="flex items-end gap-1">
            <span class="text-4xl font-bold text-gray-900">Custom</span>
          </div>
          <p class="text-xs text-gray-400 mt-1">Harga sesuai kebutuhan</p>
        </div>

        <ul class="space-y-3 text-sm text-gray-600 flex-1 mb-8">
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i><span>Outlet tak terbatas</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i><span>Integrasi ERP/akuntansi & SSO/2FA</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i><span>Custom workflow dapur & routing printer</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i><span>Dedicated support & SLA</span></li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-green-500 mt-0.5 shrink-0"></i><span>Onboarding & training tim</span></li>
        </ul>

        <a href="https://wa.me/6281234567890?text=Halo%2C%20saya%20tertarik%20dengan%20paket%20Enterprise%20VenResto" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl bg-gray-900 text-white font-semibold text-sm hover:bg-gray-800 transition-colors"><i class="bi bi-whatsapp mr-2"></i>Diskusi via WhatsApp</a>
      </div>

    </div>

    <p class="text-xs text-gray-400 text-center mt-8">Harga belum termasuk pajak yang berlaku. Pembayaran tahunan ditagihkan di muka. Trial 7 hari berlaku untuk Starter &amp; Pro.</p>
  </div>
</section>

{{-- Perbandingan Fitur (card-based, no HTML table) --}}
<section class="bg-gray-50 border-t border-gray-100">
  <div class="max-w-5xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-center text-gray-900 mb-2">Perbandingan Fitur</h2>
    <p class="text-sm text-center text-gray-500 mb-10">Semua paket termasuk onboarding & support via chat.</p>

    {{-- Header row --}}
    <div class="hidden md:grid md:grid-cols-4 gap-4 mb-3 px-4">
      <div></div>
      <div class="text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Starter</span>
      </div>
      <div class="text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Pro</span>
      </div>
      <div class="text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Enterprise</span>
      </div>
    </div>

    {{-- Feature rows --}}
    @php
    $features = [
      ['label' => 'Outlet', 'starter' => '1', 'pro' => '3', 'enterprise' => 'Tak terbatas'],
      ['label' => 'Jumlah meja', 'starter' => '10', 'pro' => 'Tak terbatas', 'enterprise' => 'Tak terbatas'],
      ['label' => 'QR Menu per meja', 'starter' => true, 'pro' => true, 'enterprise' => true],
      ['label' => 'POS Kasir (hold, diskon)', 'starter' => true, 'pro' => true, 'enterprise' => true],
      ['label' => 'Printer dapur ESC/POS', 'starter' => false, 'pro' => true, 'enterprise' => true],
      ['label' => 'Split bill & service charge', 'starter' => false, 'pro' => true, 'enterprise' => true],
      ['label' => 'Inventory & recipe', 'starter' => false, 'pro' => true, 'enterprise' => true],
      ['label' => 'Laporan lanjutan (COGS, kasir, kategori)', 'starter' => false, 'pro' => true, 'enterprise' => true],
      ['label' => 'RBAC (owner, manager, cashier, kitchen)', 'starter' => false, 'pro' => true, 'enterprise' => true],
      ['label' => 'Audit log', 'starter' => false, 'pro' => true, 'enterprise' => true],
      ['label' => 'Integrasi ERP & SSO/2FA', 'starter' => false, 'pro' => false, 'enterprise' => true],
      ['label' => 'Custom workflow & routing printer', 'starter' => false, 'pro' => false, 'enterprise' => true],
      ['label' => 'SLA & dedicated support', 'starter' => false, 'pro' => false, 'enterprise' => true],
    ];
    @endphp

    <div class="space-y-2">
      @foreach($features as $f)
      <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 grid grid-cols-2 md:grid-cols-4 gap-2 items-center shadow-sm">

        {{-- Label --}}
        <div class="col-span-2 md:col-span-1 text-sm font-medium text-gray-700">{{ $f['label'] }}</div>

        {{-- Starter --}}
        <div class="flex items-center gap-1.5 md:justify-center">
          <span class="text-xs text-gray-400 md:hidden">Starter: </span>
          @if($f['starter'] === true)
            <i class="bi bi-check-circle-fill text-green-500 text-base"></i>
          @elseif($f['starter'] === false)
            <i class="bi bi-x-circle text-gray-300 text-base"></i>
          @else
            <span class="text-sm text-gray-700 font-medium">{{ $f['starter'] }}</span>
          @endif
        </div>

        {{-- Pro --}}
        <div class="flex items-center gap-1.5 md:justify-center">
          <span class="text-xs text-gray-400 md:hidden">Pro: </span>
          @if($f['pro'] === true)
            <i class="bi bi-check-circle-fill text-blue-500 text-base"></i>
          @elseif($f['pro'] === false)
            <i class="bi bi-x-circle text-gray-300 text-base"></i>
          @else
            <span class="text-sm text-gray-700 font-medium">{{ $f['pro'] }}</span>
          @endif
        </div>

        {{-- Enterprise --}}
        <div class="flex items-center gap-1.5 md:justify-center">
          <span class="text-xs text-gray-400 md:hidden">Enterprise: </span>
          @if($f['enterprise'] === true)
            <i class="bi bi-check-circle-fill text-green-500 text-base"></i>
          @elseif($f['enterprise'] === false)
            <i class="bi bi-x-circle text-gray-300 text-base"></i>
          @else
            <span class="text-sm text-gray-700 font-medium">{{ $f['enterprise'] }}</span>
          @endif
        </div>

      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- FAQ --}}
<section>
  <div class="max-w-3xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-center text-gray-900 mb-10">Pertanyaan Umum</h2>
    <div class="space-y-4">

      <div class="border border-gray-200 rounded-xl overflow-hidden" x-data="{ open: false }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 transition-colors">
          <span>Apakah bisa ganti paket kapan saja?</span>
          <i class="bi text-gray-400 transition-transform" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-5 pb-4 text-sm text-gray-500">
          Bisa. Upgrade/downgrade pro-rata akan dihitung otomatis pada siklus berikutnya.
        </div>
      </div>

      <div class="border border-gray-200 rounded-xl overflow-hidden" x-data="{ open: false }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 transition-colors">
          <span>Metode pembayaran?</span>
          <i class="bi text-gray-400" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-5 pb-4 text-sm text-gray-500">
          Kartu kredit, transfer virtual account, e-wallet (via Stripe/Midtrans). Invoice otomatis.
        </div>
      </div>

      <div class="border border-gray-200 rounded-xl overflow-hidden" x-data="{ open: false }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 transition-colors">
          <span>Bagaimana setelah trial habis?</span>
          <i class="bi text-gray-400" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-5 pb-4 text-sm text-gray-500">
          Anda bisa lanjut berbayar atau membatalkan. Data tetap aman dan bisa diekspor.
        </div>
      </div>

      <div class="border border-gray-200 rounded-xl overflow-hidden" x-data="{ open: false }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 transition-colors">
          <span>Bisa minta demo langsung?</span>
          <i class="bi text-gray-400" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-5 pb-4 text-sm text-gray-500">
          Tentu. <a href="{{ url('/contact') }}" class="text-blue-600 hover:underline">Hubungi kami</a> untuk sesi demo & konsultasi singkat.
        </div>
      </div>

    </div>

    <div class="text-center mt-10">
      <a href="{{ route('central.signup') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-xl bg-blue-600 text-white font-bold text-base hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">Mulai Trial Gratis 7 Hari</a>
    </div>
  </div>
</section>

</div>{{-- end x-data pricingToggle --}}
@endsection

@push('scripts')
<script>
  function pricingToggle() {
    return {
      annual: false,
    };
  }
</script>
@endpush
