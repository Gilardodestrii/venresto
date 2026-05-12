<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Tenant;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'web';
        $teamKey = config('permission.column_names.team_foreign_key', 'tenant_id');

        $allPerms = [
            'orders.view','orders.create','orders.update','orders.refund','orders.void',
            'pos.open_shift','pos.close_shift','pos.hold','pos.split_bill',
            'pos.discount','pos.service_charge','pos.tip',
            'kitchen.ticket.view','kitchen.ticket.print','kitchen.ticket.reprint','printer.manage',
            'menu.manage','categories.manage','outlets.manage','tables.manage',
            'inventory.view','inventory.edit','supplier.manage',
            'reports.view.basic','reports.view.advanced',
            'users.manage','settings.manage','payments.manage',
        ];

        $rolePerms = [
            'owner'   => $allPerms,
            'manager' => [
                'orders.view','orders.create','orders.update','orders.refund',
                'pos.open_shift','pos.close_shift','pos.hold','pos.split_bill','pos.discount','pos.service_charge','pos.tip',
                'kitchen.ticket.view','kitchen.ticket.print','kitchen.ticket.reprint','printer.manage',
                'menu.manage','categories.manage','outlets.manage','tables.manage',
                'inventory.view','inventory.edit','supplier.manage',
                'reports.view.basic','reports.view.advanced',
                'users.manage','settings.manage','payments.manage',
            ],
            'cashier' => [
                'orders.view','orders.create','orders.update',
                'pos.open_shift','pos.close_shift','pos.hold','pos.split_bill','pos.discount','pos.service_charge','pos.tip',
                'kitchen.ticket.view','kitchen.ticket.print',
                'reports.view.basic',
            ],
            'kitchen' => [
                'kitchen.ticket.view','kitchen.ticket.print','kitchen.ticket.reprint',
            ],
            'waiter'  => [
                'orders.view','orders.create','kitchen.ticket.view',
            ],
        ];

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Tenant::query()->orderBy('id')->chunk(50, function ($tenants) use ($allPerms, $rolePerms, $guard, $teamKey) {
            foreach ($tenants as $tenant) {
                DB::transaction(function () use ($tenant, $allPerms, $rolePerms, $guard, $teamKey) {

                    // Set context teams (tetap dilakukan)
                    app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

                    // Create permissions per-tenant
                    foreach ($allPerms as $p) {
                        Permission::firstOrCreate(
                            ['name' => $p, 'guard_name' => $guard, 'tenant_id' => $tenant->id]
                        );
                    }

                    // Create roles & attach permissions per-tenant (paksa isi tenant_id di pivot)
                    foreach ($rolePerms as $roleName => $perms) {
                        $role = Role::firstOrCreate(
                            ['name' => $roleName, 'guard_name' => $guard, 'tenant_id' => $tenant->id]
                        );

                        // Ambil ID permission milik tenant ini sesuai daftar
                        $permIds = Permission::query()
                            ->where('tenant_id', $tenant->id)
                            ->where('guard_name', $guard)
                            ->whereIn('name', $perms)
                            ->pluck('id')
                            ->all();

                        // TULIS LANGSUNG KE PIVOT + tenant_id (no more 1364)
                        $role->permissions()->syncWithPivotValues(
                            array_column(array_map(fn($id)=>['id'=>$id], $permIds), 'id'),
                            [$teamKey => $tenant->id]
                        );
                    }
                });
            }
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
