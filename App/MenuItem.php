<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model {
	protected $table = 'menu_items';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'menu_id',
		'parent_id',
		'text',
		'icon',
		'rel',
		'link',
		'added_by',
		'prefix',
		'order',
		'role_ids',
	];

	// This table does not use the eloquent timestamping feature
	public $timestamps = false;

	public function menu() {
		return $this->belongsTo( 'App\Menu' );
	}

	public function parentMenuItem() {
		return $this->belongsTo( 'App\Menu', 'parent_id' );
	}
}
