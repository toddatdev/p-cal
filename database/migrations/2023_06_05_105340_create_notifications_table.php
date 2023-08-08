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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->unsigned()->index()->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->bigInteger('project_approval_id')->unsigned()->index()->nullable();
            $table->foreign('project_approval_id')->references('id')->on('projects_approvals')->onDelete('cascade');
            $table->bigInteger('notification_by')->unsigned()->index()->nullable();
            $table->foreign('notification_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('notification_for')->unsigned()->index()->nullable();
            $table->foreign('notification_for')->references('id')->on('users')->onDelete('cascade');
            $table->string('message');
            $table->string('is_read')->default(0)->comment('0 = No, 1 = Yes');
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
        Schema::dropIfExists('notifications');
    }
};
