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
        Schema::create('stop_earning_approvals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->unsigned()->index()->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('month');
            $table->string('year');
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
        Schema::dropIfExists('stop_earning_approvals');
    }
};
