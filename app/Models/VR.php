<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VR extends Model
{

    protected $table;
    protected $fillable;

    public function __construct(array $attributes = [])
    {
        // parent::__construct($attributes);

        $config = config('modelConfig.models.VR');

        $this->table = $config['table'];
        $this->fillable = $config['fillable'];
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
