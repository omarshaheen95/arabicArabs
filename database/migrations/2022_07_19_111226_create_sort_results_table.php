<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSortResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sort_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_test_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('sort_word_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_test_id')->references('id')->on('user_tests')->cascadeOnDelete();
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnDelete();
            $table->foreign('sort_word_id')->references('id')->on('sort_words')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sort_results');
    }
}
