@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('tenant.admin.roles.index', $currentTenant->slug) }}"
           class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">Tambah Staff Baru</h3>
            <div class="text-sm text-gray-500">Buat akun staff baru dan assign role</div>
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
            <form method="POST"
                  action="{{ route('tenant.admin.roles.create', $currentTenant->slug) }}">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                               placeholder="Contoh: Budi Santoso"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('email') border-red-500 @enderror"
                               placeholder="budi@venresto.com"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password"
                               name="password"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('password') border-red-500 @enderror"
                               placeholder="Min. 6 karakter"
                               required>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                        <select name="role"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('role') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Role</option>
                            @foreach($roles->where('name', '!=', 'owner') as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-6 mt-6 border-t border-gray-100">
                    <a href="{{ route('tenant.admin.roles.index', $currentTenant->slug) }}"
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors shadow-lg shadow-blue-200">
                        <i class="bi bi-check-circle mr-1"></i>
                        Simpan Staff
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
