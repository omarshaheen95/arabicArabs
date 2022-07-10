<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_question_id');
            $table->string('content');
            $table->boolean('result')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('t_question_id')->references('id')->on('t_questions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_options');
    }
}
