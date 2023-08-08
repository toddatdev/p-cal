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
        Schema::create('project_commissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->unsigned()->index()->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->float('commission_percentage_employee')->default(0);
            $table->float('commission_percentage_manager')->default(0);
            $table->float('commission_percentage_hod')->default(0);
            $table->boolean('status')->default(0)->comment('0 = Active, 1 = Inactive');
            $table->boolean('is_del')->default('0')->comment('0 = Not deleted, 1 = Deleted');
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
        Schema::dropIfExists('project_commissions');
    }
};
