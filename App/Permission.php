<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'file', 'role_ids',
    ];
    
    // This table does not use the eloquent timestamping feature
    public $timestamps = false;
}
