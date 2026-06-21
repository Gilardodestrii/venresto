@extends('layouts.marketing')

@section('title', 'Daftar Trial — VenResto')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
  <div class="flex justify-center">
    <div class="w-full lg:w-7/12">
      <div class="mb-6 text-center">
        <h1 class="font-bold">Mulai Trial 7 Hari</h1>
        <p class="text-slate-500 mb-0">Buat tenant & akun owner. Tanpa kartu kredit.</p>
      </div>

      {{-- Alert global (opsional) --}}
      @if(session('status'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ url('/signup') }}" id="signup-form" novalidate>
        @csrf

        {{-- Nama Restoran --}}
        <div class="mb-4">
          <label for="restaurant_name" class="block text-sm font-medium text-slate-700 mb-1">Nama Restoran</label>
          <input
            id="restaurant_name"
            type="text"
            class="w-full h-11 px-4 rounded-xl border border-slate-200 @error('restaurant_name') border-red-500 ring-1 ring-red-500 @enderror"
            name="restaurant_name"
            placeholder="Contoh: Warung Aji"
            value="{{ old('restaurant_name') }}"
            required
            autofocus>
          @error('restaurant_name')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
          @else
            <div class="text-sm text-slate-500 mt-1">Gunakan nama brand yang dikenali pelanggan.</div>
          @enderror
        </div>

        {{-- Tenant Slug + preview subdomain --}}
        <div class="mb-4">
          <label for="tenant_slug" class="block text-sm font-medium text-slate-700 mb-1">Tenant Slug</label>
          <div class="flex">
            <input
              id="tenant_slug"
              type="text"
              class="w-full h-11 px-4 rounded-l-xl border border-slate-200 @error('tenant_slug') border-red-500 ring-1 ring-red-500 @enderror"
              name="tenant_slug"
              placeholder="warung-aji"
              value="{{ old('tenant_slug') }}"
              pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$"
              inputmode="lowercase"
              required>
            <span class="hidden sm:inline-flex items-center px-3 rounded-r-lg bg-slate-100 border border-l-0 border-slate-200 text-slate-600" id="domain-preview">.appku.com</span>
            @error('tenant_slug')
              <div class="text-red-600 text-sm mt-1 block">{{ $message }}</div>
            @else
              <div class="text-sm text-slate-500 mt-1">Hanya huruf kecil, angka, dan tanda minus (-). Contoh: <code>warung-aji</code></div>
            @enderror
          </div>
          <div class="mt-2 text-sm text-slate-600">
            Subdomain: <code id="full-domain" class="font-mono">https://{{ old('tenant_slug','slug-kamu') }}.appku.com</code>
          </div>
        </div>

        {{-- Nama Owner --}}
        <div class="mb-4">
          <label for="owner_name" class="block text-sm font-medium text-slate-700 mb-1">Nama Owner</label>
          <input
            id="owner_name"
            type="text"
            class="w-full h-11 px-4 rounded-xl border border-slate-200 @error('owner_name') border-red-500 ring-1 ring-red-500 @enderror"
            name="owner_name"
            placeholder="Nama lengkap"
            value="{{ old('owner_name') }}"
            required>
          @error('owner_name')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
          <input
            id="email"
            type="email"
            class="w-full h-11 px-4 rounded-xl border border-slate-200 @error('email') border-red-500 ring-1 ring-red-500 @enderror"
            name="email"
            placeholder="email@domain.com"
            value="{{ old('email') }}"
            required>
          @error('email')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
          @else
            <div class="text-sm text-slate-500 mt-1">Kami akan kirim konfirmasi ke email ini.</div>
          @enderror
        </div>

        {{-- No HP --}}
        <div class="mb-4">
          <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">No HP</label>
          <input
            id="phone"
            type="tel"
            class="w-full h-11 px-4 rounded-xl border border-slate-200 @error('phone') border-red-500 ring-1 ring-red-500 @enderror"
            name="phone"
            placeholder="08xxxxxxxxxx"
            value="{{ old('phone') }}"
            pattern="^0[0-9]{9,15}$"
            required>
          @error('phone')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
          @else
            <div class="text-sm text-slate-500 mt-1">Format Indonesia, mulai dengan 0. Contoh: 081234567890.</div>
          @enderror
        </div>

        {{-- Password + strength meter --}}
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
          <div class="flex">
            <input
              id="password"
              type="password"
              class="w-full h-11 px-4 rounded-l-xl border border-slate-200 @error('password') border-red-500 ring-1 ring-red-500 @enderror"
              name="password"
              placeholder="Min. 8 karakter"
              minlength="8"
              required>
            <button class="px-4 rounded-r-xl border border-l-0 border-slate-200 bg-white text-slate-600 hover:bg-slate-50" type="button" id="togglePass">
              <i class="bi bi-eye"></i>
            </button>
            @error('password')
              <div class="text-red-600 text-sm mt-1 block">{{ $message }}</div>
            @enderror
          </div>

          <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden mt-2">
            <div id="pwd-bar" class="h-full bg-slate-500 rounded-full transition-all duration-300" style="width: 0%;" role="progressbar"></div>
          </div>
          <div id="pwd-hint" class="text-sm text-slate-500 mt-1">Gunakan kombinasi huruf, angka, dan simbol.</div>
        </div>

        {{-- Paket (radio cards) --}}
        {{-- di form --}}
        <div class="mb-4">
          <label class="block text-sm font-medium text-slate-700 mb-2">Paket</label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($plans as $p)
              <div class="col-span-1">
                <label class="cursor-pointer">
                  <input class="peer sr-only" type="radio" name="plan"
                        value="{{ $p->code }}"
                        {{ old('plan', $selected) === $p->code ? 'checked' : '' }}>
                  <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-sky-500 peer-checked:bg-sky-50 transition-all">
                    <div class="flex items-center justify-between">
                      <span class="font-semibold">{{ $p->name }}</span>
                      @if(!is_null($p->price_monthly))
                        <span class="inline-flex items-center rounded-full bg-sky-100 text-sky-700 px-2 py-0.5 text-sm font-medium">
                          Rp {{ number_format($p->price_monthly,0,',','.') }}/bln
                        </span>
                      @endif
                    </div>
                    <div class="text-sm text-slate-500 mt-1">
                      {{-- contoh highlight fitur utama --}}
                      @if(data_get($p->features_json,'printer_kitchen')) Printer dapur @else Tanpa printer dapur @endif
                    </div>
                  </div>
                </label>
              </div>
            @empty
              <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg col-span-2">
                Paket belum tersedia. Hubungi admin.
              </div>
            @endforelse
          </div>
          @error('plan')<div class="text-red-600 text-sm mt-1 block">{{ $message }}</div>@enderror
        </div>


        {{-- Terms --}}
        <div class="flex items-start mb-4">
          <input class="w-4 h-4 mt-0.5 rounded border-slate-300 text-sky-600 focus:ring-sky-500 @error('agree') border-red-500 @enderror" type="checkbox" value="1" id="agree" name="agree" {{ old('agree') ? 'checked' : '' }} required>
          <label class="ml-2 text-sm text-slate-700" for="agree">
            Saya menyetujui <a href="{{ url('/terms') }}" target="_blank" class="text-sky-600 hover:underline">Syarat Layanan</a> dan <a href="{{ url('/privacy') }}" target="_blank" class="text-sky-600 hover:underline">Kebijakan Privasi</a>.
          </label>
          @error('agree')
            <div class="text-red-600 text-sm mt-1 block">{{ $message }}</div>
          @enderror
        </div>

        {{-- Submit --}}
        <div class="block">
          <button class="w-full inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-sky-500 text-white font-bold text-base hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition-colors" id="submitBtn" type="submit">
            <span class="mr-2 w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin hidden" id="btnSpinner" role="status" aria-hidden="true"></span>
            Daftar & Buat Tenant
          </button>
        </div>

        <div class="text-center mt-4">
          <span class="text-sm text-slate-500">Sudah punya akun? <a href="{{ url('/login') }}" class="text-sky-600 hover:underline">Masuk</a></span>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
