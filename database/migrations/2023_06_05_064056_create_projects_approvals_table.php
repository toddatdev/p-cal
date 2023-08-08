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
        Schema::create('projects_approvals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->unsigned()->index()->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->string('client_name')->nullable();
            $table->string('job_title')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('hourly_rate')->nullable();
            $table->bigInteger('sales_person_id')->unsigned()->index()->nullable();
            $table->foreign('sales_person_id')->references('id')->on('admin_settings')->onDelete('cascade');
            $table->bigInteger('type_id')->unsigned()->index()->nullable();
            $table->foreign('type_id')->references('id')->on('admin_settings')->onDelete('cascade');
            $table->bigInteger('platform_id')->unsigned()->index()->nullable();
            $table->foreign('platform_id')->references('id')->on('admin_settings')->onDelete('cascade');
            $table->bigInteger('requested_by')->unsigned()->index()->nullable();
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_requested_del')->default(0)->comment('0 = Not Requested, 1 = Requested to del');
            $table->boolean('is_approved')->default(0)->comment('0 = Pending, 1 = Not Approved, 2 = Approved');
            $table->boolean('is_read')->default(0)->comment('0 = No, 1 = Yes');
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
        Schema::dropIfExists('projects_approvals');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
