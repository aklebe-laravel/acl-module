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
        if (!Schema::hasColumn('users', 'is_enabled')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_enabled')->default(true)->after('id')->comment('System based switch to ban user');
                $table->boolean('is_deleted')->default(false)->after('is_enabled')->comment('User deleted, data were anonymized');
                $table->string('shared_id', 255)->unique()->nullable()->after('email');
                $table->mediumText('options')->nullable()->after('shared_id')->comment('json data like socials');
                $table->timestamp('order_to_delete_at')->nullable()->after('remember_token')->comment('the time the termination has ordered');
                $table->timestamp('last_visited_at')->nullable()->after('email_verified_at');

                // LDAP ID
                $table->string('guid')->unique()->nullable()->after('shared_id')->comment('ldap id');
                // source where user come from (local, ldap with domain, ...)
                $table->string('domain')->default('local')->after('id')->comment('user domain in origin');
                // Password needs to be optional for ldap
                $table->string('password')->nullable()->change();

                $table->unique(['guid', 'domain']);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password');
            $table->dropColumn('domain');
            $table->dropColumn('guid');
            $table->dropColumn('last_visited_at');
            $table->dropColumn('shared_id');
            $table->dropColumn('order_to_delete_at');
            $table->dropColumn('is_enabled');
            $table->dropColumn('is_deleted');
            $table->dropColumn('options');
        });
    }

};
