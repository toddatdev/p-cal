<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_approval_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_approval_id')->unsigned()->index()->nullable();
            $table->foreign('project_approval_id')->references('id')->on('projects_approvals')->onDelete('cascade');
            $table->unsignedBigInteger('role_id')->unsigned()->index()->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->unsigned()->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_approved')->default(0)->comment('0 = Not Approved, 1 = Approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('project_approval_users');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
