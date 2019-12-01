<?php
/***************************************************************************
 *                               modules.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


/**
 * Call a module hook.
 *
 * This can call a single module or all modules in the system to perform an action or return data
 *
 * @param mixed  $prefix
 * @param mixed  $hookToCall
 * @param array  $hookArguments     (default: [])
 * @param int    $activeModulesOnly (default: 1)
 * @param string $returnType        (default: 'string')
 *
 * @return mixed                                    This can be an array or string composed of the data from all modules that were called
 */
function callModuleHook( $prefix, $hookToCall, $hookArguments = [], $activeModulesOnly = 1, $returnType = 'string' ) {
	global $ftsdb, $modules;
	//echo "Called $prefix, $hookToCall, $hookArguments, $activeModulesOnly, $returnType<br />";

	$argumentList = "";
	// If $prefix is empty then we want to hit all modules
	if ( $prefix == '' ) {
		$modulesToCall = array_keys( $modules );
	} else {
		$modulesToCall = array( $prefix );
	}

	if ( $returnType == 'array' ) {
		$returnVar = [];
	} else {
		$returnVar = "";
	}

	foreach ( $modulesToCall as $prefix ) {
		if ( is_numeric( $prefix ) || $prefix == '' ) {
			continue;
		}

		//echo "Calling $prefix<br />";
		// Make sure that we have an instance of our module
		if ( ! is_object( $modules[ $prefix ] ) ) {
			require_once( BASEPATH . "/modules/" . $prefix . "/" . $prefix . ".php" );
			// Instantiate class
			$modules[ $prefix ] = new $prefix;
			//echo "Instantiating $prefix<br />";
		}

		// Call our hook function
		if ( method_exists( $modules[ $prefix ], $hookToCall ) ) {
			//echo "method exists<br />";
			if ( ! empty( $hookArguments ) ) {
				$hookResult = $modules[ $prefix ]->$hookToCall( $hookArguments );
			} else {
				$hookResult = $modules[ $prefix ]->$hookToCall();
			}

			if ( $returnType == 'array' ) {
				$returnVar = $returnVar + (array) $hookResult;
			} else {
				$returnVar .= $hookResult;
			}
		}
	}

	// If we asked for a return value then provide it
	return $returnVar;
}

//=========================================================
// Include and create instances for our modules
//=========================================================
function initializeModules() {
	global $ftsdb, $modules;

	$result = $ftsdb->select( DBTABLEPREFIX . "modules", "", [], 'active, prefix' );

	if ( $result ) {
		foreach ( $result as $row ) {
			// Include our module file
			$prefix         = $row['prefix'];
			$fullModulePath = BASEPATH . '/modules/' . $prefix . "/" . $prefix . ".php";

			// Our prefix should be set, but if not dont continue
			if ( $prefix != "" ) {
				require_once( $fullModulePath );
				// Instantiate class
				$modules[ $prefix ] = new $prefix;
			}
		}
		$result = null;
	}
	//print_r($modules);
}

//=========================================================
// Installs a module
//=========================================================
function installModule( $prefix, $name, $description, $developer, $version ) {
	global $ftsdb;

	// Do the work
	$result = $ftsdb->select( DBTABLEPREFIX . "modules",
		"prefix = :prefix",
		array(
			":prefix" => $prefix,
		) );
	if ( count( $result ) == 0 ) {
		$result2 = $ftsdb->insert( DBTABLEPREFIX . 'modules',
			array(
				"name"        => $name,
				"description" => $description,
				"developer"   => $developer,
				"version"     => $version,
				"prefix"      => $prefix,
			) );

		$result = null;
	}
}

//=========================================================
// Uninstalls a module
//=========================================================
function uninstallModule( $prefix ) {
	global $ftsdb;

	$result = $ftsdb->delete( DBTABLEPREFIX . 'modules',
		"prefix = :prefix",
		array(
			":prefix" => $prefix,
		) );
}

//=========================================================
// Activates a module
//=========================================================
function activateModule( $prefix ) {
	global $ftsdb;

	$result = $ftsdb->update( DBTABLEPREFIX . "modules",
		array(
			"active" => ACTIVE,
		),
		"prefix = :prefix",
		array(
			":prefix" => $prefix,
		)
	);
}

//=========================================================
// Deactivates a module
//=========================================================
function deactivateModule( $prefix ) {
	global $ftsdb;

	$result = $ftsdb->update( DBTABLEPREFIX . "modules",
		array(
			"active" => INACTIVE,
		),
		"prefix = :prefix",
		array(
			":prefix" => $prefix,
		)
	);
}

//=========================================================
// Updates a module
//=========================================================
function updateModule( $prefix, $name, $description, $developer, $version ) {
	global $ftsdb;

	$result = $ftsdb->update( DBTABLEPREFIX . "modules",
		array(
			"name"        => $name,
			"description" => $description,
			"developer"   => $developer,
			"version"     => $version,
		),
		"prefix = :prefix",
		array(
			":prefix" => $prefix,
		)
	);
}

