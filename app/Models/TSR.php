<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TSR extends Model
{

    use HasFactory;

    protected $table = 'tsrs';
    protected $fillable = [
        'file_id',
        'tsr_number',
        'date'
    ];
    // CREATE TABLE "tsrs" ("id" integer primary key autoincrement not null, "file_id" integer not null, "tsr_number" varchar not null, "date" date not null, "created_at" datetime, "updated_at" datetime, foreign key("file_id") references "files"("id") on delete cascade)

    protected $casts = [
        'date' => 'date',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tsr) {
            if (!$tsr->tsr_number) {
                $tsr->tsr_number = '' . $tsr->file_id . '-TS-' . (static::count() + 1);
            }
        });
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
