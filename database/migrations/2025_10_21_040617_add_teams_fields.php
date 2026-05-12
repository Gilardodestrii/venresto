<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $teams       = config('permission.teams');
        $tableNames  = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (! $teams) {
            // Mode teams nonaktif: tidak perlu apa-apa
            return;
        }
        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }
        if (empty($columnNames['team_foreign_key'] ?? null)) {
            throw new \Exception('Error: team_foreign_key not set in config/permission.php. Run [php artisan config:clear] and try again.');
        }

        $teamKey         = $columnNames['team_foreign_key'];           // ex: tenant_id
        $pivotRole       = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $morphKey        = $columnNames['model_morph_key'] ?? 'model_id';

        // Helper cek index (MySQL saja)
        $hasIndex = function (string $table, string $index) {
            if (DB::getDriverName() !== 'mysql') return false;
            $db = DB::getDatabaseName();
            return DB::table('information_schema.STATISTICS')
                ->where('TABLE_SCHEMA', $db)
                ->where('TABLE_NAME', $table)
                ->where('INDEX_NAME', $index)
                ->exists();
        };

        /**
         * 1) Tambah $teamKey ke permissions (INILAH SUMBER ERROR-MU)
         *    + atur unique: (team_key, name, guard_name)
         */
        if (! Schema::hasColumn($tableNames['permissions'], $teamKey)) {
            Schema::table($tableNames['permissions'], function (Blueprint $table) use ($teamKey) {
                $table->unsignedBigInteger($teamKey)->nullable()->after('guard_name');
                $table->index($teamKey, 'permissions_'.$teamKey.'_index');
            });

            // Drop unique lama (name,guard_name) jika ada
            $oldUnique = 'permissions_name_guard_name_unique';
            try {
                if (Schema::hasColumn($tableNames['permissions'], 'name') && Schema::hasColumn($tableNames['permissions'], 'guard_name')) {
                    // Coba drop by name dulu
                    if ($hasIndex($tableNames['permissions'], $oldUnique)) {
                        Schema::table($tableNames['permissions'], function (Blueprint $table) use ($oldUnique) {
                            $table->dropUnique($oldUnique);
                        });
                    } else {
                        // Fallback drop by columns (aman di sqlite)
                        Schema::table($tableNames['permissions'], function (Blueprint $table) {
                            $table->dropUnique(['name','guard_name']);
                        });
                    }
                }
            } catch (\Throwable $e) {
                // Abaikan bila tidak ada
            }

            // Unique baru per-tenant
            Schema::table($tableNames['permissions'], function (Blueprint $table) use ($teamKey) {
                $table->unique([$teamKey,'name','guard_name'], 'permissions_'.$teamKey.'_name_guard_unique');
            });
        }

        /**
         * 2) Tambah $teamKey ke roles
         *    + atur unique: (team_key, name, guard_name)
         */
        if (! Schema::hasColumn($tableNames['roles'], $teamKey)) {
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
                $table->unsignedBigInteger($teamKey)->nullable()->after('guard_name');
                $table->index($teamKey, 'roles_'.$teamKey.'_index');
            });

            // Drop unique lama (name,guard_name) jika ada
            $oldUnique = 'roles_name_guard_name_unique';
            try {
                if ($hasIndex($tableNames['roles'], $oldUnique)) {
                    Schema::table($tableNames['roles'], function (Blueprint $table) use ($oldUnique) {
                        $table->dropUnique($oldUnique);
                    });
                } else {
                    Schema::table($tableNames['roles'], function (Blueprint $table) {
                        $table->dropUnique(['name','guard_name']);
                    });
                }
            } catch (\Throwable $e) {
                // abaikan
            }

            // Unique baru per-tenant
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
                $table->unique([$teamKey,'name','guard_name'], 'roles_'.$teamKey.'_name_guard_unique');
            });
        }

        /**
         * 3) model_has_permissions: tambah $teamKey + ganti primary
         *    PRIMARY: ($teamKey, permission_id, model_id, model_type)
         */
        if (! Schema::hasColumn($tableNames['model_has_permissions'], $teamKey)) {
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $teamKey, $pivotPermission, $morphKey) {
                $table->unsignedBigInteger($teamKey)->nullable()->after($morphKey);
                $table->index($teamKey, 'mhp_'.$teamKey.'_index');

                if (DB::getDriverName() !== 'sqlite') {
                    // Drop FK agar bisa ubah PK
                    try { $table->dropForeign([$pivotPermission]); } catch (\Throwable $e) {}
                }

                // Ubah Primary Key
                try { $table->dropPrimary(); } catch (\Throwable $e) {}
                $table->primary([$teamKey, $pivotPermission, $morphKey, 'model_type'], 'mhp_'.$teamKey.'_'.$pivotPermission.'_'.$morphKey.'_type_primary');

                if (DB::getDriverName() !== 'sqlite') {
                    $table->foreign($pivotPermission)
                        ->references('id')->on($tableNames['permissions'])->onDelete('cascade');
                }
            });
        }

        /**
         * 4) model_has_roles: tambah $teamKey + ganti primary
         *    PRIMARY: ($teamKey, role_id, model_id, model_type)
         */
        if (! Schema::hasColumn($tableNames['model_has_roles'], $teamKey)) {
            Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $teamKey, $pivotRole, $morphKey) {
                $table->unsignedBigInteger($teamKey)->nullable()->after($morphKey);
                $table->index($teamKey, 'mhr_'.$teamKey.'_index');

                if (DB::getDriverName() !== 'sqlite') {
                    try { $table->dropForeign([$pivotRole]); } catch (\Throwable $e) {}
                }

                try { $table->dropPrimary(); } catch (\Throwable $e) {}
                $table->primary([$teamKey, $pivotRole, $morphKey, 'model_type'], 'mhr_'.$teamKey.'_'.$pivotRole.'_'.$morphKey.'_type_primary');

                if (DB::getDriverName() !== 'sqlite') {
                    $table->foreign($pivotRole)
                        ->references('id')->on($tableNames['roles'])->onDelete('cascade');
                }
            });
        }

        /**
         * 5) role_has_permissions: tambah $teamKey + ganti primary
         *    PRIMARY: ($teamKey, permission_id, role_id)
         */
        if (! Schema::hasColumn($tableNames['role_has_permissions'], $teamKey)) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $teamKey, $pivotRole, $pivotPermission) {
                $table->unsignedBigInteger($teamKey)->nullable()->after($pivotPermission);
                $table->index($teamKey, 'rhp_'.$teamKey.'_index');

                try { $table->dropPrimary(); } catch (\Throwable $e) {}
                $table->primary([$teamKey, $pivotPermission, $pivotRole], 'rhp_'.$teamKey.'_'.$pivotPermission.'_'.$pivotRole.'_primary');
            });
        }

        // Clear cache permission spatie
        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $teams       = config('permission.teams');
        if (! $teams) return;

        $tableNames  = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamKey     = $columnNames['team_foreign_key'] ?? null;
        $pivotRole   = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $morphKey    = $columnNames['model_morph_key'] ?? 'model_id';

        if (! $teamKey) return;

        // role_has_permissions
        if (Schema::hasColumn($tableNames['role_has_permissions'], $teamKey)) {
            Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($teamKey, $pivotRole, $pivotPermission) {
                try { $table->dropPrimary('rhp_'.$teamKey.'_'.$pivotPermission.'_'.$pivotRole.'_primary'); } catch (\Throwable $e) {}
                // fallback primary lama
                $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_pkey');

                $table->dropIndex(['rhp_'.$teamKey.'_index']);
                $table->dropColumn($teamKey);
            });
        }

        // model_has_roles
        if (Schema::hasColumn($tableNames['model_has_roles'], $teamKey)) {
            Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teamKey, $pivotRole, $morphKey) {
                try { $table->dropPrimary('mhr_'.$teamKey.'_'.$pivotRole.'_'.$morphKey.'_type_primary'); } catch (\Throwable $e) {}
                $table->primary([$pivotRole, $morphKey, 'model_type'], 'model_has_roles_role_model_type_primary');

                $table->dropIndex(['mhr_'.$teamKey.'_index']);
                $table->dropColumn($teamKey);
            });
        }

        // model_has_permissions
        if (Schema::hasColumn($tableNames['model_has_permissions'], $teamKey)) {
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teamKey, $pivotPermission, $morphKey) {
                try { $table->dropPrimary('mhp_'.$teamKey.'_'.$pivotPermission.'_'.$morphKey.'_type_primary'); } catch (\Throwable $e) {}
                $table->primary([$pivotPermission, $morphKey, 'model_type'], 'model_has_permissions_permission_model_type_primary');

                $table->dropIndex(['mhp_'.$teamKey.'_index']);
                $table->dropColumn($teamKey);
            });
        }

        // roles
        if (Schema::hasColumn($tableNames['roles'], $teamKey)) {
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
                try { $table->dropUnique('roles_'.$teamKey.'_name_guard_unique'); } catch (\Throwable $e) {}
                $table->unique(['name','guard_name'], 'roles_name_guard_name_unique');

                $table->dropIndex(['roles_'.$teamKey.'_index']);
                $table->dropColumn($teamKey);
            });
        }

        // permissions
        if (Schema::hasColumn($tableNames['permissions'], $teamKey)) {
            Schema::table($tableNames['permissions'], function (Blueprint $table) use ($teamKey) {
                try { $table->dropUnique('permissions_'.$teamKey.'_name_guard_unique'); } catch (\Throwable $e) {}
                $table->unique(['name','guard_name'], 'permissions_name_guard_name_unique');

                $table->dropIndex(['permissions_'.$teamKey.'_index']);
                $table->dropColumn($teamKey);
            });
        }

        app('cache')
            ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
