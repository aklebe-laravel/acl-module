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
    public function up()
    {
        if (!Schema::hasTable('acl_resources')) {
            Schema::create('acl_resources', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id')->nullable()->index();
                $table->foreign('parent_id')
                    ->references('id')
                    ->on($table->getTable())
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->string('code', 255)->nullable();
                $table->string('name', 255)->nullable();
                $table->string('description', 255)->nullable();

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('acl_groups')) {
            Schema::create('acl_groups', function (Blueprint $table) {
                $table->id();
                //            $table->string('code', 255)->nullable();
                $table->string('name', 255)->unique()->nullable();
                $table->string('description', 255)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('acl_group_acl_resource')) {
            Schema::create('acl_group_acl_resource', function (Blueprint $table) {
                $table->unsignedBigInteger('acl_group_id')->unsigned();
                $table->unsignedBigInteger('acl_resource_id')->unsigned();

                $table->unique(['acl_group_id', 'acl_resource_id']);
                $table->foreign('acl_group_id')
                    ->references('id')
                    ->on('acl_groups')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->foreign('acl_resource_id')
                    ->references('id')
                    ->on('acl_resources')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('acl_group_user')) {
            Schema::create('acl_group_user', function (Blueprint $table) {
                $table->unsignedBigInteger('acl_group_id')->unsigned();
                $table->unsignedBigInteger('user_id')->unsigned();

                $table->unique(['acl_group_id', 'user_id']);
                $table->foreign('acl_group_id')
                    ->references('id')
                    ->on('acl_groups')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acl_group_user');
        Schema::dropIfExists('acl_group_acl_resource');
        Schema::dropIfExists('acl_groups');
        Schema::dropIfExists('acl_resources');
    }

};
