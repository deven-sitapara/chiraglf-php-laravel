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

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($vr) {
            if (!$vr->vr_number) {
                $vr->vr_number = $vr->file_id . '-VR-' . (VR::where('file_id', $vr->file_id)?->count() + 1);
            }
        });
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
