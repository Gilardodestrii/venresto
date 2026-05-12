<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $t) {
            // kolom nullable supaya aman walau tenant lama belum punya owner
            $t->unsignedBigInteger('owner_user_id')->nullable()->after('slug');
            $t->index('owner_user_id');

            // (opsional) kalau mau taruh trial_ends_at/status juga sekalian:
            if (!Schema::hasColumn('tenants', 'status')) {
                $t->string('status', 20)->default('trialing')->after('plan_id'); // trialing|active|past_due|canceled
            }
            if (!Schema::hasColumn('tenants', 'trial_ends_at')) {
                $t->timestamp('trial_ends_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $t) {
            $t->dropIndex(['owner_user_id']);
            $t->dropColumn('owner_user_id');

            // (opsional) rollback tambahan
            // $t->dropColumn(['status','trial_ends_at']);
        });
    }
};