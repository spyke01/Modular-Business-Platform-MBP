<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
	protected $table = 'email_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sent', 'email_address', 'subject', 'message',
    ];
    
    // This table does not use the eloquent timestamping feature
    public $timestamps = false;
}
