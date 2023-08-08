<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->unsigned()->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('roles')->onDelete('cascade');
            $table->string('permission_ids')->nullable();
            $table->string('name');
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
        Schema::dropIfExists('roles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
