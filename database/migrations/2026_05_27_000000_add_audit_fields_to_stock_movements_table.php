<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->decimal('stock_before', 12, 3)->nullable()->after('qty');
            $table->decimal('stock_after', 12, 3)->nullable()->after('stock_before');
            $table->text('note')->nullable()->after('ref');
            $table->string('source_type')->nullable()->after('note');
            $table->unsignedBigInteger('source_id')->nullable()->after('source_type');
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn([
                'stock_before',
                'stock_after',
                'note',
                'source_type',
                'source_id'
            ]);
        });
    }
};
