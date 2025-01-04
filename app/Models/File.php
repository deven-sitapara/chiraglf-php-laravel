<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table;
    protected $fillable;

    public function __construct(array $attributes = [])
    {
        // parent::__construct($attributes);

        $config = config('modelConfig.models.File');

        $this->table = $config['table'];
        $this->fillable = $config['fillable'];
    }

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
