@extends('layouts.admin')

@section('page-title', 'QR Menu Per Meja')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-1 mb-6">
        <div class="bg-white rounded-lg shadow p-6 h-full">

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-plus-circle text-indigo-600"></i>
                </div>
                <div>
                    <h5 class="mb-0 font-semibold">Tambah Meja</h5>
                    <small class="text-gray-500">Generate QR otomatis</small>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.qr.store', [$tenant->slug, $outlet->id]) }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Outlet</label>

                    <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2" onchange="window.location=this.value">
                        @foreach($outlets as $item)
                            <option value="{{ route('admin.qr.index', [$tenant->slug, $item->id]) }}"
                                {{ $outletId == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Meja</label>

                    <input type="text"
                        name="table_code"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"
                        placeholder="Contoh: Meja 1 / VIP A"
                        required>
                </div>

                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md flex items-center justify-center gap-2">
                    <i class="bi bi-save"></i>
                    Simpan Meja
                </button>
            </form>

        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h5 class="mb-0 font-semibold">QR Meja Aktif</h5>
                    <small class="text-gray-500">
                        {{ $tables->count() }} meja tersedia di {{ $outlet->name }}
                    </small>
                </div>

                <span class="bg-indigo-600 text-white text-xs font-medium px-3 py-1 rounded-full">
                    {{ $outlet->name }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

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

                    <div class="relative">
                        <div class="border border-gray-200 rounded-xl p-4 text-center bg-white">

                            <div class="mb-3">
                                <h5 class="font-bold mb-0">
                                    {{ $table->table_code }}
                                </h5>

                                <small class="text-gray-500">
                                    Scan untuk order
                                </small>
                            </div>

                            <div class="my-4">
                                <img src="{{ $qrImageUrl }}"
                                    class="mx-auto mb-2 max-w-[150px]"
                                    alt="QR {{ $table->table_code }}">
                            </div>

                            <div class="mb-4">
                                <input type="text"
                                    class="w-full text-center text-sm border border-gray-300 rounded-md p-2 bg-gray-50"
                                    value="{{ $qrUrl }}"
                                    readonly>
                            </div>

                            <div class="grid grid-cols-1 gap-2">

                                <button type="button"
                                    class="border border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white py-2 px-3 rounded-md text-sm font-medium flex items-center justify-center gap-2 transition-colors"
                                    onclick="copyText(this)">
                                    <i class="bi bi-copy"></i>
                                    Copy Link
                                </button>

                                <a href="{{ $qrUrl }}"
                                    target="_blank"
                                    class="bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded-md text-sm font-medium flex items-center justify-center gap-2">
                                    <i class="bi bi-eye"></i>
                                    Preview
                                </a>

                                <a href="{{ route('admin.qr.download', [
                                        'tenant' => $tenant->slug,
                                        'outlet' => $outlet->id,
                                        'table' => $table->id,
                                    ]) }}"
                                    class="bg-gray-800 hover:bg-gray-900 text-white py-2 px-3 rounded-md text-sm font-medium flex items-center justify-center gap-2">
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

                                    <button class="w-full border border-red-500 text-red-500 hover:bg-red-500 hover:text-white py-2 px-3 rounded-md text-sm font-medium flex items-center justify-center gap-2 transition-colors">
                                        <i class="bi bi-trash"></i>
                                        Hapus
                                    </button>
                                </form>

                            </div>

                        </div>
                    </div>

                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <i class="bi bi-qr-code text-5xl text-gray-300"></i>

                            <h5 class="mt-4 font-semibold text-gray-700">
                                Belum Ada Meja
                            </h5>

                            <p class="text-gray-500 mt-1">
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
