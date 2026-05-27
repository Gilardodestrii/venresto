@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Role Management</h3>
            <div class="text-muted">Kelola role dan akses staff berdasarkan tenant aktif</div>
        </div>
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

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Total Staff</div>
                    <h4 class="fw-bold mb-0">{{ $users->count() }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Total Role</div>
                    <h4 class="fw-bold mb-0">{{ $roles->count() }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-5">
                <div class="card-body p-4">
                    <div class="text-muted small mb-1">Scope</div>
                    <h6 class="fw-bold mb-0">{{ $currentTenant->name ?? $currentTenant->slug }}</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-1">Staff Access</h5>
            <div class="text-muted small">Assign role user. Perubahan langsung memengaruhi sidebar dan akses route.</div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Staff</th>
                        <th>Email</th>
                        <th>Role Saat Ini</th>
                        <th>Ubah Role</th>
                        <th class="text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $currentRole = $user->roles->first()?->name;
                        @endphp
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold">{{ $user->name }}</div>
                                <small class="text-muted">ID: {{ $user->id }}</small>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($currentRole)
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        {{ ucwords(str_replace('_', ' ', $currentRole)) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                        No Role
                                    </span>
                                @endif
                            </td>
                            <td style="min-width:220px;">
                                <form id="role-form-{{ $user->id }}"
                                      method="POST"
                                      action="{{ route('tenant.admin.roles.update', [$currentTenant->slug, $user->id]) }}">
                                    @csrf
                                    @method('PUT')

                                    <select name="role" class="form-select rounded-4" required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $currentRole === $role->name ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="text-end px-4">
                                <button form="role-form-{{ $user->id }}"
                                        class="btn btn-sm btn-primary rounded-pill px-3"
                                        onclick="return confirm('Update role user ini?')">
                                    Simpan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
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
