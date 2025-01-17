<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BT extends Model
{

    use HasFactory;

    protected $table = 'bts';
    protected $fillable = ['file_id', 'bt_number', 'date', 'status', 'status_message'];

    //CREATE TABLE "bts" ("id" integer primary key autoincrement not null, "file_id" integer not null, "bt_number" varchar not null, "date" date not null, "status" varchar check ("status" in ('Login', 'Check Deposit', 'Paper Collection')) not null, "status_message" varchar, "created_at" datetime, "updated_at" datetime, foreign key("file_id") references "files"("id") on delete cascade)


    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($bt) {
            if (!$bt->bt_number) {
                $bt->bt_number = $bt->file_id . '-BT-' . (BT::where('file_id', $bt->file_id)?->count() + 1);
            }
        });
    }

    // belongs to file
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
