<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('branch');
            $table->dropColumn('company_name');
            $table->foreignId('branch_id')->constrained();
            $table->foreignId('company_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('branch');
            $table->string('company_name');
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['company_id']);
            $table->dropColumn(['branch_id', 'company_id']);
        });
    }
};
