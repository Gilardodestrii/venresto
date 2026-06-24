@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold mb-1">Role Management</h3>
            <div class="text-gray-500 text-sm">Kelola role dan akses staff berdasarkan tenant aktif</div>
        </div>
        <button type="button"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-blue-200"
                @click="$dispatch('open-staff-modal')">
            <i class="bi bi-plus-circle"></i>
            Tambah Staff
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-4 shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-4 shadow-sm" role="alert">
            <ul class="mb-0 pl-3 list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="text-gray-500 text-sm mb-1">Total Staff</div>
            <h4 class="font-bold mb-0">{{ $users->count() }}</h4>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="text-gray-500 text-sm mb-1">Total Role</div>
            <h4 class="font-bold mb-0">{{ $roles->count() }}</h4>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="text-gray-500 text-sm mb-1">Scope</div>
            <h6 class="font-bold mb-0">{{ $currentTenant->name ?? $currentTenant->slug }}</h6>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h5 class="font-bold mb-1">Staff Access</h5>
            <div class="text-gray-500 text-sm">Assign role user. Perubahan langsung memengaruhi sidebar dan akses route.</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role Saat Ini</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ubah Role</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        @php
                            $currentRole = $user->roles->first()?->name;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($currentRole)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucwords(str_replace('_', ' ', $currentRole)) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        No Role
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4" style="min-width:220px;">
                                <form id="role-form-{{ $user->id }}"
                                      method="POST"
                                      action="{{ route('tenant.admin.roles.update', [$currentTenant->slug, $user->id]) }}">
                                    @csrf
                                    @method('PUT')

                                    <select name="role"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                            onchange="if(confirm('Update role user ini?')) document.getElementById('role-form-{{ $user->id }}').submit()">
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $currentRole === $role->name ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST"
                                      action="{{ route('tenant.admin.roles.delete', [$currentTenant->slug, $user->id]) }}"
                                      class="inline"
                                      onsubmit="return confirm('Hapus staff ini? Ini tidak bisa dibatalkan.')">
                                    @csrf
                                    @method('POST')
                                    @if($user->id !== auth()->id())
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-full transition-colors">
                                        <i class="bi bi-trash mr-1"></i>
                                        Hapus
                                    </button>
                                    @else
                                    <span class="text-xs text-gray-400 italic">Akun Anda</span>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-people text-4xl block mb-2 text-gray-300"></i>
                                <div class="font-medium">Belum ada staff di tenant ini.</div>
                                <button type="button"
                                        class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors"
                                        @click="$dispatch('open-staff-modal')">
                                    <i class="bi bi-plus-circle mr-1"></i>
                                    Tambah Staff Pertama
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-white">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Create Staff Modal --}}
<div id="create-staff-modal"
     x-data="{ open: false }"
     @open-staff-modal.window="open = true"
     x-show="open"
     x-transition.opacity
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     aria-hidden="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             @click="open = false"></div>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             @click.stop
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <form method="POST"
                  action="{{ route('tenant.admin.roles.create', $currentTenant->slug) }}">
                @csrf

                <div class="px-6 py-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-person-plus text-blue-600 text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Tambah Staff Baru</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
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
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
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
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
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
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
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
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button type="button"
                            class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl font-medium transition-colors"
                            onclick="document.getElementById('create-staff-modal').classList.add('hidden')">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors shadow-lg shadow-blue-200">
                        <i class="bi bi-check-circle mr-1"></i>
                        Simpan Staff
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection