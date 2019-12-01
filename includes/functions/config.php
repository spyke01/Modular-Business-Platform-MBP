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


/**
 * Loads the config values from the database.
 *
 */
function load_config_values() {
	global $ftsdb, $mbp_config;

	$result = $ftsdb->select( DBTABLEPREFIX . "config" );
	if ( $result ) {
		foreach ( $result as $row ) {
			$mbp_config[ $row['name'] ] = $row['value'];
		}
		$result = null;
	}
}

/**
 * Checks if a config setting is in the database.
 *
 * @param $name
 *
 * @return int
 */
function config_value_exists( $name ) {
	global $ftsdb;

	$exists  = 0;
	$results = $ftsdb->select( DBTABLEPREFIX . "config",
		"name = :name",
		[
			":name" => $name,
		]
	);
	if ( $results && count( $results ) > 0 ) {
		$exists = 1;
	}
	$results = null;

	return $exists;
}

/**
 * Adds a config setting in the database.
 *
 * @param $name
 * @param $value
 */
function add_config_value( $name, $value ) {
	global $ftsdb, $mbp_config;

	if ( config_value_exists( $name ) ) {
		update_config_value( $name, $value );
	} else {
		$ftsdb->insert( DBTABLEPREFIX . 'config',
			[
				"name"  => $name,
				"value" => $value,
			]
		);
		$mbp_config[ $name ] = $value;
	}
}

/**
 * Deletes a config setting from the database.
 *
 * @param $name
 */
function delete_config_value( $name ) {
	global $ftsdb, $mbp_config;

	$ftsdb->delete( DBTABLEPREFIX . 'config',
		"name = :name",
		[
			":name" => $name,
		]
	);
	unset( $mbp_config[ $name ] );
}

/**
 * Returns the value of a config setting in the database.
 *
 * @param        $name
 * @param string $default
 *
 * @return string
 */
function get_config_value( $name, $default = '' ) {
	global $mbp_config;

	// Avoid hitting the database if we don't need to
	return ( isset( $mbp_config[ $name ] ) ) ? $mbp_config[ $name ] : getDatabaseItem( 'config', 'value', $name, $default, 'name' );
}

/**
 * Updates a config setting in the database.
 *
 * @param $name
 * @param $value
 *
 * @return int
 */
function update_config_value( $name, $value ) {
	global $ftsdb, $mbp_config;

	$result              = $ftsdb->update( DBTABLEPREFIX . 'config',
		[
			"value" => $value,
		],
		"name = :name",
		[
			":name" => $name,
		]
	);
	$mbp_config[ $name ] = $value;

	return $result;
}