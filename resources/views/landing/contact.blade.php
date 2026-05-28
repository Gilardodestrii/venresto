@extends('layouts.marketing')

@section('title', 'Kontak - VenResto')
@section('meta_description', 'Hubungi tim VenResto untuk konsultasi POS restoran, QR menu, multi outlet, kitchen display, inventory, dan kebutuhan SaaS F&B Anda.')

@section('content')
<style>
:root{--primary:#0ea5e9;--bg:#f8fbff;--card:#ffffff;--border:#e5e7eb;--text:#0f172a;--muted:#64748b;}
body{background:var(--bg);} .page-hero{padding:110px 0 60px;background:linear-gradient(180deg,#f8fbff 0%,#fff 70%);} .soft-badge{display:inline-flex;align-items:center;gap:10px;padding:10px 18px;border-radius:999px;background:rgba(255,255,255,.82);border:1px solid rgba(226,232,240,.9);backdrop-filter:blur(16px);color:var(--primary);font-weight:800;box-shadow:0 10px 30px rgba(15,23,42,.05);} .page-title{font-size:56px;font-weight:900;letter-spacing:-.04em;line-height:1.08;color:var(--text);margin-top:24px;} .page-subtitle{max-width:760px;font-size:18px;line-height:1.8;color:var(--muted);margin-top:22px;} .premium-card{background:rgba(255,255,255,.9);border:1px solid rgba(226,232,240,.9);backdrop-filter:blur(18px);border-radius:28px;box-shadow:0 18px 45px rgba(15,23,42,.07);} .contact-icon{width:54px;height:54px;border-radius:18px;background:rgba(14,165,233,.12);display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:24px;flex:0 0 auto;} .form-control,.form-select{border-radius:16px;border-color:var(--border);padding:13px 15px;} .form-control:focus,.form-select:focus{border-color:var(--primary);box-shadow:0 0 0 .25rem rgba(14,165,233,.12);} .btn-send{border-radius:16px;padding:13px 18px;font-weight:800;} .info-row{display:flex;gap:16px;padding:18px;border-radius:22px;background:#fff;border:1px solid var(--border);height:100%;} .mini-muted{color:var(--muted);line-height:1.7;} @media(max-width:768px){.page-hero{padding:80px 0 40px}.page-title{font-size:38px}.page-subtitle{font-size:16px}}
</style>

<section class="page-hero">
    <div class="container">
        <div class="soft-badge"><i class="bi bi-chat-dots"></i> Hubungi VenResto</div>
        <h1 class="page-title">Butuh bantuan setup<br>POS restoran modern?</h1>
        <p class="page-subtitle">Kirim pertanyaan Anda tentang POS cashier, QR menu, kitchen display, inventory, multi outlet, printer thermal, atau kebutuhan implementasi VenResto untuk bisnis F&B Anda.</p>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="premium-card p-4 p-lg-5">
                    @if(session('success'))
                        <div class="alert alert-success rounded-4 border-0 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('landing.contact.submit') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Anda" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Topik</label>
                                <select name="topic" class="form-select @error('topic') is-invalid @enderror" required>
                                    <option value="">Pilih topik</option>
                                    @foreach(['Demo Produk','Harga & Paket','Implementasi Restoran','Integrasi Printer','QRIS & Pembayaran','Lainnya'] as $topic)
                                        <option value="{{ $topic }}" @selected(old('topic') === $topic)>{{ $topic }}</option>
                                    @endforeach
                                </select>
                                @error('topic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pesan</label>
                                <textarea name="message" rows="6" class="form-control @error('message') is-invalid @enderror" placeholder="Ceritakan kebutuhan restoran Anda..." required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 d-grid d-md-flex align-items-center gap-3">
                                <button class="btn btn-primary btn-send"><i class="bi bi-send me-2"></i>Kirim Pesan</button>
                                <span class="small text-secondary">Tim VenResto akan menindaklanjuti pesan Anda.</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="d-grid gap-3">
                    <div class="info-row"><div class="contact-icon"><i class="bi bi-envelope"></i></div><div><h5 class="fw-bold mb-1">Email</h5><div class="mini-muted">support@venresto.id<br>Untuk pertanyaan produk dan kerja sama.</div></div></div>
                    <div class="info-row"><div class="contact-icon"><i class="bi bi-clock-history"></i></div><div><h5 class="fw-bold mb-1">Jam Operasional</h5><div class="mini-muted">Senin - Jumat, 09.00 - 18.00 WIB<br>Dukungan prioritas untuk pelanggan aktif.</div></div></div>
                    <div class="info-row"><div class="contact-icon"><i class="bi bi-shop-window"></i></div><div><h5 class="fw-bold mb-1">Untuk Restoran</h5><div class="mini-muted">Cocok untuk restoran, kafe, warung, food court, cloud kitchen, dan bisnis multi outlet.</div></div></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
