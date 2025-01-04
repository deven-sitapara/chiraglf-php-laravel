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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('branch');
            $table->string('file_number');
            $table->date('date');
            $table->string('company_name');
            $table->string('company_reference_number');
            $table->string('borrower_name');
            $table->string('proposed_owner_name');
            $table->text('property_descriptions');
            $table->enum('status', ['Login', 'Queries', 'Update', 'Handover', 'Close']);
            $table->string('status_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
