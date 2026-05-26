@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Recipes</h3>
            <div class="text-muted">Atur komposisi bahan baku untuk setiap menu</div>
        </div>

        <a href="{{ route('tenant.admin.recipes.create', $currentTenant->slug) }}"
           class="btn btn-primary rounded-4 px-4">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Recipe
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-1">Daftar Recipe Menu</h5>
            <div class="text-muted small">
                Setiap menu dapat memiliki beberapa bahan baku
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Menu</th>
                        <th>Bahan</th>
                        <th>Qty Pemakaian</th>
                        <th>Unit</th>
                        <th class="text-end px-4">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($recipes as $recipe)
                    <tr>
                        <td class="px-4">
                            <div class="fw-semibold">
                                {{ $recipe->menuItem?->name ?? '-' }}
                            </div>
                            <small class="text-muted">
                                ID Menu: {{ $recipe->item_id }}
                            </small>
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $recipe->material?->name ?? '-' }}
                            </div>
                            <small class="text-muted">
                                Stok: {{ number_format($recipe->material?->stock ?? 0, 2) }}
                            </small>
                        </td>

                        <td>
                            <span class="fw-bold">
                                {{ number_format($recipe->qty, 3) }}
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                {{ $recipe->material?->unit ?? '-' }}
                            </span>
                        </td>

                        <td class="text-end px-4">
                            <form method="POST"
                                  action="{{ route('tenant.admin.recipes.destroy', [$currentTenant->slug, $recipe->id]) }}"
                                  onsubmit="return confirm('Hapus recipe ini?')"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger rounded-pill">
                                    <i class="bi bi-trash me-1"></i>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="mb-2">
                                <i class="bi bi-journal-check fs-1 text-muted"></i>
                            </div>
                            <div class="fw-semibold">Belum ada recipe</div>
                            <div class="text-muted small">
                                Tambahkan recipe agar stok bahan otomatis terpotong saat order dibayar.
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($recipes->hasPages())
            <div class="card-footer bg-white border-0 px-4 py-3">
                {{ $recipes->links() }}
            </div>
        @endif
    </div>

</div>

@endsection