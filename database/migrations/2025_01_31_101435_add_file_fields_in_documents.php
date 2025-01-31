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

            $table->string('rr_file_path')->nullable();
            $table->string('rr_file_url')->nullable();

            $table->string('stamp_duty_file_path')->nullable(); // Stamp Duty Upload
            $table->string('stamp_duty_file_url')->nullable(); // Stamp Duty Upload

            $table->string('token_file_path')->nullable(); // Token File Upload
            $table->string('token_file_url')->nullable(); // Token File Upload

            $table->string('appointment_file_url')->nullable(); // Appointment File Upload
            $table->string('appointment_file_path')->nullable(); // Appointment File Upload

            $table->string('reappointment_file_path')->nullable(); // ReAppointment File Upload
            $table->string('reappointment_file_url')->nullable(); // ReAppointment File Upload

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->dropColumn('rr_file_path');
            $table->dropColumn('rr_file_url');

            $table->dropColumn('stamp_duty_file_path'); // Stamp Duty Upload
            $table->dropColumn('stamp_duty_file_url'); // Stamp Duty Upload

            $table->dropColumn('token_file_path'); // Token File Upload
            $table->dropColumn('token_file_url'); // Token File Upload

            $table->dropColumn('appointment_file_path'); // Appointment File Upload
            $table->dropColumn('appointment_file_url'); // Appointment File Upload

            $table->dropColumn('reappointment_file_path'); // ReAppointment File Upload
            $table->dropColumn('reappointment_file_url'); // ReAppointment File Upload




        });
    }
};
