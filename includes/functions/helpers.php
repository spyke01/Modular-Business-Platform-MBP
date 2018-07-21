<?php
/***************************************************************************
 *                               helpers.php
 *                            -------------------
 *   begin                : Feb 17, 2017
 *   copyright            : (C) 2017 Paden Clayton, LLC
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


use App\Support\Registry;
use App\View\View;

if (! function_exists('view')) {
	/**
	 * Add the given view data to the current view
	 *
	 * @param  string  $name
	 * @param  array   $data
	 */
	function view($name = null, $data = [])
	{

		// Create our view if needed
		if ( Registry::contains('view') === false ) {
			$view = new View; // Needed for our views
			Registry::add( $view, 'view' ); // Store this in the registry so our views can access it
		}

		Registry::get('view')->addView($name, $data);
	}
}
if (! function_exists('returnView')) {
	/**
	 * Add the given view data to the current view
	 *
	 * @param  string  $name
	 * @param  array   $data
	 */
	function returnView($name = null, $data = [])
	{

		// Create our view if needed
		if ( Registry::contains('view') === false ) {
			$view = new View; // Needed for our views
			Registry::add( $view, 'view' ); // Store this in the registry so our views can access it
		}

		return Registry::get('view')->returnView($name, $data);
	}
}
if (! function_exists('redirect')) {
	/**
	 * Allows us to redirect to another page.
	 *
	 * @param  string  $url
	 * @param  array   $data
	 */
	function redirect($url = null, $data = [])
	{

		header("Location:$url?" . http_build_query($data) );
	}
}
