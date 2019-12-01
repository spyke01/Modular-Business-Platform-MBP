<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
	];

	// This table does not use the eloquent timestamping feature
	public $timestamps = false;
}
