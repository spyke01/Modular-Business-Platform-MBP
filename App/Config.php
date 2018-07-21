<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
	protected $table = 'config';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value',
    ];
    
    // This table does not use the eloquent timestamping feature
    public $timestamps = false;
}
