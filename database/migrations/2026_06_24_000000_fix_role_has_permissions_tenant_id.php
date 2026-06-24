<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add tenant_id to role_has_permissions if not exists
        if (!Schema::hasColumn('role_has_permissions', 'tenant_id')) {
            Schema::table('role_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('role_id');
                $table->index('tenant_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('role_has_permissions', 'tenant_id')) {
            Schema::table('role_has_permissions', function (Blueprint $table) {
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};