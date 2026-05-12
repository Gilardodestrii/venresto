@extends('layouts.app')

@section('title','Masuk — VenResto')
@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="text-center mb-4">
        <h1 class="h3 fw-bold">Masuk ke VenResto</h1>
        @isset($currentTenant)
          <div class="small text-secondary">Tenant: <code>{{ $currentTenant->slug }}</code></div>
        @endisset
      </div>

      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ tenant_url('login') }}" novalidate>
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" name="email"
                 class="form-control @error('email') is-invalid @enderror"
                 value="{{ old('email') }}" required autofocus>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between">
            <label for="password" class="form-label">Password</label>
            <a href="{{ tenant_url('forgot-password') }}" class="small">Lupa password?</a>
          </div>
          <div class="input-group">
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="••••••••" required>
            <button type="button" class="btn btn-outline-secondary" id="togglePass"><i class="bi bi-eye"></i></button>
          </div>
          @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
          <label class="form-check-label" for="remember">Ingat saya</label>
        </div>

        <div class="d-grid">
          <button class="btn btn-primary" type="submit">Masuk</button>
        </div>
      </form>

      <div class="text-center mt-3 small">
        Belum punya akun? <a href="{{ tenant_url('signup') }}">Daftar</a>
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
    });
  }
});
</script>
@endpush
@endsection
