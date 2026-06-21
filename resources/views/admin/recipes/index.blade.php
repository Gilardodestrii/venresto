@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Resep Menu</h3>
            <div class="text-sm text-gray-500">Kelola resep dan bahan untuk setiap menu</div>
        </div>

        <a href="{{ route('tenant.admin.recipes.create', $currentTenant->slug) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-blue-200">
            <i class="bi bi-plus-circle"></i>
            Tambah Resep
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Menu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Bahan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total HPP</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recipes as $recipe)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $recipe->menuItem->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $recipe->menuItem->sku ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $recipe->ingredients->count() }} bahan
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                Rp {{ number_format($recipe->total_hpp, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tenant.admin.recipes.edit', [$currentTenant->slug, $recipe->id]) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 border border-yellow-200 rounded-full transition-colors">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST"
                                          action="{{ route('tenant.admin.recipes.destroy', [$currentTenant->slug, $recipe->id]) }}"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Hapus resep ini?')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 border border-red-200 rounded-full transition-colors">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-book text-5xl text-gray-300 block mb-3"></i>
                                <p class="font-medium mb-1">Belum Ada Resep</p>
                                <p class="text-sm">Tambahkan resep untuk mulai menghitung HPP menu.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recipes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-white">
                {{ $recipes->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
