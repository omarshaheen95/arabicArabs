<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('import_student_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->string('original_file_name');
            $table->string('file_name');
            $table->integer('created_rows_count')->default(0);
            $table->integer('updated_rows_count')->default(0);
            $table->integer('failed_rows_count')->default(0);
            $table->string('file_path');
            $table->enum('status', ['New', 'Uploading', 'Completed', 'Failures', 'Errors'])->default('New');
            $table->boolean('delete_with_user')->default(0);
            $table->boolean('with_abt_id')->default(0);
            $table->text('error')->nullable();
            $table->text('failures')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('school_id')->on('schools')->references('id')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_student_files');
    }
};
