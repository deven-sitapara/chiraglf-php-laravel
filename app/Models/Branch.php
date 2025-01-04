<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{

    use HasFactory;

    protected $table = 'branches';
    protected $fillable = ['branch_name', 'person_name', 'address', 'contact_number', 'email'];

    // CREATE TABLE "branches" ("id" integer primary key autoincrement not null, "branch_name" varchar not null, "person_name" varchar not null, "address" text not null, "contact_number" varchar not null, "email" varchar not null, "created_at" datetime, "updated_at" datetime)


    public function users()
    {
        return $this->hasMany(User::class);
    }
}
