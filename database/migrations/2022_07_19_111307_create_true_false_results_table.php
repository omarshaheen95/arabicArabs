<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrueFalseResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('true_false_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_test_id');
            $table->unsignedBigInteger('question_id');
            $table->boolean('result');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_test_id')->references('id')->on('user_tests')->cascadeOnDelete();
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('true_false_results');
    }
}
