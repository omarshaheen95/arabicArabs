<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeadlineColumnToStoryAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('story_assignments', function (Blueprint $table) {
            $table->dateTime('deadline')->nullable()->after('completed');
            $table->dateTime('completed_at')->nullable()->after('deadline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('story_assignments', function (Blueprint $table) {
            $table->dropColumn('deadline');
            $table->dropColumn('completed_at');
        });
    }
}
