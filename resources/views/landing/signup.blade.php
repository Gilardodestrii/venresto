@extends('layouts.marketing')

@section('title','Daftar — VenResto')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-14"
     style="background: radial-gradient(circle at top left, rgba(14,165,233,.14), transparent 34%), linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);">

  <div class="w-full max-w-lg">

    {{-- Header --}}
    <div class="text-center mb-6">
      <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl text-white mb-3"
           style="background: linear-gradient(135deg, #38bdf8, #0ea5e9); box-shadow: 0 14px 34px rgba(14,165,233,.28);">
        <i class="bi bi-shop text-2xl"></i>
      </div>
      <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mulai Trial Gratis</h1>
      <p class="text-slate-500 text-sm mt-1">7 hari gratis, tanpa kartu kredit.</p>
    </div>

    {{-- ==================== STEP 1: Pilih metode ==================== --}}
    <div id="step-1" class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-200/80 p-6 lg:p-8">

      <p class="text-center text-sm font-semibold text-slate-700 mb-5">Buat akun dengan</p>

      <button type="button" id="btn-google"
              class="w-full h-12 rounded-2xl border border-slate-300 bg-white hover:bg-slate-50 flex items-center justify-center gap-3 text-sm font-semibold text-slate-700 transition shadow-sm mb-3">
        <svg class="w-5 h-5" viewBox="0 0 48 48">
          <path fill="#4285F4" d="M44.5 20H24v8.5h11.8C34.7 33.9 29.9 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6-6C34.5 5.1 29.5 3 24 3 12.4 3 3 12.4 3 24s9.4 21 21 21c10.9 0 20.2-7.9 20.2-21 0-1.4-.1-2.7-.3-4z"/>
          <path fill="#34A853" d="M6.3 14.7l7 5.1C15 16.1 19.1 13 24 13c3.1 0 5.9 1.1 8.1 2.9l6-6C34.5 5.1 29.5 3 24 3c-7.5 0-14 4.2-17.7 10.7z"/>
          <path fill="#FBBC05" d="M24 45c5.8 0 10.7-1.9 14.3-5.2l-6.6-5.4C29.9 36 27.1 37 24 37c-5.8 0-10.7-3.9-12.5-9.3l-7 5.4C8.1 40.7 15.5 45 24 45z"/>
          <path fill="#EA4335" d="M44.5 20H24v8.5h11.8c-.8 2.3-2.3 4.3-4.3 5.7l6.6 5.4C42.4 36.1 45 30.5 45 24c0-1.4-.1-2.7-.3-4z"/>
        </svg>
        Lanjut dengan Google
      </button>

      <div class="flex items-center gap-3 my-4">
        <div class="flex-1 h-px bg-slate-200"></div>
        <span class="text-xs text-slate-400 font-medium">atau</span>
        <div class="flex-1 h-px bg-slate-200"></div>
      </div>

      <button type="button" id="btn-email"
              class="w-full h-12 rounded-2xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-extrabold flex items-center justify-center gap-2 transition shadow-sm">
        <i class="bi bi-envelope"></i> Daftar dengan Email
      </button>

      <p class="text-center text-xs text-slate-400 mt-5">
        Sudah punya akun?
        <a href="{{ url('/login') }}" class="text-sky-600 font-semibold hover:underline">Masuk</a>
      </p>
    </div>

    {{-- ==================== STEP 2: Pilih paket ==================== --}}
    @php
      $preselected = old('plan', $selected ?? optional($plans->first())->code);
    @endphp
    <div id="step-2" class="hidden bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-200/80 p-6 lg:p-8">

      <button type="button" id="btn-back-1"
              class="flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 mb-5 transition">
        <i class="bi bi-arrow-left"></i> Kembali
      </button>

      <h2 class="text-lg font-bold text-slate-900 mb-1">Pilih Paket</h2>
      <p class="text-sm text-slate-500 mb-5">Semua paket termasuk trial 7 hari gratis.</p>

      <div class="space-y-3" id="plan-cards">
        @foreach($plans->where('code', '!=', 'enterprise') as $plan)
          @php
            $monthly = $plan->price_monthly ? 'Rp '.number_format($plan->price_monthly,0,',','.').'/bln' : 'Gratis';
            $isSelected = $preselected === $plan->code;
          @endphp
          <button type="button"
                  data-plan="{{ $plan->code }}"
                  class="plan-card w-full text-left px-4 py-4 rounded-2xl border-2 transition
                         {{ $isSelected ? 'border-sky-500 bg-sky-50 ring-2 ring-sky-200' : 'border-slate-200 hover:border-sky-300 bg-white' }}">
            <div class="flex items-center justify-between">
              <div>
                <div class="font-bold text-slate-900 text-sm">{{ $plan->name }}
                  @if($plan->code === 'pro')
                    <span class="ml-2 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">Populer</span>
                  @endif
                </div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $monthly }}</div>
              </div>
              <div class="plan-check w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0
                          {{ $isSelected ? 'border-sky-500 bg-sky-500' : 'border-slate-300' }}">
                @if($isSelected)
                  <i class="bi bi-check text-white text-xs"></i>
                @endif
              </div>
            </div>
          </button>
        @endforeach
      </div>

      <button type="button" id="btn-next-plan"
              class="w-full h-12 rounded-2xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-extrabold flex items-center justify-center gap-2 transition shadow-sm mt-5">
        Lanjutkan <i class="bi bi-arrow-right"></i>
      </button>
    </div>

    {{-- ==================== STEP 3: Form lengkap ==================== --}}
    <div id="step-3" class="hidden bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-200/80 p-6 lg:p-8">

      <button type="button" id="btn-back-2"
              class="flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 mb-5 transition">
        <i class="bi bi-arrow-left"></i> Kembali
      </button>

      {{-- Selected plan badge --}}
      <div id="selected-plan-badge" class="mb-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-sky-50 border border-sky-200 text-sky-700 text-xs font-semibold">
        <i class="bi bi-tag"></i>
        <span id="selected-plan-label">Starter</span>
      </div>

      @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
          <ul class="list-none space-y-1">
            @foreach($errors->all() as $err)
              <li class="flex items-start gap-2"><i class="bi bi-exclamation-circle mt-0.5"></i>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('central.signup.store') }}" novalidate id="signup-form">
        @csrf

        {{-- Nama Restoran --}}
        <div class="mb-4">
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Restoran / Kafe</label>
          <input id="restaurant_name" type="text" name="restaurant_name"
                 class="w-full h-12 px-4 rounded-2xl border @error('restaurant_name') border-red-400 ring-2 ring-red-100 @else border-slate-200 @enderror bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                 value="{{ old('restaurant_name') }}"
                 placeholder="Contoh: Warung Kita"
                 autocomplete="organization" required>
          @error('restaurant_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Slug --}}
        <div class="mb-4">
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            Alamat URL Tenant
            <span class="font-normal text-slate-400 text-xs">(otomatis dari nama)</span>
          </label>
          <div class="flex items-center rounded-2xl border @error('tenant_slug') border-red-400 ring-2 ring-red-100 @else border-slate-200 @enderror bg-white overflow-hidden focus-within:ring-2 focus-within:ring-sky-500 focus-within:border-sky-500 transition">
            <span class="pl-4 pr-1 text-slate-400 text-sm whitespace-nowrap">venresto.id/</span>
            <input id="tenant_slug" type="text" name="tenant_slug"
                   class="flex-1 h-12 pr-2 bg-transparent text-base focus:outline-none"
                   value="{{ old('tenant_slug') }}"
                   placeholder="warung-kita"
                   autocomplete="off" required>
            <span id="slug-status" class="pr-3 text-sm"></span>
          </div>
          @error('tenant_slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          <div id="slug-suggestions" class="hidden mt-2 flex flex-wrap gap-2"></div>
        </div>

        {{-- Nama Owner --}}
        <div class="mb-4">
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Anda</label>
          <input type="text" name="owner_name"
                 class="w-full h-12 px-4 rounded-2xl border @error('owner_name') border-red-400 ring-2 ring-red-100 @else border-slate-200 @enderror bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                 value="{{ old('owner_name') }}"
                 placeholder="Nama lengkap Anda"
                 autocomplete="name" required>
          @error('owner_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
          <input type="email" name="email"
                 class="w-full h-12 px-4 rounded-2xl border @error('email') border-red-400 ring-2 ring-red-100 @else border-slate-200 @enderror bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                 value="{{ old('email') }}"
                 placeholder="nama@email.com"
                 autocomplete="email" required>
          @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Nomor HP --}}
        <div class="mb-4">
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP / WhatsApp</label>
          <input type="tel" name="phone"
                 class="w-full h-12 px-4 rounded-2xl border @error('phone') border-red-400 ring-2 ring-red-100 @else border-slate-200 @enderror bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                 value="{{ old('phone') }}"
                 placeholder="08xxxxxxxxxx"
                 autocomplete="tel" required>
          @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Password --}}
        <div class="mb-4">
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
          <div class="flex">
            <input id="password" type="password" name="password"
                   class="flex-1 h-12 px-4 rounded-l-2xl border @error('password') border-red-400 ring-2 ring-red-100 @else border-slate-200 @enderror bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                   placeholder="Min. 8 karakter"
                   autocomplete="new-password" required>
            <button type="button" id="togglePass"
                    class="px-4 rounded-r-2xl border border-l-0 border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition"
                    aria-label="Tampilkan password">
              <i class="bi bi-eye"></i>
            </button>
          </div>
          @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Hidden plan input --}}
        <input type="hidden" name="plan" id="plan-input" value="{{ $preselected }}">

        <button type="submit"
                class="w-full h-12 rounded-2xl bg-sky-500 hover:bg-sky-600 text-white text-sm font-extrabold flex items-center justify-center gap-2 transition shadow-sm mt-2">
          <i class="bi bi-rocket-takeoff"></i> Buat Akun & Mulai Trial
        </button>

        <p class="text-center text-xs text-slate-400 mt-4">
          Dengan mendaftar, kamu setuju dengan
          <a href="#" class="text-sky-600 hover:underline">Syarat & Ketentuan</a> kami.
        </p>
      </form>
    </div>

    <div class="text-center mt-4">
      <a href="{{ url('/') }}" class="text-sm text-slate-500 hover:text-slate-700 no-underline flex items-center justify-center gap-1">
        <i class="bi bi-arrow-left"></i>Kembali ke landing page
      </a>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  const step1      = document.getElementById('step-1');
  const step2      = document.getElementById('step-2');
  const step3      = document.getElementById('step-3');
  const btnEmail   = document.getElementById('btn-email');
  const btnGoogle  = document.getElementById('btn-google');
  const btnBack1   = document.getElementById('btn-back-1');
  const btnBack2   = document.getElementById('btn-back-2');
  const btnNext    = document.getElementById('btn-next-plan');
  const planInput  = document.getElementById('plan-input');
  const planLabel  = document.getElementById('selected-plan-label');

  // Preselected plan from URL (?plan=)
  let activePlan = planInput.value || '';

  // Jika ada error validasi, langsung ke step 3
  @if($errors->any())
    step1.classList.add('hidden');
    step3.classList.remove('hidden');
  @endif

  function showStep(n) {
    step1.classList.toggle('hidden', n !== 1);
    step2.classList.toggle('hidden', n !== 2);
    step3.classList.toggle('hidden', n !== 3);
  }

  btnEmail.addEventListener('click', () => showStep(2));
  btnBack1.addEventListener('click', () => showStep(1));
  btnBack2.addEventListener('click', () => showStep(2));

  btnGoogle.addEventListener('click', () => {
    alert('Google login belum tersedia. Gunakan email untuk sementara.');
  });

  // Plan card selection
  document.querySelectorAll('.plan-card').forEach(card => {
    card.addEventListener('click', () => {
      activePlan = card.dataset.plan;
      document.querySelectorAll('.plan-card').forEach(c => {
        const check = c.querySelector('.plan-check');
        const isActive = c.dataset.plan === activePlan;
        c.classList.toggle('border-sky-500', isActive);
        c.classList.toggle('bg-sky-50', isActive);
        c.classList.toggle('ring-2', isActive);
        c.classList.toggle('ring-sky-200', isActive);
        c.classList.toggle('border-slate-200', !isActive);
        c.classList.toggle('bg-white', !isActive);
        check.classList.toggle('border-sky-500', isActive);
        check.classList.toggle('bg-sky-500', isActive);
        check.classList.toggle('border-slate-300', !isActive);
        check.innerHTML = isActive ? '<i class="bi bi-check text-white text-xs"></i>' : '';
      });
    });
  });

  btnNext.addEventListener('click', () => {
    if (!activePlan) return;
    planInput.value = activePlan;
    // Update badge label
    const nameMap = { starter: 'Starter', pro: 'Pro' };
    planLabel.textContent = 'Paket ' + (nameMap[activePlan] || activePlan);
    showStep(3);
  });

  // Toggle password
  const passInput = document.getElementById('password');
  const passBtn   = document.getElementById('togglePass');
  if (passBtn && passInput) {
    passBtn.addEventListener('click', () => {
      const show = passInput.type === 'password';
      passInput.type = show ? 'text' : 'password';
      passBtn.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
      passBtn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
    });
  }

  // Slug auto-generate
  const nameInput = document.getElementById('restaurant_name');
  const slugInput = document.getElementById('tenant_slug');

  function toSlug(str) {
    return str.toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '')
      .trim()
      .replace(/[\s_]+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '');
  }

  nameInput.addEventListener('input', () => {
    const generated = toSlug(nameInput.value);
    slugInput.value = generated;
    generated.length >= 3 ? debouncedCheck() : clearSlugUI();
  });

  slugInput.addEventListener('input', () => {
    slugInput.value.length >= 3 ? debouncedCheck() : clearSlugUI();
  });

  const slugStatus      = document.getElementById('slug-status');
  const slugSuggestions = document.getElementById('slug-suggestions');
  let checkTimer = null;

  function clearSlugUI() {
    slugStatus.innerHTML = '';
    slugSuggestions.classList.add('hidden');
    slugSuggestions.innerHTML = '';
  }

  function debouncedCheck() {
    clearTimeout(checkTimer);
    slugStatus.innerHTML = '<span class="text-slate-400 text-xs">...</span>';
    checkTimer = setTimeout(checkSlug, 450);
  }

  async function checkSlug() {
    const slug = slugInput.value.trim();
    if (slug.length < 3) { clearSlugUI(); return; }
    try {
      const res  = await fetch(`/signup/check-slug?slug=${encodeURIComponent(slug)}`);
      const data = await res.json();
      if (data.available) {
        slugStatus.innerHTML = '<span class="text-green-600 text-xs font-semibold">✓ Tersedia</span>';
        slugSuggestions.classList.add('hidden');
      } else {
        slugStatus.innerHTML = '<span class="text-red-500 text-xs font-semibold">✗ Sudah dipakai</span>';
        if (data.suggestions && data.suggestions.length) {
          slugSuggestions.innerHTML = data.suggestions.map(s =>
            `<button type="button" class="slug-suggestion px-3 py-1 rounded-full bg-sky-50 border border-sky-200 text-sky-700 text-xs font-semibold hover:bg-sky-100 transition" data-slug="${s}">${s}</button>`
          ).join('');
          slugSuggestions.classList.remove('hidden');
          slugSuggestions.querySelectorAll('.slug-suggestion').forEach(btn => {
            btn.addEventListener('click', () => {
              slugInput.value = btn.dataset.slug;
              debouncedCheck();
            });
          });
        } else {
          slugSuggestions.classList.add('hidden');
        }
      }
    } catch (e) { clearSlugUI(); }
  }

});
</script>
@endpush
