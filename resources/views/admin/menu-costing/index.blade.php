@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">HPP & Menu Costing</h3>
            <div class="text-muted">Analisa food cost dan profitabilitas menu</div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-1">Menu Cost Analysis</h5>
            <div class="text-muted small">Perhitungan otomatis berdasarkan recipe dan harga bahan</div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Menu</th>
                        <th>Harga Jual</th>
                        <th>HPP</th>
                        <th>Margin</th>
                        <th>Food Cost</th>
                        <th>Recipe</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($costings as $costing)
                    <tr>
                        <td class="px-4">
                            <div class="fw-bold">{{ $costing->menu->name }}</div>
                            <small class="text-muted">{{ $costing->recipes->count() }} bahan</small>
                        </td>

                        <td>
                            <span class="fw-semibold text-primary">
                                Rp {{ number_format($costing->price, 0, ',', '.') }}
                            </span>
                        </td>

                        <td>
                            <span class="fw-bold text-danger">
                                Rp {{ number_format($costing->hpp, 0, ',', '.') }}
                            </span>
                        </td>

                        <td>
                            @if($costing->margin > 0)
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($costing->margin, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="fw-bold text-danger">
                                    Rp {{ number_format($costing->margin, 0, ',', '.') }}
                                </span>
                            @endif
                        </td>

                        <td>
                            @if($costing->food_cost_percent <= 35)
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                    {{ number_format($costing->food_cost_percent, 1) }}%
                                </span>
                            @elseif($costing->food_cost_percent <= 50)
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                    {{ number_format($costing->food_cost_percent, 1) }}%
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                    {{ number_format($costing->food_cost_percent, 1) }}%
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex flex-column gap-1">
                                @foreach($costing->recipes as $recipe)
                                    <small>
                                        {{ $recipe->material?->name }}
                                        ({{ $recipe->qty }})
                                    </small>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-calculator fs-1 d-block mb-2"></i>
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
