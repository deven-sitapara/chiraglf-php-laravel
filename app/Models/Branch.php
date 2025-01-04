<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{

    protected $table;
    protected $fillable;

    public function __construct(array $attributes = [])
    {
        // parent::__construct($attributes);

        $config = config('modelConfig.models.Branch');

        $this->table = $config['table'];
        $this->fillable = $config['fillable'];
    }


    public function users()
    {
        return $this->hasMany(User::class);
    }
}
