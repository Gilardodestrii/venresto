@extends('layouts.admin')

@section('page-title', 'QR Menu Per Meja')

@section('content')

<div class="row">

    <div class="col-lg-4 mb-4">
        <div class="card card-premium p-4 h-100">

            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="stat-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <div>
                    <h5 class="mb-0">Tambah Meja</h5>
                    <small class="text-muted">Generate QR otomatis</small>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.qr.store', [$tenant->slug, $outlet->id]) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Outlet</label>

                    <select class="form-select" onchange="window.location=this.value">
                        @foreach($outlets as $item)
                            <option value="{{ route('admin.qr.index', [$tenant->slug, $item->id]) }}"
                                {{ $outletId == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Meja</label>

                    <input type="text"
                        name="table_code"
                        class="form-control"
                        placeholder="Contoh: Meja 1 / VIP A"
                        required>
                </div>

                <button class="btn btn-primary w-100">
                    <i class="bi bi-save me-1"></i>
                    Simpan Meja
                </button>
            </form>

        </div>
    </div>

    <div class="col-lg-8">
        <div class="card card-premium p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-0">QR Meja Aktif</h5>
                    <small class="text-muted">
                        {{ $tables->count() }} meja tersedia di {{ $outlet->name }}
                    </small>
                </div>

                <span class="badge bg-primary">
                    {{ $outlet->name }}
                </span>
            </div>

            <div class="row">

                @forelse($tables as $table)
                    @php
                        $qrUrl = route('qr.menu', [
                            'tenant' => $tenant->slug,
                            'outlet' => $outlet->id,
                            'table' => $table->id,
                        ]);

                        $qrImageUrl = route('admin.qr.generate', [
                            'tenant' => $tenant->slug,
                            'outlet' => $outlet->id,
                            'table' => $table->id,
                        ]);
                    @endphp

                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="border rounded-4 p-3 text-center h-100 position-relative bg-white">

                            <div class="mb-2">
                                <h5 class="fw-bold mb-0">
                                    {{ $table->table_code }}
                                </h5>

                                <small class="text-muted">
                                    Scan untuk order
                                </small>
                            </div>

                            <div class="my-3">
                                <img src="{{ $qrImageUrl }}"
                                    class="img-fluid mb-2"
                                    alt="QR {{ $table->table_code }}">
                            </div>

                            <div class="mb-3">
                                <input type="text"
                                    class="form-control form-control-sm text-center"
                                    value="{{ $qrUrl }}"
                                    readonly>
                            </div>

                            <div class="d-grid gap-2">

                                <button type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    onclick="copyText(this)">
                                    <i class="bi bi-copy"></i>
                                    Copy Link
                                </button>

                                <a href="{{ $qrUrl }}"
                                    target="_blank"
                                    class="btn btn-success btn-sm">
                                    <i class="bi bi-eye"></i>
                                    Preview
                                </a>

                                <a href="{{ route('admin.qr.download', [
                                        'tenant' => $tenant->slug,
                                        'outlet' => $outlet->id,
                                        'table' => $table->id,
                                    ]) }}"
                                    class="btn btn-dark btn-sm">
                                    <i class="bi bi-download"></i>
                                    Download QR
                                </a>

                                <form method="POST"
                                    action="{{ route('admin.qr.destroy', [
                                        'tenant' => $tenant->slug,
                                        'outlet' => $outlet->id,
                                        'table' => $table->id,
                                    ]) }}"
                                    onsubmit="return confirm('Hapus meja ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-outline-danger w-100">
                                        <i class="bi bi-trash"></i>
                                        Hapus
                                    </button>
                                </form>

                            </div>

                        </div>
                    </div>

                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-qr-code display-4 text-muted"></i>

                            <h5 class="mt-3">
                                Belum Ada Meja
                            </h5>

                            <p class="text-muted">
                                Tambahkan meja pertama untuk mulai QR Ordering di outlet ini.
                            </p>
                        </div>
                    </div>
                @endforelse

            </div>

        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function copyText(btn) {
    const card = btn.closest('.border');
    const input = card ? card.querySelector('input[readonly]') : null;

    if (!input) return;

    navigator.clipboard.writeText(input.value);

    const original = btn.innerHTML;

    btn.innerHTML = `
        <i class="bi bi-check-circle"></i>
        Copied!
    `;

    setTimeout(() => {
        btn.innerHTML = original;
    }, 1500);
}
</script>
@endpush