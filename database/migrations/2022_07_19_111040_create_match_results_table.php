<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_test_id')->nullable();
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('match_id');
            $table->unsignedBigInteger('result_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_test_id')->references('id')->on('user_tests')->cascadeOnDelete();
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnDelete();
            $table->foreign('match_id')->references('id')->on('matches')->cascadeOnDelete();
            $table->foreign('result_id')->references('id')->on('matches')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_results');
    }
}
