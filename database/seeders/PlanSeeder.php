<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        // Starter
        Plan::updateOrCreate(
            ['code' => 'starter'],
            [
                'name'          => 'Starter',
                'currency'      => 'IDR',
                'price_monthly' => 199_000,
                'price_yearly'  => 169_000 * 12, // diskon tahunan sudah dihitung
                'max_outlets'   => 1,
                'max_tables'    => 10,
                'max_users'     => 5,
                'trial_days'    => 7,
                'features_json' => [
                    'qr_menu'        => true,
                    'pos_basic'      => true,
                    'split_bill'     => false,
                    'service_charge' => false,
                    'printer_kitchen'=> false,
                    'inventory'      => false,
                    'report_basic'   => true,
                    'report_advanced'=> false,
                    'rbac_manager'   => false,
                ],
                'is_active'     => true,
            ]
        );

        // Pro
        Plan::updateOrCreate(
            ['code' => 'pro'],
            [
                'name'          => 'Pro',
                'currency'      => 'IDR',
                'price_monthly' => 399_000,
                'price_yearly'  => 339_000 * 12,
                'max_outlets'   => 3,
                'max_tables'    => null, // tak terbatas
                'max_users'     => null, // tak terbatas
                'trial_days'    => 7,
                'features_json' => [
                    'qr_menu'        => true,
                    'pos_basic'      => true,
                    'split_bill'     => true,
                    'service_charge' => true,
                    'printer_kitchen'=> true,
                    'inventory'      => true,
                    'report_basic'   => true,
                    'report_advanced'=> true,
                    'rbac_manager'   => true,
                ],
                'is_active'     => true,
            ]
        );

        // Enterprise (opsional)
        Plan::updateOrCreate(
            ['code' => 'enterprise'],
            [
                'name'          => 'Enterprise',
                'currency'      => 'IDR',
                'price_monthly' => null, // custom
                'price_yearly'  => null, // custom
                'max_outlets'   => null,
                'max_tables'    => null,
                'max_users'     => null,
                'trial_days'    => 7,
                'features_json' => [
                    'qr_menu'        => true,
                    'pos_basic'      => true,
                    'split_bill'     => true,
                    'service_charge' => true,
                    'printer_kitchen'=> true,
                    'inventory'      => true,
                    'report_basic'   => true,
                    'report_advanced'=> true,
                    'rbac_manager'   => true,
                    'integrations'   => ['erp','accounting','sso'],
                    'sla'            => true,
                ],
                'is_active'     => true,
            ]
        );
    }
}
