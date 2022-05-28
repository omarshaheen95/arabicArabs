<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupervisorTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervisor_teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supervisor_id');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supervisor_id')->references('id')->on('supervisors')->cascadeOnDelete();
            $table->foreign('teacher_id')->references('id')->on('teachers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supervisor_teachers');
    }
}
