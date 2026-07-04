<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Urutan seeding PENTING:
     * 1. Plans (diperlukan oleh tenants)
     * 2. Tenant default + Outlet + Settings
     * 3. Roles & Permissions (tenant-scoped, termasuk superadmin role)
     * 4. Superadmin user + assign role
     */
    public function run(): void
    {
        // 1) Plans — SEBELUM tenant agar foreign key works
        $this->call(PlanSeeder::class);

        // 2) Tenant default
        $tenantId = DB::table('tenants')->insertGetId([
            'name'          => 'Venom-resto',
            'slug'          => 'venom-resto',
            'plan_id'       => 1, // starter
            'status'        => 'trialing',
            'trial_ends_at' => now()->addDays(7),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // 3) Outlet pertama
        DB::table('outlets')->insert([
            'tenant_id' => $tenantId,
            'name'      => 'Main Outlet',
            'address'   => 'Jl. Utama No.1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4) Tenant settings
        DB::table('tenant_settings')->insert([
            'tenant_id'                     => $tenantId,
            'tax_enabled'                   => true,
            'tax_rate'                      => 0.11,
            'tax_inclusive'                 => false,
            'service_enabled'               => true,
            'service_rate'                  => 0.05,
            'service_inclusive'             => false,
            'kitchen_ticket_on_open_for_cash' => true,
            'stock_deduct_on'               => 'paid',
            'created_at'                    => now(),
            'updated_at'                    => now(),
        ]);

        // 5) Roles & Permissions — tenant-scoped (owner, superadmin, manager, cashier, kitchen, waiter)
        $this->call(RolesPermissionsSeeder::class);

        // 6) Superadmin user — buat user admin@venom-resto.com + assign role superadmin
        $this->call(SuperadminSeeder::class);
    }
}
