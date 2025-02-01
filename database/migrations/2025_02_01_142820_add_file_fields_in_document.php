<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->string('document_file_path')->nullable(); // Generate Document / Open
            $table->string('document_file_url')->nullable(); // Generate Document / Open

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // remove
            $table->deleteColumn('document_file_path');
            $table->deleteColumn('document_file_url');
        });
    }
};
