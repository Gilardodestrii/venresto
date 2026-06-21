@extends('layouts.admin')

@section('page-title', 'Edit Outlet')

@section('content')

<div class="container-fluid px-0">

    <div class="row justify-content-center">

        <div class="col-xl-7">

            <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm">

                {{-- HEADER --}}
                <div class="px-8 py-6 border-b border-gray-100">

                    <div>

                        <h3 class="font-bold text-xl mb-1">
                            Edit Outlet
                        </h3>

                        <p class="text-gray-500 mb-0">
                            Perbarui informasi outlet restoran
                        </p>

                    </div>

                </div>

                {{-- FORM --}}
                <form method="POST"
                      action="{{ route('tenant.admin.outlets.update', [$currentTenant->slug, $outlet->id]) }}">

                    @csrf
                    @method('PUT')

                    <div class="px-8 py-6">

                        {{-- NAME --}}
                        <div class="mb-6">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Outlet
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $outlet->name) }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') is-invalid @enderror">

                            @error('name')

                                <div class="invalid-feedback text-red-600 text-sm mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                        {{-- ADDRESS --}}
                        <div class="mb-6">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Outlet
                            </label>

                            <textarea name="address"
                                      rows="5"
                                      class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('address') is-invalid @enderror">{{ old('address', $outlet->address) }}</textarea>

                            @error('address')

                                <div class="invalid-feedback text-red-600 text-sm mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="px-8 py-6 border-t border-gray-100 flex justify-between gap-4">

                        <a href="{{ route('tenant.admin.outlets.index', $currentTenant->slug) }}"
                           class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">

                            Kembali

                        </a>

                        <button class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors inline-flex items-center gap-2">

                            <i class="bi bi-check-circle"></i>
                            Update Outlet

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
