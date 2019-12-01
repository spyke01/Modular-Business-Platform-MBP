<?php
/***************************************************************************
 *                               updates.php
 *                            -------------------
 *   begin                : Monday, Aug 20, 2012
 *   copyright            : (C) 2012 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


/**
 * Returns the app update data so we can work with it. The data is cached to cut down on multiple queries.
 *
 * @return string Version info block.
 * @since  4.16.05.18
 *
 * @access public
 */
function returnAppUpdateData() {
	global $fts_http, $mbp_update_data;

	if ( empty( $mbp_update_data ) ) {
		// Get current version details
		//		$updateData = $fts_http->request("https://www.fasttracksites.com/versions/serialChecker.php", 'POST', [
		//			'app' => A_NAME,
		//			'serial' => A_LICENSE,
		//			'response' => 'json'
		//		]);
		//		$mbp_update_data = json_decode($updateData);
		//var_export($mbp_update_data);
	}

	return $mbp_update_data;
}

/**
 * Returns the app version block since we use it more than once.
 *
 * @return string Version info block.
 * @since  4.14.07.15
 *
 * @access public
 */
function returnAppVersionBlock() {
	$updateData = returnAppUpdateData();

	//var_export($updateData);

	return '
				<strong>Application:</strong> ' . A_NAME . '<br />
				<strong>Version:</strong> ' . A_VERSION . '<br />
				<strong>Latest Version:</strong> ' . $updateData->latestVersion . '<br />
				<strong>Registered to:</strong> ' . A_LICENSED_TO . '<br />
				<strong>Serial:</strong> ' . A_LICENSE . '<br />
				<strong>Valid Serial:</strong> ' . ( ( A_VALID_LICENSE ) ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove"></i>' ) . '<br />
				<strong>Expired Serial:</strong> ' . ( ( $updateData->expiredSerial ) ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove"></i>' );
}

/**
 * Returns licensing and version info.
 *
 * @return string Version info block.
 * @since  4.14.07.09
 *
 * @access public
 */
function returnAppVersionInfo() {
	global $mbp_config, $modules;

	$appInfo = "
		<div class=\"box\">
			<div class=\"box-header\">
				<h3>Version Information</h3>
			</div>
			<div class=\"box-content\">
				<p>
					" . returnAppVersionBlock() . "
					<h1>Module Information</h1>";

	foreach ( $modules as $prefix => $instance ) {
		$serial  = $mbp_config[ 'ftsmbp_' . strtolower( $prefix ) . '_serial' ];
		$appInfo .= "
					<strong>$instance->name</strong> $instance->version " . returnIsLatestModuleVersionImage( $prefix ) . "<br />
					<strong>Serial:</strong> $serial " . returnIsValidSerialNumberImage( $prefix, $serial ) . "<br /><br />";
	}

	$appInfo .= "
				</p>
			</div>
		</div>";

	return $appInfo;
}

/**
 * Returns licensing and version info as well as changelogs.
 *
 * @return string Version info block.
 * @since  4.14.07.15
 *
 * @access public
 */
function returnAppInfoBlock() {
	global $mbp_config, $modules;
	$moduleTabLinks = $moduleTabs = '';
	$mbpChangelog   = file_get_contents( 'changelog.md', FILE_USE_INCLUDE_PATH );
	$mbpChangelog   = str_replace( '# Changelog #', "# MBP Changelog #", $mbpChangelog );
	$Parsedown      = new Parsedown();

	foreach ( $modules as $prefix => $instance ) {
		$changelogFile = BASEPATH . '/modules/' . $prefix . '/changelog.md';

		if ( is_file( $changelogFile ) ) {
			$changelog = file_get_contents( $changelogFile, FILE_USE_INCLUDE_PATH );
			$changelog = str_replace( '# Changelog #', "# $prefix Changelog #", $changelog );

			$moduleTabLinks .= '<li><a href="#' . $prefix . 'Changelog" data-toggle="tab"><span>' . $prefix . '</span></a></li>';
			$moduleTabs     .= '
				<div id="' . $prefix . 'Changelog" class="tab-pane">
					' . $Parsedown->text( $changelog ) . '
				</div>';
		}
	}

	$block = '
				<h2>Version Information</h2>
				<p>' . returnAppVersionBlock() . '</p>
				<div class="box tabbable">
					<div class="box-header">
						<h3><i class="glyphicon glyphicon-road"></i> ' . __( 'Changelogs' ) . '</h3>
						<div class="toolbar">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#mbpChangelog" data-toggle="tab"><span>MBP</span></a></li>
								' . $moduleTabLinks . '
							</ul>
						</div>
					</div>
					<div class="tab-content">
						<div id="mbpChangelog" class="tab-pane active">
							' . $Parsedown->text( $mbpChangelog ) . '
						</div>
						' . $moduleTabs . '
					</div>
				</div>';

	return $block;
}

