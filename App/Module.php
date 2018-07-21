<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'developer', 'version', 'prefix', 'active',
    ];
    
    // This table does not use the eloquent timestamping feature
    public $timestamps = false;
}
