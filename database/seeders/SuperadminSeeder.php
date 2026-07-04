<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperadminSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Buat role superadmin (global — tanpa tenant_id)
        $role = Role::firstOrCreate([
            'name'       => 'superadmin',
            'guard_name' => 'web',
            'tenant_id'  => null,
        ]);

        // 2) Cari atau buat user admin
        $user = User::where('email', 'admin@venom-resto.com')->first();

        if (!$user) {
            // Fresh migrate — buat user baru
            $tenantId = DB::table('tenants')->min('id');

            $user = User::create([
                'tenant_id' => $tenantId,
                'name'      => 'Super Admin',
                'email'     => 'admin@venom-resto.com',
                'password'  => Hash::make('password'),
            ]);

            $this->command->info("User admin dibuat: admin@venom-resto.com / password");
        }

        // 3) Assign role superadmin ke user
        $exists = DB::table('model_has_roles')
            ->where('role_id', $role->id)
            ->where('model_type', User::class)
            ->where('model_id', $user->id)
            ->exists();

        if (!$exists) {
            DB::table('model_has_roles')->insert([
                'role_id'    => $role->id,
                'model_type' => User::class,
                'model_id'   => $user->id,
                'tenant_id'  => $user->tenant_id,
            ]);

            $this->command->info("Role 'superadmin' ditugaskan ke {$user->email}");
        }
    }
}
