<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;

class SuperadminSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan filter teams sementara supaya role superadmin global
        $role = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
            'tenant_id' => null 
        ]);

        $user = User::where('email', 'admin@venom-resto.com')->first();
        if (!$user) {
            $user = User::first(); 
        }

        if ($user) {
            DB::table('model_has_roles')->insertOrIgnore([
                'role_id' => $role->id,
                'model_type' => User::class,
                'model_id' => $user->id,
                'tenant_id' => $user->tenant_id
            ]);
        }
    }
}