// ====== Konfigurasi dasar (ubah sesuai domain kamu)
const BASE_SUBDOMAIN = '.appku.com';

// ====== Helper: slugify
function slugify(str) {
  return (str || '')
    .toString()
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g,'')        // hapus diakritik
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')           // non-alnum -> -
    .replace(/^-+|-+$/g, '')               // trim -
    .replace(/--+/g, '-');                 // duplikat - -> satu
}

// ====== Update preview domain
function updateDomainPreview() {
  const slug = document.getElementById('tenant_slug').value || 'slug-kamu';
  document.getElementById('full-domain').textContent = 'https://' + slug + BASE_SUBDOMAIN;
}

// ====== Password strength meter (sederhana)
function calcStrength(pwd){
  let s = 0;
  if (pwd.length >= 8) s += 25;
  if (/[A-Z]/.test(pwd)) s += 20;
  if (/[a-z]/.test(pwd)) s += 15;
  if (/[0-9]/.test(pwd)) s += 20;
  if (/[^A-Za-z0-9]/.test(pwd)) s += 20;
  return Math.min(s, 100);
}

(function(){
  const nameEl = document.getElementById('restaurant_name');
  const slugEl = document.getElementById('tenant_slug');
  const passEl = document.getElementById('password');
  const bar    = document.getElementById('pwd-bar');
  const hint   = document.getElementById('pwd-hint');
  const toggle = document.getElementById('togglePass');
  const form   = document.getElementById('signup-form');
  const submit = document.getElementById('submitBtn');
  const spinner= document.getElementById('btnSpinner');

  // Auto generate slug saat nama diubah (jika slug belum disentuh manual)
  let slugTouched = false;
  slugEl.addEventListener('input', () => { slugTouched = true; updateDomainPreview(); });
  nameEl.addEventListener('input', () => {
    if (!slugTouched) {
      slugEl.value = slugify(nameEl.value);
      updateDomainPreview();
    }
  });
  // Inisialisasi preview domain
  updateDomainPreview();

  // Password meter
  passEl.addEventListener('input', () => {
    const score = calcStrength(passEl.value);
    bar.style.width = score + '%';
    bar.classList.toggle('bg-red-500', score < 40);
    bar.classList.toggle('bg-yellow-500', score >= 40 && score < 70);
    bar.classList.toggle('bg-green-500', score >= 70);
    if (score < 40)      hint.textContent = 'Password lemah. Tambah panjang & kombinasi.';
    else if (score < 70) hint.textContent = 'Cukup. Bisa ditingkatkan dengan simbol & huruf besar.';
    else                 hint.textContent = 'Kuat. Jangan gunakan ulang di tempat lain.';
  });

  // Toggle show/hide password
  toggle.addEventListener('click', () => {
    const showing = passEl.type === 'text';
    passEl.type = showing ? 'password' : 'text';
    toggle.innerHTML = showing ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
  });

  // Submit UX
  form.addEventListener('submit', () => {
    submit.setAttribute('disabled', 'disabled');
    spinner.classList.remove('hidden');
  });
})();
</script>
@endpush
@endsection
