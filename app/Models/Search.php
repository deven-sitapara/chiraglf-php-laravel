<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    use HasFactory;


    protected $table = 'searches';
    protected $fillable = ['file_id', 'search_number', 'date', 'years_required'];

    // CREATE TABLE "searches" ("id" integer primary key autoincrement not null, "file_id" integer not null, "search_number" varchar not null, "date" date not null, "years_required" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("file_id") references "files"("id") on delete cascade)

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($search) {
            if (!$search->search_number) {
                $search->search_number = $search->file_id . '-SR-' . (Search::where('file_id', $search->file_id)?->count() + 1);
            }
        });
    }

    // belogs to
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
