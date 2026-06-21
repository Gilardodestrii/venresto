@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('tenant.admin.materials.index', $currentTenant->slug) }}"
           class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Edit Bahan</h3>
            <div class="text-sm text-gray-500">Perbarui informasi bahan baku</div>
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

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            @include('admin.materials.partials.form', ['material' => $material])
        </div>
    </div>

</div>

@endsection
