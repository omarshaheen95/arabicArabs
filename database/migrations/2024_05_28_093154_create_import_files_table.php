<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->text('other_data')->nullable();

            $table->string('original_file_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->enum('status', ['New', 'Uploading', 'Completed', 'Failures', 'Errors'])->default('New');

            $table->enum('process_type', ['create', 'update', 'delete'])->default('create');
            $table->enum('model_type', [class_basename(\App\Models\User::class), class_basename(\App\Models\Teacher::class)])->default(class_basename(\App\Models\User::class));
            $table->integer('created_rows_count')->default(0);
            $table->integer('updated_rows_count')->default(0);
            $table->integer('deleted_rows_count')->default(0);
            $table->integer('failed_rows_count')->default(0);

            $table->boolean('delete_with_rows')->default(0);
            $table->text('error')->nullable();
            $table->text('failures')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_files');
    }
}
