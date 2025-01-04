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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->decimal('tsr_fee', 10, 2);
            $table->decimal('vr_fee', 10, 2);
            $table->decimal('document_fee', 10, 2);
            $table->decimal('bt_fee', 10, 2);
            $table->string('tsr_file_format')->nullable();
            $table->string('document_file_format')->nullable();
            $table->string('vr_file_format')->nullable();
            $table->string('search_file_format')->nullable();
            $table->string('ew_file_format')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
