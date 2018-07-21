<?php

namespace modules\FTS\Controllers;

class TestController {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		echo 'woot';
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		echo 'indexed';
	}
}
