<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
				
    protected $fillable = [
    	'parent_id', 'name', 'type', 'color', 'tags', 'order', 'role_ids', 
    ];
	
	public function tickets() 
	{
		return $this->hasMany(Ticket::class);	
	}
    
    // This table does not use the eloquent timestamping feature
    public $timestamps = false;
	   
	/**
	 * Return the children for a category
	 */
	public function children() 
	{
		return $this->hasMany('App\Category', 'id', 'parent_id');	
	}
	   
	/**
	 * Return the children for a category
	 */
	public function parentCategory() 
	{
		return $this->belongsTo('App\Category', 'parent_id');	
	}
}