//=================================================
// Perform Database updates
//=================================================
function updateMBPDatabase() {
	global $ftsdb, $server, $dbname, $dbuser, $dbpass;

	// Check default values and fixes
	include( BASEPATH . '/includes/dbUpdates/defaults.php' );

	// Handle DB updates
	$dbVersion = get_config_value( 'database_version' );

	if ( ! config_value_exists( 'database_version' ) ) {
		include( BASEPATH . '/includes/dbUpdates/4.13.08.08.php' );
		$dbVersion = '4.13.08.08';
	}

	// Handle including our DB Updates
	$dbUpdates = [
		'4.13.08.19'    => [ '4.13.08.19.php' ],
		'4.13.08.28'    => [ '4.13.08.28.php' ],
		'4.13.12.12'    => [ '4.13.08.28.php' ], // There was a bug where this update wasn't included at one time
		'4.14.01.08'    => [ '4.14.01.08.php' ],
		'4.14.01.27'    => [ '4.14.01.27.php' ],
		'4.14.02.12'    => [ '4.14.02.12.php' ],
		'4.14.02.28'    => [
			// We forgot to add these previously
			'4.14.02.13.php',
			'4.14.02.24.php',
			'4.14.02.28.php',
		],
		'4.14.03.11'    => [ '4.14.03.11.php' ],
		'4.14.06.02'    => [ '4.14.06.02.php' ],
		'4.14.07.11'    => [ '4.14.07.11.php' ],
		'4.14.08.05'    => [ '4.14.08.05.php' ],
		'4.14.11.24'    => [ '4.14.11.24.php' ],
		'4.15.04.06'    => [ '4.15.04.06.php' ],
		'4.15.04.27'    => [ '4.15.04.27.php' ],
		'4.15.05.14'    => [ '4.15.05.14.php' ],
		'4.15.09.01'    => [ '4.15.09.01.php' ],
		'4.15.10.02'    => [ '4.15.10.02.php' ],
		'4.16.02.23'    => [ '4.16.02.23.php' ],
		'4.16.04.18'    => [ '4.16.04.18.php' ],
		'4.16.06.30'    => [ '4.16.06.30.php' ],
		'4.16.11.15.01' => [ '4.16.11.15.01.php' ],
		'4.16.11.15.02' => [ '4.16.11.15.02.php' ],
	];

	foreach ( $dbUpdates as $version => $files ) {
		if ( $dbVersion < $version ) {
			foreach ( $files as $file ) {
				include( BASEPATH . '/includes/dbUpdates/' . $file );
			}
		}
	}

	// Recreate our database settings file
	//saveDatabaseFile($server, $dbname, $dbuser, $dbpass, DBTABLEPREFIX);

	// Delete the install.php file in case we accidentally left it in the update package
	if ( file_exists( 'install.php' ) ) {
		unlink( 'install.php' );
	}

	// Run all module datbase updates
	callModuleHook( '', 'update' );

	// Update our database version
	if ( $dbVersion < A_VERSION ) {
		add_config_value( 'prev_database_version', $dbVersion );
		add_config_value( 'database_version', A_VERSION );
		add_config_value( 'shown_updates_popup', 0 );
	}
}

//=================================================
// Determines whether or not to show the update popup
//=================================================
function showUpdatePopup() {
	global $modules;

	$showPopup = 0;

	if ( get_config_value( 'shown_updates_popup' ) == 0 ) {
		$showPopup = 1;
	}

	foreach ( $modules as $prefix => $instance ) {
		if ( get_config_value( 'shown_updates_popup_' . $prefix ) == 0 ) {
			$showPopup = 1;
		}
	}

	return $showPopup;
}

//=================================================
// Marks all the update popups as viewed
//=================================================
function showedUpdatePopup() {
	global $modules;

	add_config_value( 'shown_updates_popup', 1 );

	foreach ( $modules as $prefix => $instance ) {
		add_config_value( 'shown_updates_popup_' . $prefix, 1 );
	}
}

