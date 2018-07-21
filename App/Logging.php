<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
	protected $table = 'logging';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created', 'type', 'assoc_id2', 'assoc_id3', 'message', 'start', 'stop',
    ];
    
    // This table does not use the eloquent timestamping feature
    public $timestamps = false;
}
