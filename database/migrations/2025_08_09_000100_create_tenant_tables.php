<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('users', function(Blueprint $t){
      $t->id(); $t->unsignedBigInteger('tenant_id')->index();
      $t->string('name'); $t->string('email'); $t->string('password'); $t->timestamps();
    });
    Schema::create('tenant_settings', function(Blueprint $t){
      $t->unsignedBigInteger('tenant_id')->primary();
      $t->boolean('tax_enabled')->default(true); $t->decimal('tax_rate',4,3)->default(0.11); $t->boolean('tax_inclusive')->default(false);
      $t->boolean('service_enabled')->default(true); $t->decimal('service_rate',4,3)->default(0.05); $t->boolean('service_inclusive')->default(false);
      $t->boolean('kitchen_ticket_on_open_for_cash')->default(true);
      $t->enum('stock_deduct_on',['open','paid'])->default('paid');
      $t->json('payments_json')->nullable();
      $t->timestamps();
    });
    Schema::create('menu_categories', function(Blueprint $t){
      $t->id(); $t->unsignedBigInteger('tenant_id')->index(); $t->string('name'); $t->integer('seq')->default(0); $t->timestamps();
    });
    Schema::create('menu_items', function(Blueprint $t){
      $t->id(); $t->unsignedBigInteger('tenant_id')->index();
      $t->unsignedBigInteger('category_id');
      $t->string('name'); $t->integer('price'); $t->string('sku')->nullable(); $t->text('image_url')->nullable(); $t->boolean('is_active')->default(true);
      $t->timestamps();
    });
    Schema::create('orders', function (Blueprint $t) {
      $t->id();
      $t->unsignedBigInteger('tenant_id')->index();
      $t->unsignedBigInteger('outlet_id')->nullable();
      $t->string('code')->index();
      $t->string('table_code')->nullable();
      $t->string('customer_name', 100);
      $t->string('customer_phone', 20)->nullable();
      $t->string('customer_note', 255)->nullable();
      $t->enum('status', ['open','pending_payment','paid','void','expired'])->default('open');
      $t->integer('subtotal')->default(0);
      $t->integer('discount')->default(0);
      $t->integer('tax')->default(0);
      $t->integer('service')->default(0);
      $t->integer('grand_total')->default(0);
      $t->enum('payment_method', ['cash','qris','qris_snap','qris_static'])->nullable();
      $t->unsignedBigInteger('cashier_id')->nullable();
      $t->timestamps();
    });
    Schema::create('order_items', function(Blueprint $t){
      $t->id(); $t->unsignedBigInteger('tenant_id')->index();
      $t->unsignedBigInteger('order_id'); $t->unsignedBigInteger('menu_item_id');
      $t->integer('qty'); $t->integer('price'); $t->string('note',255)->nullable();
      $t->enum('kitchen_status',['new','cook','ready','served'])->default('new');
      $t->timestamps();
    });
    Schema::create('materials', function(Blueprint $t){
      $t->id(); $t->unsignedBigInteger('tenant_id')->index(); $t->string('name'); $t->string('unit'); $t->decimal('stock',12,2)->default(0); $t->decimal('min_stock',12,2)->default(0); $t->timestamps();
    });
    Schema::create('recipes', function(Blueprint $t){
      $t->id(); $t->unsignedBigInteger('tenant_id')->index(); $t->unsignedBigInteger('item_id'); $t->unsignedBigInteger('material_id'); $t->decimal('qty',12,3); $t->timestamps();
    });
    Schema::create('stock_movements', function(Blueprint $t){
      $t->id(); $t->unsignedBigInteger('tenant_id')->index(); $t->unsignedBigInteger('material_id'); $t->enum('type',['in','out']); $t->decimal('qty',12,3); $t->string('ref',50); $t->unsignedBigInteger('created_by')->nullable(); $t->timestamps();
    });
    Schema::create('shifts', function(Blueprint $t){
      $t->id(); $t->unsignedBigInteger('tenant_id')->index(); $t->unsignedBigInteger('cashier_id'); $t->timestamp('opened_at'); $t->timestamp('closed_at')->nullable(); $t->integer('opening_cash'); $t->integer('closing_cash')->nullable(); $t->text('notes')->nullable(); $t->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('shifts'); Schema::dropIfExists('stock_movements'); Schema::dropIfExists('recipes'); Schema::dropIfExists('materials'); Schema::dropIfExists('order_items'); Schema::dropIfExists('orders'); Schema::dropIfExists('menu_items'); Schema::dropIfExists('menu_categories'); Schema::dropIfExists('tenant_settings'); Schema::dropIfExists('users');
  }
};
