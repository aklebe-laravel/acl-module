<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'origin')) {
            Schema::table('users', function (Blueprint $table) {
                // source where group come from (local, ldap with domain, ...)
                $table->string('origin')
                    ->default('local')
                    ->after('id')
                    ->comment('user origin');
                $table->unique(['guid', 'origin', 'domain']);
            });
        }
        if (!Schema::hasColumn('acl_groups', 'origin')) {
            Schema::table('acl_groups', function (Blueprint $table) {
                // source where group come from (local, ldap with domain, ...)
                $table->string('origin')
                    ->default('local')
                    ->after('id')
                    ->comment('group origin');
                $table->string('domain')
                    ->default('local')
                    ->after('origin')
                    ->comment('group domain in origin');
                $table->dropUnique(['name']);
                $table->unique(['name', 'origin', 'domain']);
            });
        }
        if (!Schema::hasColumn('acl_resources', 'origin')) {
            Schema::table('acl_resources', function (Blueprint $table) {
                // source where resource come from (local, ldap with domain, ...)
                $table->string('origin')
                    ->default('local')
                    ->after('id')
                    ->comment('group origin');
                $table->string('domain')
                    ->default('local')
                    ->after('origin')
                    ->comment('group domain in origin');
                $table->unique(['code', 'origin', 'domain']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('acl_resources', function (Blueprint $table) {
            $table->dropColumn('domain');
            $table->dropColumn('origin');
        });
        Schema::table('acl_groups', function (Blueprint $table) {
            $table->dropColumn('domain');
            $table->dropColumn('origin');
        });
    }

};
