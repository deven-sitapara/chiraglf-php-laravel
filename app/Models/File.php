<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $fillable = ['branch', 'file_number', 'date', 'company_name', 'company_reference_number', 'borrower_name', 'proposed_owner_name', 'property_descriptions', 'status', 'status_message'];

    // CREATE TABLE "files" ("id" integer primary key autoincrement not null, "branch" varchar not null, "file_number" varchar not null, "date" date not null, "company_name" varchar not null, "company_reference_number" varchar not null, "borrower_name" varchar not null, "proposed_owner_name" varchar not null, "property_descriptions" text not null, "status" varchar check ("status" in ('Login', 'Queries', 'Update', 'Handover', 'Close')) not null, "status_message" varchar, "created_at" datetime, "updated_at" datetime)

    // belongs to company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    //has many tsrs
    public function tsrs()
    {
        return $this->hasMany(TSR::class);
    }
    //has many vrs
    public function vrs()
    {
        return $this->hasMany(VR::class);
    }
    //has many searches
    public function searches()
    {
        return $this->hasMany(Search::class);
    }
    //has many extra works
    public function extraWorks()
    {
        return $this->hasMany(ExtraWork::class);
    }
    // has many documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
