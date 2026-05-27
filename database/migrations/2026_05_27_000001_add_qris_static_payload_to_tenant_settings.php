<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenant_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('tenant_settings', 'qris_static_payload')) {
                $table->text('qris_static_payload')->nullable()->after('payments_json');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenant_settings', function (Blueprint $table) {
            if (Schema::hasColumn('tenant_settings', 'qris_static_payload')) {
                $table->dropColumn('qris_static_payload');
            }
        });
    }
};
