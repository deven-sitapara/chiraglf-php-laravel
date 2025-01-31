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
        Schema::table('vrs', function (Blueprint $table) {
            // - New
            // - Edit
            // - Generate VR / Open
            // - Add Queries
            // - DS Report Upload

            $table->string('vr_file_path')->nullable(); // Generate VR / Open
            $table->string('vr_file_url')->nullable(); // Generate VR / Open

            $table->string('ds_file_path')->nullable(); // DS Report Upload
            $table->string('ds_file_url')->nullable(); // DS Report Upload

            $table->string('query')->nullable(); // Query


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vrs', function (Blueprint $table) {
            //
        });
    }
};
