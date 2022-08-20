<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('story_id');
            $table->text('content');
            $table->string('attachment')->nullable();
            $table->enum('type', [1,2,3,4])->default(1)->comment('1:true&false, 2:choose, 3:match, 4:sortWords');
            $table->float('mark')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('story_id')->references('id')->on('stories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('story_questions');
    }
}
