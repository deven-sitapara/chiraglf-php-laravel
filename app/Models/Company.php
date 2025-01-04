<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $table;
    protected $fillable;

    public function __construct(array $attributes = [])
    {
        // parent::__construct($attributes);

        $config = config('modelConfig.models.Company');

        $this->table = $config['table'];
        $this->fillable = $config['fillable'];
    }
}
