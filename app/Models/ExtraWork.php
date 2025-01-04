<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraWork extends Model
{

    protected $table;
    protected $fillable;

    public function __construct(array $attributes = [])
    {
        // parent::__construct($attributes);

        $config = config('modelConfig.models.ExtraWork');

        $this->table = $config['table'];
        $this->fillable = $config['fillable'];
    }

    // belongs to file
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