//=================================================
// Shows the details an the update
//=================================================
function returnUpdateDetails( $showVersions = true ) {
	global $modules;

	$returnVar        = '';
	$installedVersion = A_VERSION;
	$previousVersion  = get_config_value( 'prev_database_version' );
	$latestVersion    = null;
	$Parsedown        = new Parsedown();

	// This displays a nicely formatted version info box
	if ( $showVersions ) {
		// Get the latest version info
		$updateData = returnAppUpdateData();
		//var_export($updateData);

		$latestVersion = $updateData->latestVersion;
		$extraClass    = ( $installedVersion == $latestVersion ) ? 'latest' : 'old';
		$extraClass    = ( $installedVersion > $latestVersion ) ? 'dev' : $extraClass;

		// Display our data
		$returnVar .= '
			<div class="versionContainer">
				<div class="yourHeader">Your Version</div>
				<div class="latestHeader">Latest Version</div>
				<div class="yourNumber ' . $extraClass . '">' . $installedVersion . '</div>
				<div class="latestNumber">' . $latestVersion . '</div>
				<div class="clear"></div>
			</div>';

	}

	// Process our changelog and add it to the returned value
	$changelog = file_get_contents( 'changelog.md', FILE_USE_INCLUDE_PATH );

	// Try to show only the relevant changelog data
	$pos       = strpos( $changelog, '## ' . $previousVersion . ' ##' );
	$pos       = ( $pos === false ) ? strlen( $changelog ) : $pos;
	$changelog = substr( $changelog, 0, $pos );
	$changelog = str_replace( '# Changelog #', '# MBP Changelog #', $changelog );

	$returnVar .= $Parsedown->text( $changelog );

	foreach ( $modules as $prefix => $instance ) {
		$changelogFile = BASEPATH . '/modules/' . $prefix . '/changelog.md';
		if ( get_config_value( 'shown_updates_popup_' . $prefix ) == 0 && is_file( $changelogFile ) ) {
			$previousVersion = get_config_value( 'prev_database_version_' . $prefix );

			// Process our changelog and add it to the returned value
			$changelog = file_get_contents( $changelogFile, FILE_USE_INCLUDE_PATH );

			// Try to show only the relevant changelog data
			$pos       = strpos( $changelog, '## ' . $previousVersion . ' ##' );
			$pos       = ( $pos === false ) ? strlen( $changelog ) : $pos;
			$changelog = substr( $changelog, 0, $pos );
			$changelog = str_replace( '# Changelog #', "# $prefix Changelog #", $changelog );

			$returnVar .= $Parsedown->text( $changelog );
		}
	}

	return $returnVar;
}

//=================================================
// Checks for updates to the MBP
//=================================================
function checkForMBPUpdates() {
	$updateData = returnAppUpdateData();
	//var_export($updateData);

	// Make sure we have a valid serial number
	//	if ( !$updateData->validSerial ) {
	//		return [ 'status' => 'error', 'message' => 'Your Serial number is not valid!' ];
	//	} elseif ( $updateData->expiredSerial ) {
	//		return [ 'status' => 'error', 'message' => 'Your Serial number has expired, please <a href="https://www.fasttracksites.com/product/license-renewal">renew your license here</a>!' ];
	//	} else {
	//		return downloadAndInstallUpdate( 'MBP', 1, $updateData->updateURL, A_VERSION, $updateData->latestVersion );
	//	}
	return [ 'status' => 'error', 'message' => 'Updates are not available when using the public version.' ];
}

//=================================================
// Checks for updates to modules
//=================================================
function checkForModuleUpdates() {
	global $modules, $fts_http;
	$returnArray = [];

	foreach ( $modules as $prefix => $instance ) {
		$latestVersion = '';

		// Make sure that we have an instance of our module
		if ( ! is_object( $modules[ $prefix ] ) ) {
			$fullModulePath = BASEPATH . "/modules/" . $prefix . "/" . $prefix . ".php";
			require_once( $fullModulePath );
			// Instantiate class
			$modules[ $prefix ] = new $prefix;
		}

		if ( isset( $modules[ $prefix ]->updateURL ) && ! empty( $modules[ $prefix ]->updateURL ) ) {
			$upgradeURL    = $modules[ $prefix ]->updateURL;
			$latestVersion = isLatestModuleVersion( $prefix );
		} else {
			//			$updateData = json_decode( $fts_http->request( $modules[$prefix]->updateRequestURL ) );
			//			$upgradeURL = $updateData->updateURL;
			//			$latestVersion = $updateData->latestVersion;
		}

		//		$returnArray[$prefix] = downloadAndInstallUpdate( $prefix, 2, $upgradeURL, $modules[$prefix]->version, $latestVersion );
	}

	return $returnArray;
}

