@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Inventory Bahan</h3>
            <div class="text-muted">Kelola bahan baku, stok minimum, dan stok berjalan</div>
        </div>

        <a href="{{ route('tenant.admin.materials.create', $currentTenant->slug) }}"
           class="btn btn-primary rounded-4 px-4">
            <i class="bi bi-plus-lg me-1"></i> Tambah Bahan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Bahan</th>
                        <th>Unit</th>
                        <th>Stok</th>
                        <th>Minimum</th>
                        <th>Status</th>
                        <th class="text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($materials as $material)
                    <tr>
                        <td class="px-4 fw-semibold">{{ $material->name }}</td>
                        <td>{{ $material->unit }}</td>
                        <td class="fw-bold {{ $material->is_low_stock ? 'text-danger' : 'text-success' }}">
                            {{ number_format($material->stock, 2) }}
                        </td>
                        <td>{{ number_format($material->min_stock, 2) }}</td>
                        <td>
                            @if($material->is_low_stock)
                                <span class="badge bg-danger-subtle text-danger rounded-pill">
                                    Low Stock
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success rounded-pill">
                                    Aman
                                </span>
                            @endif
                        </td>
                        <td class="text-end px-4">
                            <button class="btn btn-sm btn-outline-success rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#stockIn{{ $material->id }}">
                                Masuk
                            </button>

                            <button class="btn btn-sm btn-outline-danger rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#stockOut{{ $material->id }}">
                                Keluar
                            </button>

                            <button class="btn btn-sm btn-outline-dark rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#adjustment{{ $material->id }}">
                                Adjustment
                            </button>

                            <a href="{{ route('tenant.admin.materials.edit', [$currentTenant->slug, $material->id]) }}"
                               class="btn btn-sm btn-outline-warning rounded-pill">
                                Edit
                            </a>
                        </td>
                    </tr>

                    @include('admin.materials.partials.stock-modal', [
                        'material' => $material,
                        'type' => 'in'
                    ])

                    @include('admin.materials.partials.stock-modal', [
                        'material' => $material,
                        'type' => 'out'
                    ])

                    @include('admin.materials.partials.stock-modal', [
                        'material' => $material,
                        'type' => 'adjustment'
                    ])
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            Belum ada bahan baku.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $materials->links() }}
    </div>

</div>

@endsection