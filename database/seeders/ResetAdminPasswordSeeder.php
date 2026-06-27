<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetAdminPasswordSeeder extends Seeder
{
    public function run(): void
    {
        // Cari user admin@venom-resto.com, kalau ngga ada, cari email gilardo, atau user pertama
        $user = User::where('email', 'admin@venom-resto.com')->first();
        
        if (!$user) {
             $user = User::where('email', 'gilardo@venresto.com')->first();
        }
        
        if (!$user) {
             $user = User::first();
        }

        if ($user) {
            $user->update([
                'password' => Hash::make('password123')
            ]);
            $this->command->info("Password untuk {$user->email} berhasil direset menjadi: password123");
        } else {
            $this->command->error("Tidak ada satupun user ditemukan di database!");
        }
    }
}
