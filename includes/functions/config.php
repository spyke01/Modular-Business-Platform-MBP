<?php 
/***************************************************************************
 *                               config.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//==================================================
// Loads the config values from the database
//==================================================
function load_config_values() {
	global $ftsdb, $mbp_config; 
	
	$result = $ftsdb->select(DBTABLEPREFIX . "config");
	if ($result) {
		foreach ($result as $row) {
			$mbp_config[$row['name']] = $row['value'];
		}
		$result = NULL;
	}
}

//==================================================
// Checks if a config setting is in the database
//==================================================
function config_value_exists($name) {
	global $ftsdb; 
	
	$exists = 0;
	$results = $ftsdb->select(DBTABLEPREFIX . "config", "name = :name", array(
		":name" => $name
	));
	if ( $results && count( $results ) > 0 ) { $exists = 1; }
	$results = NULL;
	
	return $exists;
}

//==================================================
// Adds a config setting in the database
//==================================================
function add_config_value($name, $value) {
	global $ftsdb, $mbp_config; 
	
	if ( config_value_exists($name) ) {
		update_config_value($name, $value);
	} else {
		$result = $ftsdb->insert(DBTABLEPREFIX . 'config', array(
			"name" => $name,
			"value" => $value
		));
		$mbp_config[$name] = $value;
	}
}

//==================================================
// Deletes a config setting from the database
//==================================================
function delete_config_value($name) {
	global $ftsdb, $mbp_config; 
	
	$result = $ftsdb->delete(DBTABLEPREFIX . 'config', "name = :name", array(
		":name" => $name
	));
	unset($mbp_config[$name]);
}

//==================================================
// Returns the value of a config setting in the database
//==================================================
function get_config_value( $name, $default = '' ) {
	global $mbp_config; 
	
	// Avoid hitting the database if we don't need to
	return ( isset( $mbp_config[$name] ) ) ? $mbp_config[$name] : getDatabaseItem( 'config', 'value', $name, $default, 'name' );
}

//==================================================
// Updates a config setting in the database
//==================================================
function update_config_value($name, $value) {
	global $ftsdb, $mbp_config; 
	
	$result = $ftsdb->update(DBTABLEPREFIX . 'config', array(
			"value" => $value
		), "name = :name", array(
			":name" => $name
		)
	);
	$mbp_config[$name] = $value;
	
	return $result;
}