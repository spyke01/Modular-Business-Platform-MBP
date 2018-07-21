<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'added_by', 'prefix',
    ];
    
    // This table does not use the eloquent timestamping feature
    public $timestamps = false;
	   
	/**
	 * Return the notifications for a user
	 */
	public function items() 
	{
		return $this->hasMany('App\MenuItem');	
	}
}
