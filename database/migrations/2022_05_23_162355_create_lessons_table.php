<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('content')->nullable();
            $table->unsignedBigInteger('grade_id');
            $table->enum('lesson_type', ['reading', 'writing', 'listening', 'speaking', 'grammar', 'dictation', 'rhetoric']);
            $table->enum('section_type', ['informative', 'literary'])->nullable();
            $table->string('color')->nullable();
            $table->integer('ordered')->default(1);
            $table->integer('success_mark')->default(60);
            $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('grade_id')->references('id')->on('grades')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
