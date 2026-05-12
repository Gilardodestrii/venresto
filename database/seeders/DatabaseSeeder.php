<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {
        DB::table('tenants')->insert([
          ['name'=>'Venom-resto','slug'=>'venom-resto','plan_id'=>1,'status'=>'trialing','trial_ends_at'=>now()->addDays(7)],
        ]);

        //PlanSeeder
        $this->call(PlanSeeder::class);
        // 2) Roles & Permissions (tenant-scoped)
        $this->call(RolesPermissionsSeeder::class);
      }
}