//=================================================
// Checks for updates to themes
//=================================================
function checkForThemeUpdates() {
	global $modules, $fts_http;
	$returnArray = [];

	// Themes available to all users
	$stylepath = BASEPATH . "/themes";
	$themes    = getDirNames( $stylepath, 'bootstrap,default,modern,installer,jquery,modules' ); // Ignore themes hat are part of the MBP package

	// Themes for modules
	$moduleThemes = getDirNames( $stylepath . '/modules' );

	foreach ( $moduleThemes as $module => $nothing ) {
		$moduleThemes = getDirNames( "$stylepath/modules/$module" );

		foreach ( $moduleThemes as $theme => $nothing ) {
			$themes["modules/$module/$theme"] = $nothing;
		}
	}

	ksort( $themes ); //sort by name

	// Start update check
	foreach ( $themes as $theme => $nothing ) {
		$latestVersion        = '';
		$themeDetailsFilename = $stylepath . '/' . $theme . '/themedetails.php';

		if ( file_exists( $themeDetailsFilename ) ) {
			include( $themeDetailsFilename );

			// Get update info
			//			$updateData = json_decode( $fts_http->request( $themeUpdateRequestURL ) );
			//			$upgradeURL = $updateData->updateURL;
			//			$latestVersion = $updateData->latestVersion;
			//
			//			// Try and update
			//			$returnArray[$theme] = downloadAndInstallUpdate( $theme, 3, $upgradeURL, $themeVersion, $latestVersion );
		} else {
			// $returnArray[$theme] = ['status' => 'error', 'message' => "$theme does not have a theme details file!"];
		}
	}

	return $returnArray;
}

//=================================================
// Download and install an update package
// ---
// type = (1 = MBP, 2 = Module, 3 = Theme)
//=================================================
function downloadAndInstallUpdate( $name, $type, $upgradeURL = '', $currentVersion = '', $latestVersion = '' ) {
	// Increase our memory limit
	increase_memory_limit( true );

	/*
	* Check version number and see if we are up to date
	* Send server our serial number and see if we get a download package
	* Download file
	* Extract to temp location
	* Check for files in extracted area
	* Copy upgrade files
	* Run update routine
	*/
	$fileFunctions  = new fileFunctions; //initialize our fileFunctions class
	$upgrade_folder = BASEPATH . "/files/upgrade";
	$finalLocation  = ( $type == 1 ) ? BASEPATH : BASEPATH . '/modules/' . $name;
	$finalLocation  = ( $type == 3 ) ? BASEPATH . '/themes/' . $name : $finalLocation;
	if ( ! is_dir( $upgrade_folder ) ) {
		mkdir( $upgrade_folder );
	}

	if ( empty( $upgradeURL ) ) {
		return [ 'status' => 'error', 'message' => "No Upgrade URL was supplied for $name!" ];
	}

	// See if we actually need to update
	if ( $currentVersion < $latestVersion || isset ( $_GET['forceUpdate'] ) ) {
		//$upgradeURL = "https://www.fasttracksites.com/versions/upgrades/mbp.zip";
		$updgradeFileName = $upgrade_folder . '/' . basename( $upgradeURL );

		$downloadFileAttempt = $fileFunctions->downloadFile( $upgradeURL, $updgradeFileName );

		if ( $downloadFileAttempt['status'] == 'error' ) {
			return $downloadFileAttempt;
		}

		if ( ! is_file( $updgradeFileName ) ) {
			return [ 'status' => 'error', 'message' => 'Failed to download update!' ];
		} else {
			// Remove any directories left over from previous upgrades
			$upgrade_files = getDirNames( $upgrade_folder );
			if ( count( $upgrade_files ) > 0 ) {
				foreach ( $upgrade_files as $filename => $nothing ) {
					$fileFunctions->delete( $upgrade_folder . '/' . $filename, true );
				}
			}

			//We need a working directory
			$working_dir = $upgrade_folder . '/' . basename( $updgradeFileName, '.zip' );

			// Clean up working directory
			if ( is_dir( $working_dir ) ) {
				$fileFunctions->delete( $working_dir, true );
			}

			// Unzip package to working directory
			$result = $fileFunctions->unzip_file( $updgradeFileName, $working_dir );

			// Once extracted, delete the package
			@unlink( $updgradeFileName );

			if ( $result['status'] == 'error' ) {
				$fileFunctions->delete( $working_dir, true );

				//print_r($result);
				return $result;
			} else {
				// Move our new files
				$fileFunctions->move_dir( $working_dir, $finalLocation );

				// Store module version info for popup
				if ( get_config_value( 'database_version' ) < A_VERSION && $type == 1 ) {
					add_config_value( 'prev_database_version_' . $name, $currentVersion );
					add_config_value( 'database_version_' . $name, $latestVersion );
					add_config_value( 'shown_updates_popup_' . $name, 0 );
				}
			}

			return [ 'status' => 'success', 'message' => "$name Updated!" ];
		}
	} else {
		return [ 'status' => 'success', 'message' => "$name is the latest version!" ];
	}
}