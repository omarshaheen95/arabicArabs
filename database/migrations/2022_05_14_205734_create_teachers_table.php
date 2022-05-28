<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->unsignedBigInteger('school_id');

            $table->string('mobile');

            $table->integer('pending_tasks')->default(0);
            $table->integer('corrected_tasks')->default(0);
            $table->integer('returned_tasks')->default(0);
            $table->integer('passed_tests')->default(0);
            $table->integer('failed_tests')->default(0);

            $table->boolean('approved')->default(0);
            $table->boolean('active')->default(0);

            $table->rememberToken();
            $table->dateTime('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('teachers');
    }
}
