<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table;
    protected $fillable = ['name',  'emails',   'tsr_fee', 'vr_fee', 'document_fee', 'bt_fee'];

    // CREATE TABLE "companies" ("id" integer primary key autoincrement not null, "name" varchar not null, "emails" varchar not null, "tsr_fee" numeric not null, "vr_fee" numeric not null, "document_fee" numeric not null, "bt_fee" numeric not null, "tsr_file_format" varchar, "document_file_format" varchar, "vr_file_format" varchar, "search_file_format" varchar, "ew_file_format" varchar, "created_at" datetime, "updated_at" datetime)

}
