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
        Schema::table('tsrs', function (Blueprint $table) {
            // add file_id and file_url columns
            $table->string('tsr_file_id')->nullable();
            $table->string('tsr_file_url')->nullable();

            $table->string('search1_file_id')->nullable();
            $table->string('search1_file_url')->nullable();

            $table->string('search2_file_id')->nullable();
            $table->string('search2_file_url')->nullable();

            $table->string('ds_file_id')->nullable();
            $table->string('ds_file_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tsrs', function (Blueprint $table) {
            $table->dropColumn('tsr_file_id');
            $table->dropColumn('tsr_file_url');

            $table->dropColumn('search1_file_id');
            $table->dropColumn('search1_file_url');

            $table->dropColumn('search2_file_id');
            $table->dropColumn('search2_file_url');

            $table->dropColumn('ds_file_id');
            $table->dropColumn('ds_file_url');
        });
    }
};
