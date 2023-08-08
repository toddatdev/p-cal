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
        Schema::create('project_approval_commissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('commission_id')->unsigned()->index()->nullable();
            $table->foreign('commission_id')->references('id')->on('project_commissions')->onDelete('cascade');
            $table->bigInteger('project_id')->unsigned()->index()->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->bigInteger('project_approval_id')->unsigned()->index()->nullable();
            $table->foreign('project_approval_id')->references('id')->on('projects_approvals')->onDelete('cascade');
            $table->float('commission_percentage_employee')->default(0);
            $table->float('commission_percentage_manager')->default(0);
            $table->float('commission_percentage_hod')->default(0);
            $table->bigInteger('requested_by')->unsigned()->index()->nullable();
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_approved')->default(0)->comment('0 = Pending, 1 = Not Approved, 2 = Approved');
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
        Schema::dropIfExists('project_approval_commissions');
    }
};
