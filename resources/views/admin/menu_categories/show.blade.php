@extends('layouts.admin')
@section('page-title', 'Detail Kategori Menu')
@section('content')

<div class="container-fluid px-0">

    {{-- =========================
        HEADER
    ========================== --}}
    <div class="outlet-header-card mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">

            <div>

                <div class="flex items-center gap-3 mb-2">

                    <div class="header-icon">
                        <i class="bi bi-tag"></i>
                    </div>

                    <div>

                        <h2 class="font-bold mb-1 text-lg text-slate-900">
                            Detail Kategori Menu
                        </h2>

                        <p class="text-slate-500 mb-0 text-sm">
                            Lihat detail kategori menu untuk outlet
                        </p>

                    </div>

                </div>

            </div>

            <div class="flex flex-wrap gap-2">

                <a href="{{ route('tenant.admin.menu-categories.index', $currentTenant->slug) }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    <i class="bi bi-arrow-left me-1.5"></i>
                    Kembali
                </a>

                <a href="{{ route('tenant.admin.menu-categories.edit', [$currentTenant->slug, $menu_category->id]) }}"
                   class="inline-flex items-center px-4 py-2.5 rounded-xl bg-sky-500 text-white text-sm font-semibold hover:bg-sky-600 shadow-sm transition">
                    <i class="bi bi-pencil me-1.5"></i>
                    Edit Kategori
                </a>

            </div>

        </div>

    </div>
    {{-- HEADER --}}

    {{-- =========================
        DETAIL CARD
    ========================== --}}
    <div class="premium-table-card">

        <div class="bg-white rounded-xl shadow-sm">

            <div class="p-6">

                {{-- CATEGORY NAME --}}
                <div class="mb-6">
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 mb-1">
                        Nama Kategori
                    </label>
                    <div class="text-lg font-semibold text-slate-900">
                        {{ $menu_category->name }}
                    </div>
                </div>

                <hr class="border-slate-200 my-5">

                {{-- DETAILS GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- SEQUENCE --}}
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">
                            Urutan Tampil
                        </label>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-sky-100 text-sky-800">
                                {{ $menu_category->seq }}
                            </span>
                            <small class="text-slate-500 d-block mt-1 text-xs">
                                Semakin kecil semakin atas
                            </small>
                        </div>
                    </div>

                    {{-- CREATED AT --}}
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">
                            Dibuat Pada
                        </label>
                        <div class="text-slate-900 text-sm">
                            {{ $menu_category->created_at->format('d M Y, H:i') }} WIB
                        </div>
                    </div>

                    {{-- UPDATED AT --}}
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">
                            Terakhir Diperbarui
                        </label>
                        <div class="text-slate-900 text-sm">
                            {{ $menu_category->updated_at->format('d M Y, H:i') }} WIB
                        </div>
                    </div>

                </div>

                <hr class="border-slate-200 my-5">

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-wrap gap-2 justify-end">

                    <form action="{{ route('tenant.admin.menu-categories.destroy', [$currentTenant->slug, $menu_category->id]) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus kategori ini?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-xl border border-red-400 text-red-600 text-sm font-medium hover:bg-red-50 transition">
                            <i class="bi bi-trash3 me-1.5"></i>
                            Hapus Kategori
                        </button>

                    </form>

                    <a href="{{ route('tenant.admin.menu-categories.edit', [$currentTenant->slug, $menu_category->id]) }}"
                       class="inline-flex items-center px-4 py-2.5 rounded-xl bg-sky-500 text-white text-sm font-semibold hover:bg-sky-600 shadow-sm transition">
                        <i class="bi bi-pencil me-1.5"></i>
                        Edit Kategori
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
