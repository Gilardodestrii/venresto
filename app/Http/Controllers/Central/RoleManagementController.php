<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleManagementController extends Controller
{
    public function index()
    {
        $tenant = TenantContext::get();
        app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

        $users = User::where('tenant_id', $tenant->id)
            ->orderBy('name')
            ->get();

        $roles = Role::where('tenant_id', $tenant->id)
            ->orderBy('name')
            ->get();

        return view('admin.roles.index', compact('users', 'roles'));
    }

    public function update(Request $request, string $tenant, User $user)
    {
        $tenantModel = TenantContext::get();

        abort_if($user->tenant_id !== $tenantModel->id, 404);

        $validated = $request->validate([
            'role' => ['required', 'string'],
        ]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($tenantModel->id);

        $role = Role::where('tenant_id', $tenantModel->id)
            ->where('name', $validated['role'])
            ->firstOrFail();

        $user->syncRoles([$role->name]);

        return back()->with('success', 'Role user berhasil diperbarui.');
    }
}
