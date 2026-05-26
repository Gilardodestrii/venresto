<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->unsignedBigInteger('outlet_id')->nullable()->after('tenant_id')->index();
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->unsignedBigInteger('outlet_id')->nullable()->after('tenant_id')->index();
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->unsignedBigInteger('outlet_id')->nullable()->after('tenant_id')->index();
        });

        $outlets = DB::table('outlets')
            ->select('tenant_id', DB::raw('MIN(id) as outlet_id'))
            ->groupBy('tenant_id')
            ->get();

        foreach ($outlets as $outlet) {
            DB::table('materials')
                ->where('tenant_id', $outlet->tenant_id)
                ->whereNull('outlet_id')
                ->update(['outlet_id' => $outlet->outlet_id]);

            DB::table('recipes')
                ->where('tenant_id', $outlet->tenant_id)
                ->whereNull('outlet_id')
                ->update(['outlet_id' => $outlet->outlet_id]);

            DB::table('stock_movements')
                ->where('tenant_id', $outlet->tenant_id)
                ->whereNull('outlet_id')
                ->update(['outlet_id' => $outlet->outlet_id]);
        }
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn('outlet_id');
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('outlet_id');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('outlet_id');
        });
    }
};
