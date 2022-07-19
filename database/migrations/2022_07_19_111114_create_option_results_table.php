<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_test_id')->nullable();
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('option_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_test_id')->references('id')->on('user_tests')->cascadeOnDelete();
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnDelete();
            $table->foreign('option_id')->references('id')->on('options')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_results');
    }
}
