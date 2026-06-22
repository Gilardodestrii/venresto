@extends('layouts.admin')
@section('page-title', 'Kategori Menu')
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
                        <i class="bi bi-tags"></i>
                    </div>

                    <div>

                        <h2 class="font-bold mb-1 text-lg text-slate-900">
                            Kategori Menu
                        </h2>

                        <p class="text-slate-500 mb-0 text-sm">
                            Kelola kategori menu untuk outlet
                        </p>

                    </div>

                </div>

            </div>

                <div class="flex flex-wrap gap-2">



                <a href="{{ route('tenant.admin.menu-categories.create', $currentTenant->slug) }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-sky-500 text-white text-sm font-semibold hover:bg-sky-600 shadow-sm transition">

                    <i class="bi bi-plus-circle me-1.5"></i>
                    Tambah Kategori

                </a>

            </div>

        </div>

    </div>
    {{-- HEADER --}}

    {{-- =========================
        TABLE CARD
    ========================== --}}
    <div class="premium-table-card">

        {{-- SEARCH --}}
        <div class="table-topbar">
            <div class="flex flex-wrap gap-2">
                <div class="search-box">

                    <i class="bi bi-search"></i>

                    <input type="text"
                        class="w-full h-10 pl-10 pr-4 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                        placeholder="Cari outlet...">

                </div>

                <button class="inline-flex items-center px-3 py-2 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition">

                        <i class="bi bi-funnel me-1.5"></i>
                        Filter

                </button>
            </div>


        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                {{-- TABLE HEAD --}}
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="px-4 py-3 font-semibold text-slate-700">Nama Kategori</th>
                        <th class="px-4 py-3 font-semibold text-slate-700 text-center">Urutan</th>
                        <th class="px-4 py-3 font-semibold text-slate-700 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- TABLE BODY --}}
                <tbody>

                    @forelse($categories as $cat)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">

                        {{-- NAME --}}
                        <td class="px-4 py-3 font-medium text-slate-900">
                            {{ $cat->name }}
                        </td>

                        {{-- ORDER --}}
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                {{ $cat->seq }}
                            </span>
                        </td>

                        {{-- ACTION --}}
                        <td class="px-4 py-3 text-center">

                            <div class="flex items-center justify-center gap-2">

                                <a href="{{ route('tenant.admin.menu-categories.edit', [$currentTenant->slug, $cat->id]) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium border border-amber-400 text-amber-600 hover:bg-amber-50 transition">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>

                                <form action="{{ route('tenant.admin.menu-categories.destroy', [$currentTenant->slug, $cat->id]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus kategori ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium border border-red-400 text-red-600 hover:bg-red-50 transition">
                                        <i class="bi bi-trash3 me-1"></i>
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    {{-- EMPTY STATE --}}
                    <tr>
                        <td colspan="3" class="px-4 py-12 text-center">

                            <div class="flex flex-col items-center gap-2">

                                <div class="text-2xl">📂</div>

                                <div class="font-semibold text-slate-700">
                                    Belum ada kategori menu
                                </div>

                                <div class="text-sm text-slate-500">
                                    Silakan tambah kategori terlebih dahulu
                                </div>

                            </div>

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
