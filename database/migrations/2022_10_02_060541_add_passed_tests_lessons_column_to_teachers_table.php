<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPassedTestsLessonsColumnToTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->integer('passed_tests_lessons')->default(0)->after('failed_tests');
            $table->integer('failed_tests_lessons')->default(0)->after('passed_tests_lessons');
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
            $table->dropColumn('passed_tests_lessons');
            $table->dropColumn('failed_tests_lessons');
        });
    }
}
