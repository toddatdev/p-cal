<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('job_title');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('hourly_rate');
            $table->bigInteger('sales_person_id')->unsigned()->index()->nullable();
            $table->foreign('sales_person_id')->references('id')->on('admin_settings')->onDelete('cascade');
            $table->bigInteger('type_id')->unsigned()->index()->nullable();
            $table->foreign('type_id')->references('id')->on('admin_settings')->onDelete('cascade');
            $table->bigInteger('platform_id')->unsigned()->index()->nullable();
            $table->foreign('platform_id')->references('id')->on('admin_settings')->onDelete('cascade');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('projects');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
