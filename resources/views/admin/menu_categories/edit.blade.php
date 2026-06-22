@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h4 class="mb-0 text-lg font-semibold text-slate-900">Edit Kategori Menu</h4>
            <small class="text-slate-500">Perbarui data kategori menu</small>
        </div>

        <a href="{{ route('tenant.admin.menu-categories.index', $currentTenant->slug) }}"
           class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
            ← Kembali
        </a>
    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6">

            <form action="{{ route('tenant.admin.menu-categories.update', [$currentTenant->slug, $menu_category->id]) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    {{-- LEFT --}}
                    <div class="md:col-span-2">

                        {{-- NAME --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Nama Kategori
                            </label>

                            <input type="text"
                                   name="name"
                                   class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500"
                                   value="{{ $menu_category->name }}"
                                   required>

                            <small class="text-slate-500 text-xs mt-1 block">
                                Nama kategori yang akan tampil di menu pelanggan
                            </small>
                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="md:col-span-1">

                        {{-- SORT --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Urutan Tampilan
                            </label>

                            <input type="number"
                                   name="seq"
                                   class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-base focus:outline-none focus:ring-2 focus:ring-sky-500"
                                   value="{{ $menu_category->seq }}"
                                   min="0">

                            <small class="text-slate-500 text-xs mt-1 block">
                                Semakin kecil semakin atas
                            </small>
                        </div>

                        {{-- INFO --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <small class="text-slate-600">
                                ⚡ Perubahan akan langsung mempengaruhi QR Menu & POS kasir.
                            </small>
                        </div>

                    </div>

                </div>

                <hr class="border-slate-200 my-6">

                {{-- ACTION --}}
                <div class="flex justify-end gap-3">

                    <a href="{{ route('tenant.admin.menu-categories.index', $currentTenant->slug) }}"
                       class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                        Batal
                    </a>

                    <button class="px-5 py-2.5 rounded-xl bg-sky-500 text-white text-sm font-semibold hover:bg-sky-600 shadow-sm transition">
                        💾 Update Kategori
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
