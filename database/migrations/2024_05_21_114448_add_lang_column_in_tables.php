<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLangColumnInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('managers', function (Blueprint $table) {
            $table->string('lang')->default('en')->after('active');
        });
        Schema::table('schools', function (Blueprint $table) {
            $table->string('lang')->default('en')->after('active');
        });
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('lang')->default('en')->after('active');
        });
        Schema::table('supervisors', function (Blueprint $table) {
            $table->string('lang')->default('en')->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('managers', function (Blueprint $table) {
            $table->dropColumn('lang');
        });
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('lang');
        });
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('lang');
        });
        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropColumn('lang');
        });
    }
}
