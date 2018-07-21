<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active', 'user_level', 'username', 'password', 'first_name', 'last_name', 
        'email_address', 'website', 'company', 'title', 'phone_number', 
        'facebook', 'twitter', 'google_plus', 'pinterest', 'instagram', 'linkedin', 
        'signup_date', 'token_activation', 'token_password_reset', 'token_date', 'notes',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    
    // This table does not use the eloquent timestamping feature
    public $timestamps = false;
	   
	/**
	 * Return the notifications for a user
	 */
	public function notifications() 
	{
		return $this->hasMany('App\Notification');	
	}
	
}
