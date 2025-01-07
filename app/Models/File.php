<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    // CREATE TABLE "files" ("id" integer primary key autoincrement not null, "file_number" varchar not null, "date" date not null, "company_reference_number" varchar not null, "borrower_name" varchar not null, "proposed_owner_name" varchar not null, "property_descriptions" text not null, "status" varchar not null, "status_message" varchar, "created_at" datetime, "updated_at" datetime, "branch_id" integer not null, "company_id" integer not null, foreign key("branch_id") references "branches"("id"), foreign key("company_id") references "companies"("id"))

    protected $fillable = [
        'branch_id',
        'company_id',
        'file_number',
        'date',
        'company_reference_number',
        'borrower_name',
        'proposed_owner_name',
        'property_descriptions',
        'status',
        'status_message',
    ];

    const STATUS_OPTIONS = [
        'login' => 'Login',
        'queries' => 'Queries',
        'update' => 'Update',
        'handover' => 'Handover',
        'close' => 'Close',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $file->file_number = (int) str_pad($file->id ?? (static::max('id') + 1), 5, '0', STR_PAD_LEFT);
            $file->status = 'login';
        });
    }

    // belongs to company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // belongs to branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
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
