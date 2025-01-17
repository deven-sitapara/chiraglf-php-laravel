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
            $table->dropUnique('extra_works_ew_number_unique'); // Replace with the correct index name if different
            $table->dropColumn('ew_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extra_works', function (Blueprint $table) {
            $table->string('ew_number')->unique();
        });
    }
};
