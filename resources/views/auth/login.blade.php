@extends('layouts.app')

@section('title','Masuk — VenResto')
@section('content')
<style>
:root{
  --primary:#0ea5e9;
  --bg:#f8fbff;
  --card:#ffffff;
  --border:#e5e7eb;
  --text:#0f172a;
  --muted:#64748b;
}

body{
  background:
    radial-gradient(circle at top left, rgba(14,165,233,.14), transparent 34%),
    linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}

.auth-shell{
  min-height:calc(100vh - 40px);
  display:flex;
  align-items:center;
  padding:56px 0;
}

.auth-card{
  background:rgba(255,255,255,.88);
  border:1px solid rgba(226,232,240,.9);
  backdrop-filter:blur(18px);
  border-radius:28px;
  box-shadow:0 24px 70px rgba(15,23,42,.09);
  overflow:hidden;
}

.auth-brand{
  width:54px;
  height:54px;
  border-radius:18px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  color:white;
  background:linear-gradient(135deg,#38bdf8,#0ea5e9);
  box-shadow:0 14px 34px rgba(14,165,233,.28);
}

.auth-title{
  color:var(--text);
  font-weight:900;
  letter-spacing:-.03em;
}

.auth-subtitle{
  color:var(--muted);
  line-height:1.7;
}

.form-control{
  min-height:50px;
  border-radius:16px;
  border-color:var(--border);
}

.form-control:focus{
  border-color:var(--primary);
  box-shadow:0 0 0 .25rem rgba(14,165,233,.12);
}

.btn-auth{
  min-height:50px;
  border-radius:16px;
  font-weight:800;
}

.input-group .form-control{
  border-top-left-radius:16px;
  border-bottom-left-radius:16px;
}

.input-group .btn{
  border-top-right-radius:16px;
  border-bottom-right-radius:16px;
}

.auth-meta{
  border-radius:18px;
  background:#f8fafc;
  border:1px solid var(--border);
}
</style>

@php
  $loginAction = isset($currentTenant) && $currentTenant
      ? url($currentTenant->slug . '/login')
      : url('/login');
@endphp

<div class="auth-shell">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-5">
        <div class="auth-card p-4 p-lg-5">
          <div class="text-center mb-4">
            <div class="auth-brand mb-3">
              <i class="bi bi-shop fs-3"></i>
            </div>
            <h1 class="h3 auth-title mb-2">Masuk ke VenResto</h1>
            <p class="auth-subtitle mb-0">
              Kelola POS, QR menu, kitchen display, inventory, dan laporan restoran dari satu dashboard.
            </p>

            @isset($currentTenant)
              <div class="auth-meta small text-secondary mt-3 px-3 py-2">
                Tenant aktif: <code>{{ $currentTenant->slug }}</code>
              </div>
            @endisset
          </div>

          @if(session('status'))
            <div class="alert alert-success rounded-4 border-0">
              <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
            </div>
          @endif

          <form method="POST" action="{{ $loginAction }}" novalidate>
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Email</label>
              <input id="email" type="email" name="email"
                     class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email') }}"
                     placeholder="nama@email.com"
                     autocomplete="email"
                     required autofocus>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label fw-semibold">Password</label>
                <span class="small text-secondary">Gunakan akun tenant Anda</span>
              </div>
              <div class="input-group">
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••"
                       autocomplete="current-password"
                       required>
                <button type="button" class="btn btn-outline-secondary" id="togglePass" aria-label="Tampilkan password">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
              @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
              </div>
            </div>

            <div class="d-grid">
              <button class="btn btn-primary btn-auth" type="submit">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
              </button>
            </div>
          </form>

          <div class="text-center mt-4 small text-secondary">
            Belum punya akun?
            <a href="{{ url('/signup') }}" class="fw-semibold text-decoration-none">Mulai Trial</a>
          </div>

          <div class="text-center mt-3">
            <a href="{{ url('/') }}" class="small text-secondary text-decoration-none">
              <i class="bi bi-arrow-left me-1"></i>Kembali ke landing page
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const pass = document.getElementById('password');
  const btn  = document.getElementById('togglePass');
  if (btn && pass) {
    btn.addEventListener('click', () => {
      const show = pass.type === 'password';
      pass.type = show ? 'text' : 'password';
      btn.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
      btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
    });
  }
});
</script>
@endpush
@endsection
