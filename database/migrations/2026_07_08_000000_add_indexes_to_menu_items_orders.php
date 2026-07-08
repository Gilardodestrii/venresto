<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Index untuk menu_items
        Schema::table('menu_items', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('is_active');
            $table->index('name');
        });

        // Index untuk orders
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('outlet_id');
            $table->index('created_at');
        });

        // Index untuk order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('menu_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['name']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['outlet_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['menu_item_id']);
        });
    }
};