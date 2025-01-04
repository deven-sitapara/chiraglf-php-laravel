<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TSR extends Model
{

    use HasFactory;

    protected $table = 'tsrs';
    protected $fillable = ['file_id', 'tsr_number', 'date'];
    // CREATE TABLE "tsrs" ("id" integer primary key autoincrement not null, "file_id" integer not null, "tsr_number" varchar not null, "date" date not null, "created_at" datetime, "updated_at" datetime, foreign key("file_id") references "files"("id") on delete cascade)



    //belongs to file
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
