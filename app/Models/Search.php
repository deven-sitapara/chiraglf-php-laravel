<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{

    protected $table;
    protected $fillable;

    public function __construct(array $attributes = [])
    {
        // parent::__construct($attributes);

        $config = config('modelConfig.models.Search');

        $this->table = $config['table'];
        $this->fillable = $config['fillable'];
    }

    // belogs to
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
