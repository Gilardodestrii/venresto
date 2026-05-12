<?php

// use Exception;
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
        $teams       = config('permission.teams');
        $tableNames  = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole       = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $teamKey     = $columnNames['team_foreign_key'] ?? null;
        $morphKey    = $columnNames['model_morph_key'] ?? 'model_id';

        throw_if(empty($tableNames), new \Exception('...'));
        throw_if($teams && empty($teamKey), new \Exception('...'));
        
        // permissions
        Schema::create($tableNames['permissions'], static function (Blueprint $table) use ($teams, $teamKey) {
            $table->bigIncrements('id');
            if ($teams || config('permission.testing')) {
                $table->unsignedBigInteger($teamKey)->nullable();
                $table->index($teamKey, 'permissions_team_foreign_key_index');
            }
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            if ($teams || config('permission.testing')) {
                $table->unique([$teamKey, 'name', 'guard_name'], 'permissions_team_name_guard_unique');
            } else {
                $table->unique(['name', 'guard_name'], 'permissions_name_guard_unique');
            }
        });

        // roles
        Schema::create($tableNames['roles'], static function (Blueprint $table) use ($teams, $columnNames) {
            $table->bigIncrements('id');
            if ($teams || config('permission.testing')) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
            }
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            if ($teams || config('permission.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name'], 'roles_team_name_guard_unique');
            } else {
                $table->unique(['name', 'guard_name'], 'roles_name_guard_unique');
            }
        });

        // model_has_permissions
        Schema::create($tableNames['model_has_permissions'], static function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission, $teams, $teamKey, $morphKey) {
            $table->unsignedBigInteger($pivotPermission);

            $table->string('model_type');
            $table->unsignedBigInteger($morphKey);
            $table->index([$morphKey, 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign($pivotPermission)
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            if ($teams) {
                $table->unsignedBigInteger($teamKey);
                $table->index($teamKey, 'model_has_permissions_team_foreign_key_index');

                $table->primary([$teamKey, $pivotPermission, $morphKey, 'model_type'],
                    'model_has_permissions_team_permission_model_type_primary');
            } else {
                $table->primary([$pivotPermission, $morphKey, 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            }
        });

        // model_has_roles
        Schema::create($tableNames['model_has_roles'], static function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $teams, $teamKey, $morphKey) {
            $table->unsignedBigInteger($pivotRole);

            $table->string('model_type');
            $table->unsignedBigInteger($morphKey);
            $table->index([$morphKey, 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign($pivotRole)
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            if ($teams) {
                $table->unsignedBigInteger($teamKey);
                $table->index($teamKey, 'model_has_roles_team_foreign_key_index');

                $table->primary([$teamKey, $pivotRole, $morphKey, 'model_type'],
                    'model_has_roles_team_role_model_type_primary');
            } else {
                $table->primary([$pivotRole, $morphKey, 'model_type'],
                    'model_has_roles_role_model_type_primary');
            }
        });

        // role_has_permissions
        Schema::create($tableNames['role_has_permissions'], static function (Blueprint $table) use ($tableNames, $pivotRole, $pivotPermission, $teams, $teamKey) {
            $table->unsignedBigInteger($pivotPermission);
            $table->unsignedBigInteger($pivotRole);

            $table->foreign($pivotPermission)
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign($pivotRole)
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            if ($teams) {
                $table->unsignedBigInteger($teamKey);
                $table->index($teamKey, 'role_has_permissions_team_foreign_key_index');
                $table->primary([$teamKey, $pivotPermission, $pivotRole],
                    'role_has_permissions_team_permission_role_primary');
            } else {
                $table->primary([$pivotPermission, $pivotRole],
                    'role_has_permissions_permission_role_primary');
            }
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
};
