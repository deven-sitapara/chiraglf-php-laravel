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
        Schema::table('searches', function (Blueprint $table) {
            // - New
            // - Edit
            // - Generate Search
            // - Search 1 Upload
            // - DS Report Upload

            $table->string('search_path')->nullable(); // Generate Search
            $table->string('search_url')->nullable(); // Generate Search

            $table->string('search1_file_path')->nullable(); // Search 1 Upload
            $table->string('search1_file_url')->nullable(); // Search 1 Upload

            $table->string('ds_file_path')->nullable(); // DS Report Upload
            $table->string('ds_file_url')->nullable(); // DS Report Upload



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('searches', function (Blueprint $table) {
            // drop fields

            $table->dropColumn('search_path');
            $table->dropColumn('search_url');
            $table->dropColumn('search1_file_path');
            $table->dropColumn('search1_file_url');
            $table->dropColumn('ds_file_path');
            $table->dropColumn('ds_file_url');
        });
    }
};
