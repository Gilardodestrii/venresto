<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
      Schema::create('plans', function (Blueprint $t) {
        $t->id();
        $t->string('code', 40)->unique();      // 'starter', 'pro', 'enterprise'
        $t->string('name', 80);                 // Starter, Pro, Enterprise
        $t->string('currency', 10)->default('IDR');

        // Simpan harga sebagai integer (rupiah), null = negotiable/custom
        $t->unsignedBigInteger('price_monthly')->nullable();
        $t->unsignedBigInteger('price_yearly')->nullable();

        // Batasan/limit (null = tak terbatas / tidak dibatasi)
        $t->unsignedInteger('max_outlets')->nullable();
        $t->unsignedInteger('max_tables')->nullable();
        $t->unsignedInteger('max_users')->nullable();

        // Durasi trial default (hari)
        $t->unsignedInteger('trial_days')->default(7);

        // Fitur tambahan disimpan sebagai JSON
        $t->json('features_json')->nullable();

        $t->boolean('is_active')->default(true);
        $t->timestamps();
    });
    Schema::create('tenants', function(Blueprint $t){
      $t->id(); $t->string('name'); $t->string('slug')->unique();
      $t->foreignId('plan_id')->nullable();
      $t->string('status')->default('trialing');
      $t->timestamp('trial_ends_at')->nullable();
      $t->timestamps();
    });
    Schema::create('subscriptions', function(Blueprint $t){
      $t->id(); $t->foreignId('tenant_id'); $t->foreignId('plan_id'); $t->string('status'); $t->timestamp('current_period_start')->nullable(); $t->timestamp('current_period_end')->nullable(); $t->timestamps();
    });
    Schema::create('payments_log', function(Blueprint $t){
      $t->id(); $t->foreignId('tenant_id'); $t->string('gateway'); $t->string('ref'); $t->integer('amount')->default(0); $t->string('status'); $t->json('payload_json')->nullable(); $t->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('payments_log'); Schema::dropIfExists('subscriptions'); Schema::dropIfExists('tenants'); Schema::dropIfExists('plans');
  }
};
