<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model {
	protected $table = 'email_templates';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'template_id',
		'name',
		'subject',
		'message',
		'added_by',
		'prefix',
	];

	// This table does not use the eloquent timestamping feature
	public $timestamps = false;
}
