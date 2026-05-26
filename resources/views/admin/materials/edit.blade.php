@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <h3 class="fw-bold mb-4">Edit Bahan</h3>

    <div class="card border-0 shadow-sm rounded-5">
        <div class="card-body p-4">
            <form method="POST"
                  action="{{ route('tenant.admin.materials.update', [$currentTenant->slug, $material->id]) }}">
                @csrf
                @method('PUT')

                @include('admin.materials.partials.form', ['material' => $material])

                <div class="text-end mt-4">
                    <button class="btn btn-primary rounded-4 px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection