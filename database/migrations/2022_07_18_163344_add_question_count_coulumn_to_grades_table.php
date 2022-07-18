<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionCountCoulumnToGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->tinyInteger('true_false')->after('grammar')->default(0);
            $table->tinyInteger('choose')->after('true_false')->default(0);
            $table->tinyInteger('match')->after('choose')->default(0);
            $table->tinyInteger('sort')->after('match')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn('true_false');
            $table->dropColumn('choose');
            $table->dropColumn('match');
            $table->dropColumn('sort');
        });
    }
}
