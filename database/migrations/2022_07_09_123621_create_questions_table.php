<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->text('content')->nullable();
            $table->enum('type', [1,2,3,4,5,6])->default(1)->comment('1:true&false, 2:choose, 3:match, 4:sortWords, 5:writing, 6:listening');
            $table->tinyInteger('mark')->default(1);
            $table->string('attachment')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lesson_id')->references('id')->on('lessons')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
