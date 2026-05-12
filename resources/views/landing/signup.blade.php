@extends('layouts.marketing')

@section('title', 'Daftar Trial — VenResto')
@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="mb-4 text-center">
        <h1 class="fw-bold">Mulai Trial 7 Hari</h1>
        <p class="text-secondary mb-0">Buat tenant & akun owner. Tanpa kartu kredit.</p>
      </div>

      {{-- Alert global (opsional) --}}
      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ url('/signup') }}" id="signup-form" novalidate>
        @csrf

        {{-- Nama Restoran --}}
        <div class="mb-3">
          <label for="restaurant_name" class="form-label">Nama Restoran</label>
          <input
            id="restaurant_name"
            type="text"
            class="form-control @error('restaurant_name') is-invalid @enderror"
            name="restaurant_name"
            placeholder="Contoh: Warung Aji"
            value="{{ old('restaurant_name') }}"
            required
            autofocus>
          @error('restaurant_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @else
            <div class="form-text">Gunakan nama brand yang dikenali pelanggan.</div>
          @enderror
        </div>

        {{-- Tenant Slug + preview subdomain --}}
        <div class="mb-3">
          <label for="tenant_slug" class="form-label">Tenant Slug</label>
          <div class="input-group">
            <input
              id="tenant_slug"
              type="text"
              class="form-control @error('tenant_slug') is-invalid @enderror"
              name="tenant_slug"
              placeholder="warung-aji"
              value="{{ old('tenant_slug') }}"
              pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$"
              inputmode="lowercase"
              required>
            <span class="input-group-text d-none d-sm-inline" id="domain-preview">.appku.com</span>
            @error('tenant_slug')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @else
              <div class="form-text">Hanya huruf kecil, angka, dan tanda minus (-). Contoh: <code>warung-aji</code></div>
            @enderror
          </div>
          <div class="small mt-1">
            Subdomain: <code id="full-domain">https://{{ old('tenant_slug','slug-kamu') }}.appku.com</code>
          </div>
        </div>

        {{-- Nama Owner --}}
        <div class="mb-3">
          <label for="owner_name" class="form-label">Nama Owner</label>
          <input
            id="owner_name"
            type="text"
            class="form-control @error('owner_name') is-invalid @enderror"
            name="owner_name"
            placeholder="Nama lengkap"
            value="{{ old('owner_name') }}"
            required>
          @error('owner_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input
            id="email"
            type="email"
            class="form-control @error('email') is-invalid @enderror"
            name="email"
            placeholder="email@domain.com"
            value="{{ old('email') }}"
            required>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @else
            <div class="form-text">Kami akan kirim konfirmasi ke email ini.</div>
          @enderror
        </div>

        {{-- No HP --}}
        <div class="mb-3">
          <label for="phone" class="form-label">No HP</label>
          <input
            id="phone"
            type="tel"
            class="form-control @error('phone') is-invalid @enderror"
            name="phone"
            placeholder="08xxxxxxxxxx"
            value="{{ old('phone') }}"
            pattern="^0[0-9]{9,15}$"
            required>
          @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
          @else
            <div class="form-text">Format Indonesia, mulai dengan 0. Contoh: 081234567890.</div>
          @enderror
        </div>

        {{-- Password + strength meter --}}
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <input
              id="password"
              type="password"
              class="form-control @error('password') is-invalid @enderror"
              name="password"
              placeholder="Min. 8 karakter"
              minlength="8"
              required>
            <button class="btn btn-outline-secondary" type="button" id="togglePass">
              <i class="bi bi-eye"></i>
            </button>
            @error('password')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="progress mt-2" style="height:6px;">
            <div id="pwd-bar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
          </div>
          <div id="pwd-hint" class="small text-secondary mt-1">Gunakan kombinasi huruf, angka, dan simbol.</div>
        </div>

        {{-- Paket (radio cards) --}}
        {{-- di form --}}
        <div class="mb-3">
          <label class="form-label">Paket</label>
          <div class="row g-3">
            @forelse($plans as $p)
              <div class="col-sm-6">
                <label class="w-100">
                  <input class="btn-check" type="radio" name="plan"
                        value="{{ $p->code }}"
                        {{ old('plan', $selected) === $p->code ? 'checked' : '' }}>
                  <div class="btn btn-outline-primary w-100 text-start p-3">
                    <div class="d-flex justify-content-between">
                      <span class="fw-semibold">{{ $p->name }}</span>
                      @if(!is_null($p->price_monthly))
                        <span class="badge bg-primary-subtle text-primary border">
                          Rp {{ number_format($p->price_monthly,0,',','.') }}/bln
                        </span>
                      @endif
                    </div>
                    <div class="small text-secondary mt-1">
                      {{-- contoh highlight fitur utama --}}
                      @if(data_get($p->features_json,'printer_kitchen')) Printer dapur @else Tanpa printer dapur @endif
                    </div>
                  </div>
                </label>
              </div>
            @empty
              <div class="alert alert-warning">
                Paket belum tersedia. Hubungi admin.
              </div>
            @endforelse
          </div>
          @error('plan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>


        {{-- Terms --}}
        <div class="form-check mb-3">
          <input class="form-check-input @error('agree') is-invalid @enderror" type="checkbox" value="1" id="agree" name="agree" {{ old('agree') ? 'checked' : '' }} required>
          <label class="form-check-label" for="agree">
            Saya menyetujui <a href="{{ url('/terms') }}" target="_blank">Syarat Layanan</a> dan <a href="{{ url('/privacy') }}" target="_blank">Kebijakan Privasi</a>.
          </label>
          @error('agree')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        {{-- Submit --}}
        <div class="d-grid">
          <button class="btn btn-primary btn-lg" id="submitBtn" type="submit">
            <span class="spinner-border spinner-border-sm me-2 d-none" id="btnSpinner" role="status" aria-hidden="true"></span>
            Daftar & Buat Tenant
          </button>
        </div>

        <div class="text-center mt-3">
          <span class="small text-secondary">Sudah punya akun? <a href="{{ url('/login') }}">Masuk</a></span>
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
    bar.classList.toggle('bg-danger', score < 40);
    bar.classList.toggle('bg-warning', score >= 40 && score < 70);
    bar.classList.toggle('bg-success', score >= 70);
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
    spinner.classList.remove('d-none');
  });
})();
</script>
@endpush
@endsection
