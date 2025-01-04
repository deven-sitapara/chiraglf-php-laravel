<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraWork extends Model
{

    use HasFactory;

    protected $table = 'extra_works';
    protected $fillable = ['file_id', 'ew_number', 'date', 'customer_contact', 'email', 'work_details', 'total_amount', 'received_amount'];

    // CREATE TABLE "extra_works" ("id" integer primary key autoincrement not null, "file_id" integer not null, "ew_number" varchar not null, "date" date not null, "customer_contact" varchar not null, "email" varchar not null, "work_details" text not null, "total_amount" numeric not null, "received_amount" numeric not null, "created_at" datetime, "updated_at" datetime, foreign key("file_id") references "files"("id") on delete cascade)

    // belongs to file
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
