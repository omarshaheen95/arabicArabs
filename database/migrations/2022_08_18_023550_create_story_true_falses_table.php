<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryTrueFalsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_true_falses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('story_question_id');
            $table->boolean('result')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('story_question_id')->references('id')->on('story_questions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('story_true_falses');
    }
}
