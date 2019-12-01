<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'widget_id',
		'area',
		'order',
		'type',
		'settings',
	];

	// This table does not use the eloquent timestamping feature
	public $timestamps = false;
}
