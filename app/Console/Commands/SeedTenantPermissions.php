<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SeedTenantPermissions extends Command
{
    protected $signature = 'tenants:seed-permissions {--tenant= : Slug or ID of specific tenant}';
    protected $description = 'Seed all permissions and sync to roles for existing tenants';

    const ALL_PERMISSIONS = [
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
        'reports.view',
    ];

    const ROLE_PERMISSIONS = [
        'owner' => null, // null = all permissions
        'manager' => [
            'dashboard.view','pos.access','orders.view','orders.pay','orders.void',
            'kitchen.access','inventory.view','inventory.manage','recipe.manage',
            'costing.view','stock.transfer','waste.manage','stock.movement.view',
            'outlet.manage','menu.manage','reports.view',
        ],
        'cashier' => [
            'dashboard.view','pos.access','orders.view','orders.pay',
        ],
        'kitchen' => [
            'dashboard.view','kitchen.access',
        ],
        'inventory' => [
            'dashboard.view','inventory.view','inventory.manage','recipe.manage',
            'costing.view','stock.transfer','waste.manage','stock.movement.view',
        ],
        'waiter' => [
            'dashboard.view','pos.access','orders.view',
        ],
    ];

    public function handle(): int
    {
        $query = Tenant::query();

        if ($slug = $this->option('tenant')) {
            $query->where('slug', $slug)->orWhere('id', $slug);
        }

        $tenants = $query->get();

        if ($tenants->isEmpty()) {
            $this->error('No tenants found.');
            return 1;
        }

        foreach ($tenants as $tenant) {
            $this->info("Processing tenant: {$tenant->name} ({$tenant->slug})");

            app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

            // 1. Create all permissions
            foreach (self::ALL_PERMISSIONS as $perm) {
                Permission::firstOrCreate([
                    'name'       => $perm,
                    'guard_name' => 'web',
                    'tenant_id'  => $tenant->id,
                ]);
            }

            // 2. Create roles & sync permissions
            foreach (self::ROLE_PERMISSIONS as $roleName => $rolePerms) {
                $role = Role::firstOrCreate([
                    'name'       => $roleName,
                    'guard_name' => 'web',
                    'tenant_id'  => $tenant->id,
                ]);

                $permsToSync = $rolePerms ?? self::ALL_PERMISSIONS;

                // Get permission IDs for this tenant
                $permIds = Permission::whereIn('name', $permsToSync)
                    ->where('tenant_id', $tenant->id)
                    ->pluck('id')
                    ->toArray();

                // Manual sync to role_has_permissions with tenant_id
                \DB::table('role_has_permissions')
                    ->where('role_id', $role->id)
                    ->where('tenant_id', $tenant->id)
                    ->delete();

                $now = now();
                foreach ($permIds as $permId) {
                    \DB::table('role_has_permissions')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $permId,
                        'tenant_id' => $tenant->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                $this->line("  - Role '{$roleName}': synced " . count($permIds) . ' permissions');
            }

            $this->info("  Done.");
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->info('All tenants processed. Permission cache cleared.');

        return 0;
    }
}
