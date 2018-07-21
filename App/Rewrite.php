<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rewrite extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'match', 'query', 'added_by', 'prefix',
    ];
    
    // This table does not use the eloquent timestamping feature
    public $timestamps = false;
}
