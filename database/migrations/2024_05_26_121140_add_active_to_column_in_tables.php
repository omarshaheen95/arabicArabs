<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveToColumnInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dateTime('active_to')->nullable()->after('last_login');
        });
        Schema::table('supervisors', function (Blueprint $table) {
            $table->dateTime('active_to')->nullable()->after('last_login');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('active_to');
        });
        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropColumn('active_to');
        });
    }
}
