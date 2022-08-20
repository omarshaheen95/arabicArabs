<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorySortWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_sort_words', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('story_question_id');
            $table->string('content');
            $table->tinyInteger('ordered')->default(1);
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
        Schema::dropIfExists('story_sort_words');
    }
}
