<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('outlet_tables', function (Blueprint $table) {
            $table->unsignedBigInteger('outlet_id')->after('tenant_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('outlet_tables', function (Blueprint $table) {
            $table->dropColumn('outlet_id');
        });
    }
};