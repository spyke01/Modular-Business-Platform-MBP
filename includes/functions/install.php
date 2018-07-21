<?php 
/***************************************************************************
 *                               install.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


 
/**
* Returns an array of available DBMS with some data, if a DBMS is specified it will only
* return data for that DBMS and will load its extension if necessary.
*/
function get_available_dbms( $return_unavailable = false ) {
	global $lang;
	$available_dbms = array(
		'pdo'		=> array(
			'LABEL'			=> 'PDO',
			'MODULE'		=> 'pdo',
			'AVAILABLE'		=> true,
		),
		// Note: php 5.5 alpha 2 deprecated mysql.
		// Keep mysqli before mysql in this list.
		/*
		'mysqli'	=> array(
			'LABEL'			=> 'MySQL with MySQLi Extension',
			'SCHEMA'		=> 'mysql_41',
			'MODULE'		=> 'mysqli',
			'DELIM'			=> ';',
			'COMMENTS'		=> 'remove_remarks',
			'DRIVER'		=> 'mysqli',
			'AVAILABLE'		=> true,
		),
		'mysql'		=> array(
			'LABEL'			=> 'MySQL',
			'SCHEMA'		=> 'mysql',
			'MODULE'		=> 'mysql',
			'DELIM'			=> ';',
			'COMMENTS'		=> 'remove_remarks',
			'DRIVER'		=> 'mysql',
			'AVAILABLE'		=> true,
		),
		*/
	);

	// now perform some checks whether they are really available
	foreach ( $available_dbms as $db_name => $db_ary ) {
		$dll = $db_ary['MODULE'];

		if ( !@extension_loaded( $dll ) ) {
			if ( !can_load_dll( $dll ) ) {
				if ( $return_unavailable ) {
					$available_dbms[$db_name]['AVAILABLE'] = false;
				} else {
					unset( $available_dbms[$db_name] );
				}
				continue;
			}
		}
		$any_db_support = true;
	}

	if ( $return_unavailable ) {
		$available_dbms['ANY_DB_SUPPORT'] = $any_db_support;
	}
	return $available_dbms;
}
 
//=========================================================
// Checks that the server we are installing on meets the requirements
//=========================================================
function check_server_requirements() {
	$checks = array(
		'php' => array(
			'pass' => false,
			'title' => 'PHP >= 5.5.9',
			'description' => 'You must be running at least version 5.5.9 of PHP in order to install the MBP.',
		), 
		'phpSafeMode' => array(
			'pass' => true,
			'title' => 'PHP Safe Mode Off',
			'description' => 'Your PHP installation is running in that mode. This will impose limitations on certain functions.',
		), 
		'registerGlobals' => array(
			'pass' => true,
			'title' => 'Register Globals Disabled',
			'description' => 'It is recommended that register_globals is disabled on your PHP install for security reasons. The system may run but certain pages will break.',
		), 
		/*
		'fopen' => array(
			'pass' => false,
			'title' => 'Allow URL Fopen',
			'description' => '<strong>Optional</strong> - This setting is optional, however certain functions like updates may break.',
		), 
		*/
		'curl' => array(
			'pass' => false,
			'title' => 'CURL Enabled',
			'description' => 'CURL must be available in order for the software to function properly. This feature is used for updates, license checks, and many other areas.',
		), 
		'db' => array(
			'pass' => false,
			'title' => 'Database Driver Available',
			'description' => 'You must have support for PDO within PHP. If PDO is not installed you should contact your hosting provider or review the relevant PHP installation documentation for advice.',
		), 
		'files' => array(
			'pass' => false,
			'title' => 'File Permissions',
			'description' => 'In order to function correctly the MBP needs to be able to access or write to certain files or directories. Please make sure the <strong>/files</strong> directory exists and that the permissions are set to allow the MBP to write to it.',
		), 
		'pcre' => array(
			'pass' => false,
			'title' => 'PCRE UTF-8 Support',
			'description' => 'This software will <strong>not</strong> run if your PHP installation is not compiled with UTF-8 support in the PCRE extension.',
		),
		'dbms' => array(),
	);
	$php_version = PHP_VERSION;

	// Test the minimum PHP version
	if ( version_compare( $php_version, '5.5.9' ) >= 0 ) {
		$checks['php']['pass'] = true;

		// We also give feedback on whether we're running in safe mode
		if ( @ini_get( 'safe_mode' ) == '1' || strtolower( @ini_get( 'safe_mode' ) ) == 'on' ) {
			$checks['phpSafeMode']['pass'] = false;
		}
	}

	// Don't check for register_globals on 5.4+
	if ( version_compare( $php_version, '5.4.0-dev' ) < 0 ) {
		// Check for register_globals being enabled
		if ( @ini_get( 'register_globals' ) == '1' || strtolower( @ini_get( 'register_globals' ) ) == 'on' ) {
			$checks['registerGlobals']['pass'] = false;
		}
	}

	/*
	// Check for url_fopen
	if ( @ini_get( 'allow_url_fopen' ) == '1' || strtolower( @ini_get( 'allow_url_fopen' ) ) == 'on' ) {
		$checks['fopen']['pass'] = true;
	} 
	*/

	// Check for curl
	if ( function_exists( 'curl_version' ) ) {
		$checks['curl']['pass'] = true;
	}

	// Check for PCRE UTF-8 support
	if ( @preg_match( '//u', '' ) ) {
		$checks['pcre']['pass'] = true;
	}
	
	// Test for available database modules
	$available_dbms = get_available_dbms( true );
	$checks['db']['pass'] = $available_dbms['ANY_DB_SUPPORT'];
	unset( $available_dbms['ANY_DB_SUPPORT'] );

	foreach ( $available_dbms as $db_name => $db_ary ) {
		if ( $db_ary['AVAILABLE'] ) {
			$checks['dbms'][$db_name] = true;
		}
	}

	// Check permissions on files/directories we need access to
	$directories = array( 'files/' );

	umask(0);

	$checks['files']['pass'] = true;
	foreach ( $directories as $dir ) {
		$exists = $write = false;

		// Try to create the directory if it does not exist
		if ( !file_exists( BASEPATH . '/' . $dir ) ) {
			@mkdir( BASEPATH . '/' . $dir, 0755 );
		}

		// Now really check
		if ( file_exists( BASEPATH . '/' . $dir ) && is_dir( BASEPATH . '/' . $dir ) ) {
			chmod( BASEPATH . '/' . $dir, 0755 );
			$exists = true;
		}

		// Now check if it is writable by storing a simple file
		$fp = @fopen( BASEPATH . '/' . $dir . 'test_lock', 'wb' );
		if ( $fp !== false ) {
			$write = true;
		}
		@fclose( $fp );

		@unlink( BASEPATH . '/' . $dir . 'test_lock' );

		$checks['files']['pass'] = ( $exists && $write && $checks['files'] ) ? true : false;
	}
	
	return $checks;
}