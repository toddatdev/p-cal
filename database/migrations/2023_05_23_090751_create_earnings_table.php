<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
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
            $table->boolean('status')->default(0)->comment('0 = active, 1 = inactive');
            $table->boolean('is_del')->default(0)->comment('0 = not deleted, 1 = deleted');
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
        Schema::dropIfExists('earnings');
    }
};
