<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtraWork extends Model
{

    use HasFactory;

    protected $table = 'extra_works';
    protected $fillable = ['file_id', 'ew_number', 'date', 'customer_contact', 'email', 'work_details', 'total_amount', 'received_amount'];

    // CREATE TABLE "extra_works" ("id" integer primary key autoincrement not null, "file_id" integer not null, "ew_number" varchar not null, "date" date not null, "customer_contact" varchar not null, "email" varchar not null, "work_details" text not null, "total_amount" numeric not null, "received_amount" numeric not null, "created_at" datetime, "updated_at" datetime, foreign key("file_id") references "files"("id") on delete cascade)

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($extra_work) {
            if (!$extra_work->extra_work_number) {
                $extra_work->extra_work_number = $extra_work->file_id . '-EW-' . (ExtraWork::where('file_id', $extra_work->file_id)?->count() + 1);
            }
        });
    }

    // belongs to file
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
