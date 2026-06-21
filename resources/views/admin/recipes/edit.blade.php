@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('tenant.admin.recipes.index', $currentTenant->slug) }}"
           class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Edit Resep</h3>
            <div class="text-sm text-gray-500">Perbarui komposisi bahan resep menu</div>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-sm">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('tenant.admin.recipes.update', [$currentTenant->slug, $recipe->id]) }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Menu</label>
                <select name="menu_item_id"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        required>
                    @foreach($menuItems as $item)
                        <option value="{{ $item->id }}" {{ old('menu_item_id', $recipe->menu_item_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="recipeRows" class="space-y-3 mb-4">
                @foreach($recipe->ingredients as $i => $ing)
                <div class="grid grid-cols-12 gap-3 items-end p-4 bg-gray-50 border border-gray-200 rounded-xl">
                    <div class="col-span-5">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Bahan</label>
                        <select name="ingredients[{{ $i }}][material_id]" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                            <option value="">Pilih</option>
                            @foreach($materials as $m)
                                <option value="{{ $m->id }}" {{ $ing->material_id == $m->id ? 'selected' : '' }}>{{ $m->name }} ({{ $m->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Qty</label>
                        <input type="number" step="0.001" name="ingredients[{{ $i }}][qty]" value="{{ $ing->qty }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Notes</label>
                        <input type="text" name="ingredients[{{ $i }}][notes]" value="{{ $ing->notes ?? '' }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div class="col-span-1">
                        <button type="button" onclick="this.closest('.grid').remove()" class="w-full px-3 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('tenant.admin.recipes.index', $currentTenant->slug) }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors shadow-lg shadow-blue-200">
                    <i class="bi bi-check-circle mr-1"></i>
                    Update Resep
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
