<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameEmailToEmailsInCompaniesTable extends Migration
{
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('email', 'emails');
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('emails', 'email');
        });
    }
}
