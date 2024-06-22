<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImportFileIdInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['import_student_file_id']);
            $table->dropColumn('import_student_file_id');

            $table->unsignedBigInteger('import_file_id')->nullable()->after('last_login_info');
            $table->foreign('import_file_id')->on('import_files')->references('id')->cascadeOnDelete();
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('import_file_id')->nullable()->after('last_login_info');
            $table->foreign('import_file_id')->on('import_files')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('import_file_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('import_file_id');
        });
    }
}
