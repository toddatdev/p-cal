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
        Schema::create('earning_approvals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('earning_id')->unsigned()->index()->nullable();
            $table->foreign('earning_id')->references('id')->on('earnings')->onDelete('cascade');
            $table->bigInteger('project_id')->unsigned()->index()->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->integer('earning')->nullable();
            $table->string('year')->nullable();
            $table->string('month')->nullable();
            $table->integer('exg_rate')->nullable();
            $table->string('employee_commission')->nullable()->default(0);
            $table->string('manager_commission')->nullable()->default(0);
            $table->string('hod_commission')->nullable()->default(0);
            $table->string('employee_commission_by_exg_rate')->nullable()->default(0);
            $table->string('manager_commission_by_exg_rate')->nullable()->default(0);
            $table->string('hod_commission_by_exg_rate')->nullable()->default(0);
            $table->string('currency')->nullable();
            $table->bigInteger('requested_by')->unsigned()->index()->nullable();
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_requested_del')->default(0)->comment('0 = Not Requested, 1 = Requested to del');
            $table->boolean('is_approved')->default('0')->comment('0 = Pending, 1 = Not Approved, 2 = Approved');
            $table->boolean('is_read')->default('0')->comment('0 = No, 1 = Yes');
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
        Schema::dropIfExists('earning_approvals');
    }
};
