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
        Schema::table('completed_projects', function (Blueprint $table) {
            $table->boolean('is_completed')->default(0)->comment('0 = Pending, 1 = Not Approved, 2 = Approved')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('completed_projects', function (Blueprint $table) {
            $table->dropColumn('is_completed');
        });
    }
};
