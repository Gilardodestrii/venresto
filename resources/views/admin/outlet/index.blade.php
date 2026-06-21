@extends('layouts.admin')

@section('page-title', 'Outlet Management')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================
        HEADER
    ========================== --}}
    <div class="bg-white rounded-3xl p-5 mb-6 border border-gray-100 shadow-sm">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <div class="flex items-center gap-4 mb-3">

                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-100 to-blue-100 flex items-center justify-center text-2xl text-sky-600">
                        <i class="bi bi-shop"></i>
                    </div>

                    <div>

                        <h2 class="font-bold text-xl mb-1">
                            Outlet Management
                        </h2>

                        <p class="text-gray-500 mb-0">
                            Kelola seluruh cabang restoran tenant VenResto
                        </p>

                    </div>

                </div>

            </div>

            <div class="flex flex-wrap gap-3">

                <button class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors inline-flex items-center gap-2">

                    <i class="bi bi-funnel"></i>
                    Filter

                </button>

                <button class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors inline-flex items-center gap-2"
                        onclick="openOutletModal()">

                    <i class="bi bi-plus-circle"></i>
                    Tambah Outlet
                </button>

            </div>

        </div>

    </div>

    {{-- =========================
        STATS
    ========================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <div class="text-sm text-gray-500 mb-1">
                            Total Outlet
                        </div>

                        <div class="text-3xl font-bold text-gray-900">
                            {{ $outlets->count() }}
                        </div>

                    </div>

                    <div class="w-12 h-12 rounded-xl bg-sky-100 flex items-center justify-center text-xl text-sky-600">
                        <i class="bi bi-shop"></i>
                    </div>

                </div>

            </div>

        </div>

        <div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <div class="text-sm text-gray-500 mb-1">
                            Outlet Active
                        </div>

                        <div class="text-3xl font-bold text-gray-900">
                            {{ $outlets->where('is_active', true)->count() }}
                        </div>

                    </div>

                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-xl text-green-600">
                        <i class="bi bi-check-circle"></i>
                    </div>

                </div>

            </div>

        </div>

        <div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <div class="text-sm text-gray-500 mb-1">
                            Non Active
                        </div>

                        <div class="text-3xl font-bold text-gray-900">
                            {{ $outlets->where('is_active', false)->count() }}
                        </div>

                    </div>

                    <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center text-xl text-red-600">
                        <i class="bi bi-x-circle"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================
        TABLE CARD
    ========================== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- SEARCH --}}
        <div class="p-4 border-b border-gray-100">

            <div class="relative">

                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                <input type="text"
                       class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Cari outlet...">

            </div>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Outlet</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($outlets as $outlet)

                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- OUTLET --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-4">

                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-sky-100 to-blue-100 flex items-center justify-center text-lg text-sky-600 flex-shrink-0">

                                        <i class="bi bi-shop"></i>

                                    </div>

                                    <div>

                                        <div class="font-semibold text-gray-900">
                                            {{ $outlet->name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ $outlet->address ?? 'Alamat belum tersedia' }}
                                        </div>

                                    </div>

                                </div>

                            </td>

                            {{-- CONTACT --}}
                            <td class="px-6 py-4">

                                <div class="text-sm">

                                    <div class="mb-1 text-gray-700">
                                        <i class="bi bi-telephone mr-1 text-gray-400"></i>
                                        {{ $outlet->phone ?? '-' }}
                                    </div>

                                    <div class="text-gray-500">
                                        <i class="bi bi-envelope mr-1 text-gray-400"></i>
                                        {{ $outlet->email ?? '-' }}
                                    </div>

                                </div>

                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4">

                                @if($outlet->is_active)

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Active
                                    </span>

                                @else

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Non Active
                                    </span>

                                @endif

                            </td>

                            {{-- ACTION --}}
                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                {{-- VIEW --}}
                                <a href="{{ route('tenant.admin.outlets.show', [$currentTenant->slug, $outlet->id]) }}"
                                class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-colors">

                                    <i class="bi bi-eye"></i>

                                </a>

                                {{-- EDIT --}}
                                <a href="{{ route('tenant.admin.outlets.edit', [$currentTenant->slug, $outlet->id]) }}"
                                class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-colors">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                    <form method="POST"
                                        action="{{ route('tenant.admin.outlets.destroy', [$currentTenant->slug, $outlet->id]) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button class="w-9 h-9 rounded-xl bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-600 transition-colors"
                                                onclick="return confirm('Hapus outlet ini?')">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4">

                                <div class="py-16 text-center">

                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-3xl text-gray-400 mx-auto mb-4">

                                        <i class="bi bi-shop"></i>

                                    </div>

                                    <h5 class="font-bold text-gray-900 mb-2">
                                        Belum Ada Outlet
                                    </h5>

                                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                                        Tambahkan outlet pertama untuk memulai sistem POS restoran.
                                    </p>

                                    <a href="#"
                                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">

                                        <i class="bi bi-plus-circle"></i>
                                        Tambah Outlet

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ======================================
    MODAL CREATE OUTLET
====================================== --}}
<div id="modalCreateOutlet"
     class="fixed inset-0 z-50 hidden">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"
         onclick="closeOutletModal()"></div>

    {{-- Panel --}}
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md pointer-events-auto">

            <form method="POST"
                  action="{{ route('tenant.admin.outlets.store', $currentTenant->slug) }}">

                @csrf

                <div class="border-0 pb-0 px-6 pt-6">

                    <div>

                        <h5 class="font-bold mb-1">
                            Tambah Outlet
                        </h5>

                        <p class="text-gray-500 text-sm mb-0">
                            Tambahkan cabang restoran baru
                        </p>

                    </div>

                    <button type="button"
                            class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
                            onclick="closeOutletModal()">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                </div>

                <div class="px-6 py-5">

                    {{-- NAME --}}
                    <div class="mb-5">

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Outlet
                        </label>

                        <input type="text"
                               name="name"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') is-invalid @enderror"
                               placeholder="Contoh: Outlet Bogor">

                        @error('name')

                            <div class="invalid-feedback text-red-600 text-sm mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    {{-- ADDRESS --}}
                    <div class="mb-5">

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat
                        </label>

                        <textarea name="address"
                                  rows="4"
                                  class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('address') is-invalid @enderror"
                                  placeholder="Masukkan alamat outlet"></textarea>

                        @error('address')

                            <div class="invalid-feedback text-red-600 text-sm mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

                <div class="border-0 pt-0 px-6 pb-6 flex justify-end gap-3">

                    <button type="button"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors"
                            onclick="closeOutletModal()">

                        Cancel

                    </button>

                    <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors inline-flex items-center gap-2">

                        <i class="bi bi-check-circle"></i>
                        Simpan Outlet

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
    function openOutletModal() {
        document.getElementById('modalCreateOutlet').classList.remove('hidden');
    }

    function closeOutletModal() {
        document.getElementById('modalCreateOutlet').classList.add('hidden');
    }
</script>

@endsection
