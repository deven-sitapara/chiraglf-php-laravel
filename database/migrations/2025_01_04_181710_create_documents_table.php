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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_number')->unique();
            $table->foreignId('file_id')->constrained()->onDelete('cascade')->restrictOnUpdate();
            $table->date('date');
            $table->enum('type', ['MOD', 'Release Deed', 'Sale Deed', 'Declaration Deed', 'Rectification Deed', 'Other Documents']);
            $table->string('executing_party_name');
            $table->string('executing_party_mobile');
            $table->string('contact_person');
            $table->string('contact_person_mobile');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
