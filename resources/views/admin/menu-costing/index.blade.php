@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4 py-6">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold mb-1 text-xl">HPP & Menu Costing</h3>
            <div class="text-gray-500">Analisa food cost dan profitabilitas menu</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="bg-white border-0 p-6">
            <h5 class="font-bold mb-1 text-lg">Menu Cost Analysis</h5>
            <div class="text-gray-500 text-sm">Perhitungan otomatis berdasarkan recipe dan harga bahan</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full align-middle">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Menu</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Harga Jual</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">HPP</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Margin</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Food Cost</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Recipe</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($costings as $costing)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-4">
                            <div class="font-bold">{{ $costing->menu->name }}</div>
                            <small class="text-gray-500">{{ $costing->recipes->count() }} bahan</small>
                        </td>

                        <td class="px-4 py-4">
                            <span class="font-semibold text-blue-600">
                                Rp {{ number_format($costing->price, 0, ',', '.') }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            <span class="font-bold text-red-600">
                                Rp {{ number_format($costing->hpp, 0, ',', '.') }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            @if($costing->margin > 0)
                                <span class="font-bold text-green-600">
                                    Rp {{ number_format($costing->margin, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="font-bold text-red-600">
                                    Rp {{ number_format($costing->margin, 0, ',', '.') }}
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            @if($costing->food_cost_percent <= 35)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                    {{ number_format($costing->food_cost_percent, 1) }}%
                                </span>
                            @elseif($costing->food_cost_percent <= 50)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                                    {{ number_format($costing->food_cost_percent, 1) }}%
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                    {{ number_format($costing->food_cost_percent, 1) }}%
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-1">
                                @foreach($costing->recipes as $recipe)
                                    <small class="text-gray-600">
                                        {{ $recipe->material?->name }}
                                        ({{ $recipe->qty }})
                                    </small>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-500">
                            <i class="bi bi-calculator text-4xl d-block mb-2"></i>
                            Belum ada data menu costing.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
