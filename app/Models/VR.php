<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VR extends Model
{

    use HasFactory;

    protected $table = 'vrs';
    protected $fillable = ['file_id', 'vr_number', 'date'];

    // CREATE TABLE "vrs" ("id" integer primary key autoincrement not null, "file_id" integer not null, "vr_number" varchar not null, "date" date not null, "created_at" datetime, "updated_at" datetime, foreign key("file_id") references "files"("id") on delete cascade)

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
