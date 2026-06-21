@extends('layouts.admin')

@section('page-title', 'Detail Outlet')

@section('content')

<div class="container-fluid px-0">

    <div class="row justify-content-center">

        <div class="col-xl-8">

            <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm">

                {{-- HEADER --}}
                <div class="p-8 bg-gradient-to-br from-sky-50/50 to-blue-50/30 flex items-center gap-6">

                    <div class="w-24 h-24 rounded-2xl bg-white flex items-center justify-center text-4xl text-sky-600 shadow-lg shadow-sky-100 flex-shrink-0">

                        <i class="bi bi-shop"></i>

                    </div>

                    <div>

                        <h2 class="font-bold text-2xl mb-2">
                            {{ $outlet->name }}
                        </h2>

                        <div class="text-gray-500">
                            Detail informasi outlet restoran
                        </div>

                    </div>

                </div>

                {{-- BODY --}}
                <div class="p-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div class="bg-gray-50 rounded-2xl p-6">

                            <div class="text-sm text-gray-500 mb-2">
                                Nama Outlet
                            </div>

                            <div class="font-semibold text-gray-900">
                                {{ $outlet->name }}
                            </div>

                        </div>

                        <div class="bg-gray-50 rounded-2xl p-6">

                            <div class="text-sm text-gray-500 mb-2">
                                Status
                            </div>

                            <div class="font-semibold">

                                @if($outlet->is_active)

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                        Active
                                    </span>

                                @else

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                                        Non Active
                                    </span>

                                @endif

                            </div>

                        </div>

                        <div class="md:col-span-2 bg-gray-50 rounded-2xl p-6">

                            <div class="text-sm text-gray-500 mb-2">
                                Alamat Outlet
                            </div>

                            <div class="font-semibold text-gray-900">
                                {{ $outlet->address ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-8 py-6 border-t border-gray-100 flex justify-between gap-4">

                    <a href="{{ route('tenant.admin.outlets.index', $currentTenant->slug) }}"
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors inline-flex items-center gap-2">

                        <i class="bi bi-arrow-left"></i>
                        Kembali

                    </a>

                    <a href="{{ route('tenant.admin.outlets.edit', [$currentTenant->slug, $outlet->id]) }}"
                       class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors inline-flex items-center gap-2">

                        <i class="bi bi-pencil-square"></i>
                        Edit Outlet

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
