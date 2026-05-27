<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->unsignedBigInteger('outlet_id')->index();
            $table->string('code')->unique();
            $table->enum('reason', ['expired', 'damaged', 'spillage', 'overcooked', 'staff_meal', 'other'])->default('other')->index();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('waste_record_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('waste_record_id')->index();
            $table->unsignedBigInteger('material_id')->index();
            $table->decimal('qty', 12, 3);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_record_items');
        Schema::dropIfExists('waste_records');
    }
};
