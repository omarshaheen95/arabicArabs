<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');

            $table->string('password');
            $table->string('mobile')->nullable();

            $table->unsignedBigInteger('school_id');



            $table->integer('grade');
            $table->integer('alternate_grade')->nullable();



            $table->string('year_learning')->default(0);
            $table->string('section')->nullable();

            $table->string('country_code')->nullable();
            $table->string('short_country')->nullable();

            $table->boolean('active')->default(1);
            $table->enum('type', ['trial', 'member'])->default('trial');

            $table->dateTime('active_from');
            $table->dateTime('active_to');



            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('manager_id');
            $table->unsignedBigInteger('year_id');

            $table->rememberToken();
            $table->dateTime('last_login')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('package_id')->references('id')->on('packages')->cascadeOnDelete();
            $table->foreign('manager_id')->references('id')->on('managers')->cascadeOnDelete();
            $table->foreign('year_id')->references('id')->on('years')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
