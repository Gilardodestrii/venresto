<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DefaultRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'pos.access',
            'orders.view',
            'orders.pay',
            'orders.void',
            'kitchen.access',
            'inventory.view',
            'inventory.manage',
            'recipe.manage',
            'costing.view',
            'stock.transfer',
            'waste.manage',
            'stock.movement.view',
            'outlet.manage',
            'menu.manage',
            'users.manage',
            'settings.manage',
            'reports.view',
        ];

        $rolePermissions = [
            'owner' => $permissions,
            'manager' => [
                'dashboard.view',
                'pos.access',
                'orders.view',
                'orders.pay',
                'orders.void',
                'kitchen.access',
                'inventory.view',
                'inventory.manage',
                'recipe.manage',
                'costing.view',
                'stock.transfer',
                'waste.manage',
                'stock.movement.view',
                'outlet.manage',
                'menu.manage',
                'users.manage',
                'settings.manage',
                'reports.view',
            ],
            'cashier' => [
                'dashboard.view',
                'pos.access',
                'orders.view',
                'orders.pay',
            ],
            'kitchen' => [
                'dashboard.view',
                'kitchen.access',
            ],
            'inventory' => [
                'dashboard.view',
                'inventory.view',
                'inventory.manage',
                'recipe.manage',
                'costing.view',
                'stock.transfer',
                'waste.manage',
                'stock.movement.view',
            ],
        ];

        $tenants = Tenant::query()->get();

        foreach ($tenants as $tenant) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                    'tenant_id' => $tenant->id,
                ]);
            }

            foreach ($rolePermissions as $roleName => $rolePermissionNames) {
                $role = Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web',
                    'tenant_id' => $tenant->id,
                ]);

                $role->syncPermissions($rolePermissionNames);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
