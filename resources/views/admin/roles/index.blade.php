@extends('layouts.admin')

@section('content')

<div class="container mx-auto px-4">

    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div>
            <h3 class="font-bold mb-1">Role Management</h3>
            <div class="text-gray-500 text-sm">Kelola role dan akses staff berdasarkan tenant aktif</div>
        </div>
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
        <div class="bg-white border-0 shadow-sm rounded-2xl">
            <div class="p-4">
                <div class="text-gray-500 text-sm mb-1">Total Staff</div>
                <h4 class="font-bold mb-0">{{ $users->count() }}</h4>
            </div>
        </div>

        <div class="bg-white border-0 shadow-sm rounded-2xl">
            <div class="p-4">
                <div class="text-gray-500 text-sm mb-1">Total Role</div>
                <h4 class="font-bold mb-0">{{ $roles->count() }}</h4>
            </div>
        </div>

        <div class="bg-white border-0 shadow-sm rounded-2xl">
            <div class="p-4">
                <div class="text-gray-500 text-sm mb-1">Scope</div>
                <h6 class="font-bold mb-0">{{ $currentTenant->name ?? $currentTenant->slug }}</h6>
            </div>
        </div>
    </div>

    <div class="bg-white border-0 shadow-sm rounded-2xl overflow-hidden">
        <div class="bg-white border-0 p-4 border-b border-gray-100">
            <h5 class="font-bold mb-1">Staff Access</h5>
            <div class="text-gray-500 text-sm">Assign role user. Perubahan langsung memengaruhi sidebar dan akses route.</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full align-middle mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Saat Ini</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Role</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        @php
                            $currentRole = $user->roles->first()?->name;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="font-semibold">{{ $user->name }}</div>
                                <small class="text-gray-400">ID: {{ $user->id }}</small>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
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
                            <td class="px-4 py-3" style="min-width:220px;">
                                <form id="role-form-{{ $user->id }}"
                                      method="POST"
                                      action="{{ route('tenant.admin.roles.update', [$currentTenant->slug, $user->id]) }}">
                                    @csrf
                                    @method('PUT')

                                    <select name="role" class="block w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $currentRole === $role->name ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button form="role-form-{{ $user->id }}"
                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-full transition-colors"
                                        onclick="return confirm('Update role user ini?')">
                                    Simpan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400">
                                <i class="bi bi-people text-4xl block mb-2"></i>
                                Belum ada staff di tenant ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
