@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold mb-1">Role Management</h3>
            <div class="text-gray-500 text-sm">Kelola role dan akses staff berdasarkan tenant aktif</div>
        </div>
        <a href="{{ route('tenant.admin.roles.create', $currentTenant->slug) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-blue-200">
            <i class="bi bi-plus-circle"></i>
            Tambah Staff
        </a>
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
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tenant.admin.roles.edit', [$currentTenant->slug, $user->id]) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-yellow-600 bg-yellow-50 hover:bg-yellow-100 rounded-full transition-colors">
                                        <i class="bi bi-pencil mr-1"></i>
                                        Edit
                                    </a>
                                    <form method="POST"
                                          action="{{ route('tenant.admin.roles.delete', [$currentTenant->slug, $user->id]) }}"
                                          class="inline"
                                          onsubmit="return confirm('Hapus staff ini? Ini tidak bisa dibatalkan.')">
                                        @csrf
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
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-people text-4xl block mb-2 text-gray-300"></i>
                                <div class="font-medium">Belum ada staff di tenant ini.</div>
                                <button type="button"
                                        class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors"
                                        onclick="window.location.href='{{ route('tenant.admin.roles.create', $currentTenant->slug) }}'">
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


@endsection