//=========================================================
// Checks to see if a module is installed
// Returns 0 for no or 1 for yes
//=========================================================
function isModuleInstalled( $prefix ) {
	global $ftsdb;

	$result = $ftsdb->select( DBTABLEPREFIX . "modules",
		"prefix = :prefix",
		array(
			":prefix" => $prefix,
		) );

	if ( count( $result ) == 0 ) {
		return 0;
	} else {
		return 1;
	}
}

//=========================================================
// Checks to see if a module is activated
// Returns 0 for no or 1 for yes
//=========================================================
function isModuleActivated( $prefix ) {
	global $ftsdb;

	$result = $ftsdb->select( DBTABLEPREFIX . "modules",
		"prefix = :prefix",
		array(
			":prefix" => $prefix,
		),
		'active' );

	if ( $result ) {
		foreach ( $result as $row ) {
			return $row['active'];
		}
		$result = null;
	} else {
		// There werent any results so the module isnt installed
		return INACTIVE;
	}
}

//=========================================================
// Checks to see if a module is the latest version
// Returns 0 for no or 1 for yes
//=========================================================
function isLatestModuleVersion( $prefix ) {
	global $modules, $fts_http;
	// Clean the variables
	$prefix = keepsafe( $prefix );

	//	if ( isset( $modules[$prefix]->updateURL ) && !empty( $modules[$prefix]->updateURL ) ) {
	//		$latestVersion = returnCurrentAppVersion( 'module_' . $prefix );
	//	} else {
	//		$updateData = json_decode( $fts_http->request( $modules[$prefix]->updateRequestURL ) );
	//		$latestVersion = $updateData->latestVersion;
	//	}

	// Do the work		
	//	$latestVersion = ( $latestVersion == $modules[$prefix]->version ) ? 1 : 0;
	$latestVersion = 1;

	return $latestVersion;
}

//=========================================================
// Returns an image representation of version
//=========================================================
function returnIsLatestModuleVersionImage( $prefix ) {
	global $mbp_config;

	$versionImage = ( isLatestModuleVersion( $prefix ) ) ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-warning-sign"></i>';

	return $versionImage;
}

//=========================================================
// Print the Modules table
//=========================================================
function printModulesTable() {
	global $menuvar, $mbp_config;

	$content        = "";
	$sub_file_names = [];

	// Themes available to all users
	$globalModulePath = BASEPATH . "/modules";
	$sub_dir_names    = getDirNames( $globalModulePath );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "usersTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => __( 'Modules' ), "colspan" => "6" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Name" ),
			array( 'type' => 'th', 'data' => "Description", 'class' => 'visible-lg' ),
			array( 'type' => 'th', 'data' => "Developer", 'class' => 'hidden-sm' ),
			array( 'type' => 'th', 'data' => "Version", 'class' => 'hidden-sm' ),
			array( 'type' => 'th', 'data' => "" ),
		),
		'',
		'title2',
		'thead'
	);

	// Add our data
	// Cycle through our modules and print them out to our table
	foreach ( $sub_dir_names as $file => $path ) {
		// Include our module class file
		require_once( $path . "/" . $file . "/" . $file . ".php" );

		// Create an instance of our module class so that we can reference it for information
		$moduleInstance = new $file;

		$table->addNewRow(
			array(
				array( 'data' => $moduleInstance->name ),
				array( 'data' => $moduleInstance->description, 'class' => 'visible-lg' ),
				array( 'data' => $moduleInstance->developer, 'class' => 'hidden-sm' ),
				array( 'data' => $moduleInstance->version . ' ' . returnIsLatestModuleVersionImage( $file ), 'class' => 'hidden-sm' ),
				array( 'data' => '<span id="statusButtonHolder_' . $file . '" class="btn-group">' . printModulesStatusButtons( $file ) . '</span>', 'class' => 'center' ),
			),
			'',
			''
		);

		// Reset our moduleInstance variable so that we can use it again
		unset( $moduleInstance );
	}

	// Return the table's HTML
	$content = $table->returnTableHTML();

	unset( $sub_dir_names );

	return $content;
}

//=========================================================
// Print the buttons for making a module active/deactive
// and for installing/uninstalling
//=========================================================
function printModulesStatusButtons( $prefix ) {
	global $mbp_config;
	$content = "";

	// Determine if our module is installed
	if ( isModuleInstalled( $prefix ) ) {
		// The module is installed so let us uninstall it
		// Global modules should not be able to be uninstalled by a user
		$content .= '<a class="btn btn-danger" onClick="ajaxUninstallModule(\'' . $prefix . '\'); return false;">Uninstall</a>';

		// Determine if the module is activated or not
		if ( isModuleActivated( $prefix ) ) {
			// The module is activated so let us deactivate it
			$content .= '<a class="btn btn-warning" onClick="ajaxDeactivateModule(\'' . $prefix . '\'); return false;">Disable</a>';
		} else {
			// The module is not activated so let us activate it
			$content .= '<a class="btn btn-primary" onClick="ajaxActivateModule(\'' . $prefix . '\'); return false;">Activate</a>';
		}
	} else {
		// The module is not installed so let us install it
		$content .= '<a class="btn btn-primary" onClick="ajaxInstallModule(\'' . $prefix . '\'); return false;">Install</a>';
	}

	return $content;
}