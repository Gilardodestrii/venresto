@extends('layouts.app')

@section('title','Masuk — VenResto')
@section('layout-body')

@php
  $loginAction = isset($currentTenant) && $currentTenant
      ? url($currentTenant->slug . '/login')
      : url('/login');
@endphp

<div class="min-h-screen flex items-center justify-center px-4 py-14" style="background: radial-gradient(circle at top left, rgba(14,165,233,.14), transparent 34%), linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);">
  <div class="w-full max-w-md">

    <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden">

      <div class="p-6 lg:p-8">

        <div class="text-center mb-5">
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl text-white mb-3" style="background: linear-gradient(135deg, #38bdf8, #0ea5e9); box-shadow: 0 14px 34px rgba(14,165,233,.28);">
            <i class="bi bi-shop text-2xl"></i>
          </div>
          <h1 class="text-2xl font-black text-slate-900 mb-2 tracking-tight">Masuk ke VenResto</h1>
          <p class="text-slate-500 leading-relaxed text-sm">
            Kelola POS, QR menu, kitchen display, inventory, dan laporan restoran dari satu dashboard.
          </p>

          @isset($currentTenant)
            <div class="mt-3 px-3 py-2 rounded-xl bg-slate-100 border border-slate-200 text-xs text-slate-600">
              Tenant aktif: <code class="font-mono">{{ $currentTenant->slug }}</code>
            </div>
          @endisset
        </div>

        @if(session('status'))
          <div class="mb-4 px-4 py-3 rounded-xl bg-green-100 text-green-800 text-sm flex items-center gap-2">
            <i class="bi bi-check-circle"></i>{{ session('status') }}
          </div>
        @endif

        {{-- Login dengan Google --}}
        <a href="{{ route('central.login.google') }}"
           class="w-full h-12 rounded-2xl border border-slate-300 bg-white hover:bg-slate-50 flex items-center justify-center gap-3 text-sm font-semibold text-slate-700 transition shadow-sm mb-4 no-underline">
          <svg class="w-5 h-5" viewBox="0 0 48 48">
            <path fill="#4285F4" d="M44.5 20H24v8.5h11.8C34.7 33.9 29.9 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6-6C34.5 5.1 29.5 3 24 3 12.4 3 3 12.4 3 24s9.4 21 21 21c10.9 0 20.2-7.9 20.2-21 0-1.4-.1-2.7-.3-4z"/>
            <path fill="#34A853" d="M6.3 14.7l7 5.1C15 16.1 19.1 13 24 13c3.1 0 5.9 1.1 8.1 2.9l6-6C34.5 5.1 29.5 3 24 3c-7.5 0-14 4.2-17.7 10.7z"/>
            <path fill="#FBBC05" d="M24 45c5.8 0 10.7-1.9 14.3-5.2l-6.6-5.4C29.9 36 27.1 37 24 37c-5.8 0-10.7-3.9-12.5-9.3l-7 5.4C8.1 40.7 15.5 45 24 45z"/>
            <path fill="#EA4335" d="M44.5 20H24v8.5h11.8c-.8 2.3-2.3 4.3-4.3 5.7l6.6 5.4C42.4 36.1 45 30.5 45 24c0-1.4-.1-2.7-.3-4z"/>
          </svg>
          Masuk dengan Google
        </a>

        <div class="flex items-center gap-3 mb-4">
          <div class="flex-1 h-px bg-slate-200"></div>
          <span class="text-xs text-slate-400 font-medium">atau</span>
          <div class="flex-1 h-px bg-slate-200"></div>
        </div>

        <form method="POST" action="{{ $loginAction }}" novalidate>
          @csrf

          <div class="mb-4">
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
            <input id="email" type="email" name="email"
                   class="w-full h-12 px-4 rounded-2xl border @error('email') border-red-400 ring-2 ring-red-100 @else border-slate-200 @enderror bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                   value="{{ old('email') }}"
                   placeholder="nama@email.com"
                   autocomplete="email"
                   required autofocus>
            @error('email')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
          </div>

          <div class="mb-4">
            <div class="flex justify-between items-center mb-1.5">
              <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
              <span class="text-xs text-slate-500">Gunakan akun tenant Anda</span>
            </div>
            <div class="flex">
              <input id="password" type="password" name="password"
                     class="flex-1 h-12 px-4 rounded-l-2xl border @error('password') border-red-400 ring-2 ring-red-100 @else border-slate-200 @enderror bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                     placeholder="••••••••"
                     autocomplete="current-password"
                     required>
              <button type="button" class="px-4 rounded-r-2xl border border-l-0 border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition" id="togglePass" aria-label="Tampilkan password">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            @error('password')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
          </div>

          <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-2">
              <input class="w-4 h-4 rounded border-slate-300 text-sky-500 focus:ring-sky-500" type="checkbox" value="1" id="remember" name="remember">
              <label class="text-sm text-slate-600" for="remember">Ingat saya</label>
            </div>
          </div>

          <button class="w-full h-12 rounded-2xl bg-sky-500 text-white text-sm font-extrabold hover:bg-sky-600 shadow-sm transition flex items-center justify-center gap-2" type="submit">
            <i class="bi bi-box-arrow-in-right"></i>Masuk
          </button>
        </form>

        <div class="text-center mt-4">
          <span class="text-sm text-slate-500">Belum punya akun?</span>
          <a href="{{ url('/signup') }}" class="text-sm font-semibold text-sky-600 hover:text-sky-700 no-underline">Mulai Trial</a>
        </div>

        <div class="text-center mt-3">
          <a href="{{ url('/') }}" class="text-sm text-slate-500 hover:text-slate-700 no-underline flex items-center justify-center gap-1">
            <i class="bi bi-arrow-left"></i>Kembali ke landing page
          </a>
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
