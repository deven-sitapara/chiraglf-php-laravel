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
        Schema::table('extra_works', function (Blueprint $table) {
            // - New
            // - Edit
            // - Generate Extra Work,
            // - Upload 1,
            // - DS Report Upload
            // - Upload Search 2

            $table->string('ew_file_path')->nullable(); // Generate Extra Work
            $table->string('ew_file_url')->nullable(); // Generate Extra Work

            $table->string('search1_file_path')->nullable(); // Upload 1
            $table->string('search1_file_url')->nullable(); // Upload 1

            $table->string('ds_file_path')->nullable(); // DS Report Upload
            $table->string('ds_file_url')->nullable(); // DS Report Upload

            $table->string('search2_file_path')->nullable(); // Upload Search 2
            $table->string('search2_file_url')->nullable(); // Upload Search 2

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extra_works', function (Blueprint $table) {

            $table->dropColumn('ew_path');
            $table->dropColumn('ew_url');

            $table->dropColumn('search1_file_path');
            $table->dropColumn('search1_file_url');

            $table->dropColumn('ds_file_path');
            $table->dropColumn('ds_file_url');

            $table->dropColumn('search2_file_path');
            $table->dropColumn('search2_file_url');
        });
    }
};
