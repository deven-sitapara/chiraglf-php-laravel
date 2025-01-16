<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{

    use HasFactory;

    protected $table = 'documents';
    protected $fillable = ['document_number', 'file_id', 'date', 'type', 'executing_party_name', 'executing_party_mobile', 'contact_person', 'contact_person_mobile'];

    // CREATE TABLE "documents" ("id" integer primary key autoincrement not null, "document_number" varchar not null, "file_id" integer not null, "date" date not null, "type" varchar check ("type" in ('MOD', 'Release Deed', 'Sale Deed', 'Declaration Deed', 'Rectification Deed', 'Other Documents')) not null, "executing_party_name" varchar not null, "executing_party_mobile" varchar not null, "contact_person" varchar not null, "contact_person_mobile" varchar not null, "created_at" datetime, "updated_at" datetime, foreign key("file_id") references "files"("id") on delete cascade)

    // belongs to file
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
