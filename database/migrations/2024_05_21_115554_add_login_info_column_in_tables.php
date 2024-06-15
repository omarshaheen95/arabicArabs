<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoginInfoColumnInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('managers', function (Blueprint $table) {
            $table->text('last_login_info')->nullable()->after('last_login');
        });
        Schema::table('schools', function (Blueprint $table) {
            $table->text('last_login_info')->nullable()->after('last_login');
        });
        Schema::table('teachers', function (Blueprint $table) {
            $table->text('last_login_info')->nullable()->after('last_login');
        });
        Schema::table('supervisors', function (Blueprint $table) {
            $table->text('last_login_info')->nullable()->after('last_login');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->text('last_login_info')->nullable()->after('last_login');
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
            $table->dropColumn('last_login_info');
        });
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('last_login_info');
        });
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('last_login_info');
        });
        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropColumn('last_login_info');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login_info');
        });
    }
}
