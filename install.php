<?php
/***************************************************************************
 *                               install.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


ini_set( 'arg_separator.output', '&amp;' );
error_reporting( E_ALL );
ini_set( 'display_errors', '1' );

/* Define our Paths */
define( 'ABSPATH', dirname( __FILE__ ) . '/' );
define( 'BASEPATH', rtrim( ABSPATH, '/' ) );
define( 'SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . rtrim( dirname( $_SERVER['PHP_SELF'] ), '/\\' ) );

// Set up our installer
define( 'INSTALLER_SCRIPT_NAME', 'Modular Business Platform' );
define( 'INSTALLER_SCRIPT_DESC', 'The Modular Business Platform was designed to allow businesses to allow wed designers and system administrators to create their own custom administrative websites. The system allows you to use modules and build your own site from a trusted and easy to use platform.' );
define( 'INSTALLER_SCRIPT_DB_PREFIX', 'MBP_' );

// Include our class autoloader to enable MVC items
include( BASEPATH . '/vendor/autoload.php' );

use App\Support\Registry;
use Illuminate\Database\Capsule\Manager as DB;

// Handle our variables
$actual_step  = ( empty( $_GET['step'] ) ) ? 1 : intval( $_GET['step'] );
$page_content = $JQueryReadyScripts = "";
$failed       = 0;
$totalfailure = 0;
$failed       = [];
$failedsql    = [];
$currentdate  = time();

if ( $actual_step < 4 ) {
	// Inlcude the needed files
	include( BASEPATH . '/includes/constants.php' );
	include( BASEPATH . '/includes/classes/FTSDB.php' );
	include( BASEPATH . '/includes/classes/fts-http.php' );
	include( BASEPATH . '/includes/classes/Page.php' );
	include( BASEPATH . '/includes/functions/http.php' );
	include( BASEPATH . '/includes/functions/modules.php' );
	include( BASEPATH . '/includes/functions/menus.php' );
	$fts_http = new fts_http; //initialize our curl handler
	$page     = new Page; //initialize our page
} elseif ( $actual_step == 4 ) {
	// Inlcude the needed files
	include( BASEPATH . '/_db.php' );
	include( BASEPATH . '/includes/constants.php' );
	include( BASEPATH . '/includes/classes/FTSDB.php' );
	include( BASEPATH . '/includes/classes/fts-http.php' );
	include( BASEPATH . '/includes/classes/Page.php' );
	include( BASEPATH . '/includes/functions/config.php' );
	include( BASEPATH . '/includes/functions/general.php' );
	include( BASEPATH . '/includes/functions/http.php' );
	include( BASEPATH . '/includes/functions/modules.php' );
	include( BASEPATH . '/includes/functions/menus.php' );
	include( BASEPATH . '/includes/functions/permissions.php' );
	include( BASEPATH . '/includes/functions/email.php' );
	$fts_http = new fts_http; //initialize our curl handler
	$page     = new Page; //initialize our page

	// Create our database connection
	$ftsdb = connectToDB( $server, $dbname, $dbuser, $dbpass, $serverPort );
	//$ftsdb->profile = 1;
	$ftsdb->setErrorCallbackFunction( 'echo' );
	$ftsdb->setProfileCallbackFunction( 'echo' );

	// Set up our Uses
	Registry::add( $page ); // Store this in the registry so our views can access it

	// Create our eloquent DB connection
	$capsule = new DB;
	$capsule->addConnection( [
		'driver'    => 'mysql',
		'host'      => $server . ':' . $serverPort,
		'database'  => $dbname,
		'username'  => $dbuser,
		'password'  => $dbpass,
		'charset'   => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix'    => DBTABLEPREFIX,
	] );
	$capsule->setAsGlobal();
	$capsule->bootEloquent();
} else {
	include( BASEPATH . '/includes/header.php' );
}

//=========================================================
//
// Install Functions
//
//=========================================================
function checkresult( $result, $sql, $table ) {
	global $failed;
	global $failedsql;
	global $totalfailure;

	if ( ! $result ) {
		$failed[ $table ]    = "failed";
		$failedsql[ $table ] = $sql;
		$totalfailure        = 1;
	} else {
		$failed[ $table ]    = "succeeded";
		$failedsql[ $table ] = $sql;
	}
}

if ( ! function_exists( 'keepsafe' ) ) {
	function keepsafe( $makesafe ) {
		$makesafe = strip_tags( $makesafe ); // strip away any dangerous tags
		$makesafe = str_replace( " ", "", $makesafe ); // remove spaces from variables
		$makesafe = str_replace( "%20", "", $makesafe ); // remove escaped spaces
		//encode all ascii items above #127
		$makesafe = preg_replace_callback(
			'/[^\x09\x0A\x0D\x20-\x7F]/',
			function ( $m ) {
				return "&#\".ord($m[0]).\";";
			},
			$makesafe
		);
		$makesafe = stripslashes( $makesafe );

		return $makesafe;
	}
}

if ( ! function_exists( 'keeptasafe' ) ) {
	function keeptasafe( $makesafe ) {
		$makesafe = strip_tags( $makesafe ); // strip away any dangerous tags
		//encode all ascii items above #127
		$makesafe = preg_replace_callback(
			'/[^\x09\x0A\x0D\x20-\x7F]/',
			function ( $m ) {
				return "&#\".ord($m[0]).\";";
			},
			$makesafe
		);

		$makesafe = stripslashes( $makesafe );

		return $makesafe;
	}
}

if ( ! function_exists( 'checkSerialNumber' ) ) {
	function checkSerialNumber( $app = A_NAME, $serial = A_LICENSE ) {
		global $fts_http;

		//		$result = $fts_http->request("https://www.fasttracksites.com/versions/serialChecker.php", 'POST', [
		//			'app' => $app,
		//			'serial' => $serial
		//		]);

		return $result;
	}
}

if ( ! function_exists( 'saveDatabaseFile' ) ) {
	function saveDatabaseFile( $dbServer, $dbName, $dbUsername, $dbPassword, $DBTABLEPREFIX, $serverPort = '3306' ) {
		$str = "<?php\n\n// Connect to the database\n\n\$server = \"" . $dbServer . "\";\n\$serverPort = \"" . $serverPort . "\";\n\$dbuser = \"" . $dbUsername . "\";\n\$dbpass = \"" . $dbPassword . "\";\n\$dbname = \"" . $dbName . "\";\ndefine('DBTABLEPREFIX', '" . $DBTABLEPREFIX . "');\ndefine('USERSDBTABLEPREFIX', '" . $DBTABLEPREFIX . "');";

		$fp     = fopen( "_db.php", "w+" );
		$result = fwrite( $fp, $str );
		fclose( $fp );

		return $result;
	}
}

if ( ! function_exists( 'connectToDB' ) ) {
	function connectToDB( $server, $dbname, $dbuser, $dbpass, $serverPort = '3306' ) {
		// TO-DO build a mysql_ version of this class for backwards compatability
		return new FTSDB( "mysql:host=$server;port=$serverPort;dbname=$dbname", $dbuser, $dbpass );
	}
}

if ( ! function_exists( 'getDirNames' ) ) {
	function getDirNames( $dirRequested, $ignore = '' ) {
		$dirNames     = [];
		$dirsToIgnore = explode( ',', $ignore );

		if ( is_dir( $dirRequested ) ) {
			if ( $dir = opendir( $dirRequested ) ) {
				while ( false !== ( $file = readdir( $dir ) ) ) {
					if ( $file != "." && $file != ".." && ! in_array( $file, $dirsToIgnore ) && is_dir( $dirRequested . "/" . $file ) ) {
						$dirNames[ $file ] = $dirRequested;
					}
				}
			}
		}
		ksort( $dirNames );

		return $dirNames;
	}
}

if ( ! function_exists( 'getFileNames' ) ) {
	function getFileNames( $dirRequested, $keepExtension = 1 ) {
		$fileNames = [];

		if ( is_dir( $dirRequested ) ) {
			if ( $dir = opendir( $dirRequested ) ) {
				while ( false !== ( $file = readdir( $dir ) ) ) {
					if ( $file != "." && $file != ".." && ! is_dir( $dirRequested . "/" . $file ) ) {
						$fileArray              = explode( ".", $file );
						$fileName               = ( $keepExtension == 1 ) ? $fileArray[0] . $fileArray[1] : $fileArray[0];
						$fileNames[ $fileName ] = $dirRequested;
					}
				}
			}
		}
		ksort( $fileNames );

		return $fileNames;
	}
}

//==================================================
// Returns a bootstrap alert
//==================================================
if ( ! function_exists( 'return_alert' ) ) {
	function return_alert( $text, $showIcon = 1, $icon = 'glyphicon glyphicon-ok', $id = '' ) {
		return '<div class="alert"' . ( ( ! empty ( $id ) ) ? ' id="' . $id . '"' : '' ) . '>' . ( ( $showIcon ) ? '<i class="' . $icon . '"></i> ' : '' ) . $text . '</div>';
	}
}

//==================================================
// Returns a bootstrap error alert
//==================================================
if ( ! function_exists( 'return_error_alert' ) ) {
	function return_error_alert( $text, $showIcon = 1, $icon = 'glyphicons glyphicons-warning-sign', $id = '' ) {
		return '<div class="alert alert-danger"' . ( ( ! empty ( $id ) ) ? ' id="' . $id . '"' : '' ) . '>' . ( ( $showIcon ) ? '<i class="' . $icon . '"></i> ' : '' ) . $text . '</div>';
	}
}

//==================================================
// Returns a bootstrap info alert
//==================================================
if ( ! function_exists( 'return_info_alert' ) ) {
	function return_info_alert( $text, $showIcon = 1, $icon = 'glyphicons glyphicons-warning-sign', $id = '' ) {
		return '<div class="alert alert-info"' . ( ( ! empty ( $id ) ) ? ' id="' . $id . '"' : '' ) . '>' . ( ( $showIcon ) ? '<i class="' . $icon . '"></i> ' : '' ) . $text . '</div>';
	}
}

//==================================================
// Returns a bootstrap warning alert
//==================================================
if ( ! function_exists( 'return_warning_alert' ) ) {
	function return_warning_alert( $text, $showIcon = 1, $icon = 'glyphicons glyphicons-warning-sign', $id = '' ) {
		return '<div class="alert alert-danger"' . ( ( ! empty ( $id ) ) ? ' id="' . $id . '"' : '' ) . '>' . ( ( $showIcon ) ? '<i class="' . $icon . '"></i> ' : '' ) . $text . '</div>';
	}
}

//========================================
// Build our Page
//========================================
switch ( $actual_step ) {
	case 1:
		$page->setTemplateVar( 'PageTitle', INSTALLER_SCRIPT_NAME . " Step 1 - Introduction" );
		include( BASEPATH . '/includes/functions/install.php' );
		$checks       = check_server_requirements();
		$checkResults = '';

		foreach ( $checks as $key => $check ) {
			if ( $key == 'dbms' ) {
				continue;
			}

			$labelColor   = ( $check['pass'] ) ? 'label-success' : 'label-danger';
			$labelText    = ( $check['pass'] ) ? 'PASS' : 'FAIL';
			$checkResults .= '
				<li class="list-group-item">
					<span class="label ' . $labelColor . ' pull-right">' . $labelText . '</span>
					' . $check['title'] . '
					' . ( ( ! $check['pass'] ) ? '<br /><br /><em>' . $check['description'] . '</em>' : '' ) . '
				</li>';
		}
		//print_r( $checks );


		// Print this page
		$page_content = '
			<h1>' . INSTALLER_SCRIPT_NAME . ' Installer</h1>
			Thank you for downloading the ' . INSTALLER_SCRIPT_NAME . ' this page will walk you through the setup procedure.
			<div class="scriptDescription">' . INSTALLER_SCRIPT_DESC . '</div>
										
			<h3><i class="glyphicons glyphicons-server"></i> Server Requirements</h3>
			If any of the items below have failed please resolve them before continuing the installation.<br /><br />	
			<ul class="list-group">
				' . $checkResults . '
			</ul>
					
			<form id="licenseInformationForm" action="install.php?step=2" method="post" class="form-horizontal" role="form">
				<fieldset>
					<legend><i class="glyphicons glyphicons-keys"></i> License Agreement</legend>
					<div class="help-block">Please enter your registration information below, failure to do so can result in your application being disabled.</div>
					<div class="form-group">
						<label for="serialNumber" class="col-sm-3 control-label">Serial Number</label> 
						<div class="col-sm-9">
							<label><input type="radio" name="serialType" id="freeVersionCheckbox" value="free" /> I want to use the FREE version (1 User Account, no updates)</label>
							<label><input type="radio" name="serialType" id="proVersionCheckbox" value="pro" /> I purchased a serial number</label>
							<div id="serialNumberInput" style="display:none"><input type="text" name="serialNumber" id="serialNumber" placeholder="Enter you Serial Number here" class="form-control required" /></div>
						</div>
					</div>
					<div class="form-group">
						<label for="registeredTo" class="col-sm-3 control-label">Registered To</label> 
						<div class="col-sm-9"><input type="text" name="registeredTo" id="registeredTo" class="form-control required" /></div>
					</div>
					<div class="help-block">By installing this application you are agreeing to all the terms and conditions stated in the <a href="https://www.fasttracksites.com/ftspl">Paden Clayton Program License</a>.</div>
					<div class="form-group"><div class="col-sm-12"><input type="submit" name="submit" class="btn btn-primary" value="I Agree" /></div></div>
				</fieldset>
			</form>';

		$JQueryReadyScripts = '	
			$("input[name=\'serialType\']").click(function() {
				if ( $(this).val() == "free" )
					$("#serialNumberInput").hide();
				else
					$("#serialNumberInput").show();
			}).click();			
			var v = jQuery("#licenseInformationForm").validate({
				highlight: function(element) {
					$(element).closest(\'.form-group\').addClass(\'has-error\');
				},
				unhighlight: function(element) {
					$(element).closest(\'.form-group\').removeClass(\'has-error\');
				},
				errorElement: \'span\',
				errorClass: \'help-block\',
				errorPlacement: function(error, element) {
					if(element.parent(\'.input-group\').length) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				},
			});';

		break;
	case 2:
		$page->setTemplateVar( 'PageTitle', INSTALLER_SCRIPT_NAME . " Step 2 - Database Connection" );

		// Create our license file
		$validLicense     = 1;
		$serialNumber     = ( empty( $_POST['serialNumber'] ) ) ? 'FREE_VERSION' : keepsafe( $_POST['serialNumber'] );
		$registeredTo     = keeptasafe( $_POST['registeredTo'] );
		$validLicenseText = '';

		//		// Check if we are using the free version
		//		if ( $_POST['serialType'] == 'free' )
		//			$serialNumber = 'FREE_VERSION';
		//
		//		// Check serial number
		//		$validLicense = checkSerialNumber(A_NAME, $serialNumber);
		//
		//		// Check for blacklisitng
		//		if ( $blacklistCheckResult = $fts_http->request('https://www.fasttracksites.com/versions/advancedOptionsChecker.php', 'POST', [ 'site' => urlencode( $_SERVER['HTTP_HOST'] )] ) ) {
		//			if ($blacklistCheckResult != '') {
		//				$validLicense = 0;
		//				$validLicenseText = $blacklistCheckResult;
		//			}
		//		}
		//
		//		// Use default message if necessary
		//		if ( $validLicense == 0 && empty( $validLicenseText ) ) {
		//			$validLicenseText = 'You are using an invalid serial number please check the number and contact <a href="https://www.fasttracksites.com">Paden Clayton</a> for further support.';
		//		}

		// Write the license file
		$str = "<?php\n\ndefine('A_LICENSE', '" . $serialNumber . "');\ndefine('A_LICENSED_TO', '" . $registeredTo . "');\ndefine('A_VALID_LICENSE', '" . $validLicense . "');\ndefine('A_VALID_LICENSE_TEXT', '" . htmlspecialchars( $validLicenseText ) . "');";

		$fp     = fopen( "_license.php", "w+" );
		$result = fwrite( $fp, $str );
		fclose( $fp );

		// Print this page
		$page_content = "";

		if ( ! $result || $result == "" ) {
			$page_content .= return_error_alert( 'Unable to create license file.' );
		} else {
			$page_content .= return_alert( 'Successfully created license file.' );
		}

		if ( empty( $validLicense ) ) {
			$page_content .= return_error_alert( 'You have supplied an invalid serial please check the number and try again.' );
		} else {
			$page_content .= return_alert( 'Successfully verified serial number.' );
		}

		$page_content .= '
			<br />
			<form id="databaseConnectionForm" action="install.php?step=3" method="post" class="form-horizontal" role="form">
				<fieldset>
					<legend><i class="glyphicons glyphicons-server"></i> Configure Your Database Connection</legend>
					<div class="help-block">Please enter your database information below:</div>
					<div class="form-group">
						<label for="dbServer" class="col-sm-3 control-label">Server</label> 
						<div class="col-sm-9"><input type="text" name="dbServer" id="dbServer" value="localhost" class="form-control required" /></div>
					</div>
					<div class="form-group">
						<label for="dbServerPort" class="col-sm-3 control-label">Server Port Number</label> 
						<div class="col-sm-9"><input type="text" name="dbServerPort" id="dbServerPort" value="3306" class="form-control required" /></div>
					</div>
					<div class="form-group">
						<label for="dbName" class="col-sm-3 control-label">Database Name</label> 
						<div class="col-sm-9"><input type="text" name="dbName" id="dbName" class="form-control required" /></div>
					</div>
					<div class="form-group">
						<label for="dbUsername" class="col-sm-3 control-label">Username</label> 
						<div class="col-sm-9"><input type="text" name="dbUsername" id="dbUsername" class="form-control required" /></div>
					</div>
					<div class="form-group">
						<label for="dbPassword" class="col-sm-3 control-label">Password</label> 
						<div class="col-sm-9"><input type="password" name="dbPassword" id="dbPassword" class="form-control required" /></div>
					</div>
					<div class="form-group">
						<label for="dbTablePrefix" class="col-sm-3 control-label">Table Prefix</label> 
						<div class="col-sm-9"><input type="text" name="dbTablePrefix" id="dbTablePrefix" class="form-control required" value="' . INSTALLER_SCRIPT_DB_PREFIX . '" /></div>
					</div>
					<div class="form-group"><div class="col-sm-12"><input type="submit" name="submit" class="btn btn-primary" value="Next" /></div></div>
				</fieldset>
			</form>';

		$JQueryReadyScripts = '				
			var v = jQuery("#databaseConnectionForm").validate({
				highlight: function(element) {
					$(element).closest(\'.form-group\').addClass(\'has-error\');
				},
				unhighlight: function(element) {
					$(element).closest(\'.form-group\').removeClass(\'has-error\');
				},
				errorElement: \'span\',
				errorClass: \'help-block\',
				errorPlacement: function(error, element) {
					if(element.parent(\'.input-group\').length) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				},
			});';
		break;
	case 3:
		$page->setTemplateVar( 'PageTitle', INSTALLER_SCRIPT_NAME . " Step 3 - Create database Tables" );

		// Create our database connection file
		$dbServer      = keepsafe( $_POST['dbServer'] );
		$serverPort    = keepsafe( $_POST['dbServerPort'] );
		$dbName        = keepsafe( $_POST['dbName'] );
		$dbUsername    = keepsafe( $_POST['dbUsername'] );
		$dbPassword    = keepsafe( $_POST['dbPassword'] );
		$DBTABLEPREFIX = keepsafe( $_POST['dbTablePrefix'] );

		$result = saveDatabaseFile( $dbServer, $dbName, $dbUsername, $dbPassword, $DBTABLEPREFIX, $serverPort );

		// Print this page
		$page_content = "";

		if ( ! $result || $result == "" ) {
			$page_content .= return_error_alert( 'Unable to create database connection file.' );
		} else {
			$page_content .= return_alert( 'Successfully created database connection file.' );
		}

		$page_content .= '
			<br />
			<a href="install.php?step=4" class="btn btn-primary"><i class="glyphicon glyphicon-arrow-right"></i> Create Database Tables</a>';
		break;
	case 4:
		$page->setTemplateVar( 'PageTitle', INSTALLER_SCRIPT_NAME . " Step 4 - Create Admin Account" );

		// Create our Database Tables
		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "config` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) NOT NULL DEFAULT '',
				`value` TEXT NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "config" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "categories` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`parent_id` BIGINT(19) NOT NULL DEFAULT 0,
				`name` VARCHAR(50) DEFAULT NULL,
				`type` MEDIUMINT(8) NOT NULL,
				`color` VARCHAR(50) DEFAULT NULL, # appointments
				`tags` TEXT NULL, # articles
				`order` BIGINT(19) NULL DEFAULT 999,
				`role_ids` LONGTEXT, # ticket and email templates
				PRIMARY KEY  (`id`),
				KEY `parent_id` (`parent_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "categories" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "email_templates` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`template_id` VARCHAR(255) DEFAULT NULL, #internal TEXT id FOR USE BY certain PLUGINS
				`name` VARCHAR(255) DEFAULT NULL,
				`subject` VARCHAR(255) DEFAULT NULL,
				`message` LONGTEXT NULL,
				`added_by` VARCHAR(100) DEFAULT NULL,
				`prefix` VARCHAR(100) DEFAULT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "email_templates" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "email_logs` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`sent` DATETIME NULL,
				`email_address` VARCHAR(255) DEFAULT NULL,
				`subject` VARCHAR(255) DEFAULT NULL,
				`message` LONGTEXT DEFAULT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "email_logs" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "logging` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`created` DATETIME NULL,
				`type` MEDIUMINT(8) NOT NULL,
				`assoc_id` BIGINT(19) NULL,
				`assoc_id2` BIGINT(19) NULL,
				`assoc_id3` BIGINT(19) NULL,
				`message` LONGTEXT DEFAULT NULL,
				`start` VARCHAR(25) DEFAULT NULL,
				`stop` VARCHAR(25) DEFAULT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "logging" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "menus` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(100) DEFAULT NULL,
				`added_by` VARCHAR(100) DEFAULT NULL,
				`prefix` VARCHAR(100) DEFAULT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "menus" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "menu_items` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`menu_id` BIGINT(19) NOT NULL,
				`parent_id` BIGINT(19) NOT NULL DEFAULT 0,
				`text` VARCHAR(100) DEFAULT NULL,
				`icon` VARCHAR(255) DEFAULT NULL,
				`rel` VARCHAR(255) DEFAULT NULL,
				`link` TEXT NULL,
				`added_by` VARCHAR(100) DEFAULT NULL,
				`prefix` VARCHAR(100) DEFAULT NULL,
				`order` BIGINT(19) NULL DEFAULT 999,
				`role_ids` LONGTEXT,
				PRIMARY KEY  (`id`),
				KEY `menu_id` (`menu_id`),
				KEY `parent_id` (`parent_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "menu_items" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "modules` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(100) NULL DEFAULT '',
				`description` TEXT NULL,
				`developer` VARCHAR(100) DEFAULT NULL,
				`version` VARCHAR(100) DEFAULT NULL,
				`prefix` VARCHAR(100) DEFAULT NULL,
				`active` TINYINT(1) NULL DEFAULT 1,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "modules" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "notifications` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`user_id` BIGINT(19) NULL,
				`type` BIGINT(19) NULL,
				`icon` VARCHAR(255) DEFAULT NULL,
				`message` TEXT DEFAULT NULL,
				`link` VARCHAR(255) DEFAULT NULL,
				`created` DATETIME NULL,
				`read` TINYINT(1) NOT NULL DEFAULT 0,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "notifications" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "permissions` (
				`id` MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) DEFAULT NULL,
				`file` VARCHAR(255) DEFAULT NULL,
				`role_ids` LONGTEXT,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "permissions" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "rewrites` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`match` VARCHAR(255) DEFAULT NULL,
				`query` VARCHAR(255) DEFAULT NULL,
				`added_by` VARCHAR(100) DEFAULT NULL,
				`prefix` VARCHAR(100) DEFAULT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "rewrites" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "roles` (
				`id` MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(64) NOT NULL DEFAULT '',
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "roles" );

		$sql    = "CREATE TABLE `" . USERSDBTABLEPREFIX . "users` (
				`id` MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
				`active` TINYINT(1) NULL DEFAULT 1,
				`user_level` TINYINT(4) NULL DEFAULT 0,
				`username` VARCHAR(255) NOT NULL DEFAULT '',
				`password` VARCHAR(32) NOT NULL DEFAULT '',
				`first_name` VARCHAR(50) DEFAULT NULL,
				`last_name` VARCHAR(50) DEFAULT NULL,
				`email_address` VARCHAR(255) DEFAULT NULL,
				`website` VARCHAR(255) DEFAULT NULL,
				`company` VARCHAR(255) NULL,
				`title` VARCHAR(255) DEFAULT NULL,
				`phone_number` VARCHAR(255) DEFAULT NULL,
				`facebook` VARCHAR(255) DEFAULT NULL,
				`twitter` VARCHAR(255) DEFAULT NULL,
				`google_plus` VARCHAR(255) DEFAULT NULL,
				`pinterest` VARCHAR(255) DEFAULT NULL,
				`instagram` VARCHAR(255) DEFAULT NULL,
				`linkedin` VARCHAR(255) DEFAULT NULL,
				`signup_date` DATETIME NULL,
				`token_activation` VARCHAR(50) NULL,
				`token_password_reset` VARCHAR(50) NULL,
				`token_date` DATETIME NULL,
				`notes` TEXT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "users" );

		$sql    = "CREATE TABLE `" . DBTABLEPREFIX . "widgets` (
				`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
				`widget_id` VARCHAR(100) NOT NULL DEFAULT '',
				`area` VARCHAR(100) DEFAULT NULL,
				`order` BIGINT(19) NULL DEFAULT 999,
				`type` VARCHAR(100) DEFAULT NULL,
				`settings` LONGTEXT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "widgets" );

		$sql    = "INSERT INTO `" . DBTABLEPREFIX . "config` (`name`, `value`) VALUES 
			('ftsmbp_theme', 'modern'),
			('ftsmbp_cookie_name', 'ftsmbp'),
			('ftsmbp_language', 'en'),
			('ftsmbp_active', '1'),
			('ftsmbp_inactive_msg', ''),
			('ftsmbp_time_zone', 'America/Chicago'),
			('ftsmbp_site_name', 'Modular Business Platform'),
			('ftsmbp_site_url', 'http://" . $_SERVER['HTTP_HOST'] . rtrim( dirname( $_SERVER['PHP_SELF'] ), '/\\' ) . "'),
			('ftsmbp_logo', 'http://" . $_SERVER['HTTP_HOST'] . rtrim( dirname( $_SERVER['PHP_SELF'] ), '/\\' ) . "/themes/modern/images/logo.png'),
			('ftsmbp_copyright', 'Copyright &copy; 2011 - " . date( 'Y' ) . " Paden Clayton'),
			('ftsmbp_show_powered_by', 1),
			('shown_updates_popup', 1),
			('ftsmbp_enable_logging', 0),
			('ftsmbp_logging_prune', 1),
			('ftsmbp_powered_by', 'Powered By: <a href=\"https://github.com/spyke01/Modular-Business-Platform-MBP\" rel=\"nofollow\">Modular Business Platform</a>'),
			('ftsmbp_content_dashboard', 'Welcome to the Paden Clayton Modular Business System.<br />'),
			('ftsmbp_enable_public_account_creation', 0),
			('ftsmbp_require_account_activation', 0),
			('database_version', '" . A_VERSION . "'),
			('database_version_MBP', '" . A_VERSION . "'),
			('ftsmbp_email_protocol', 'smtp');";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "configinserts" );

		$sql    = "INSERT INTO `" . DBTABLEPREFIX . "email_templates` (`id`, `template_id`, `name`, `subject`, `message`, `added_by`, `prefix`) VALUES
			(1, 'mbp-account-created', 'MBP: New Account Alert', '%site_title%: New Account', '<p>Your account on %site_title% has been created. Your login details are below:</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Username:</strong> %username%</p>\r\n<p><strong>Password:</strong> %password%</p>\r\n<p>&nbsp;</p>\r\n<p>You can now log into your new account using <a href=\"http://tagsite_url\">this link</a></p>', 'System', ''),
			(2, 'mbp-account-updated', 'MBP: Account Updated Alert', '%site_title%: Account Updated', '<p>Your account on %site_title% has been updated. Your login details are below:</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Username:</strong> %username%</p>\r\n<p><strong>Password:</strong> %password%</p>\r\n<p>&nbsp;</p>\r\n<p>You can log into your account using <a href=\"http://tagsite_url\">this link</a></p>', 'System', '');";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "emailtemplateinserts" );

		$sql    = "INSERT INTO `" . DBTABLEPREFIX . "menus` VALUES 
			(1, 'User Menu', 'System', ''),
			(2, 'Admin Menu', 'System', ''),
			(3, 'Top Menu', 'System', ''),
			(4, 'Footer Menu', 'System', '');";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "menuinserts" );

		$sql    = "INSERT INTO `" . DBTABLEPREFIX . "menu_items` (`id`, `menu_id`, `parent_id`, `text`, `link`, `added_by`, `prefix`, `order`, `role_ids`, `icon`) VALUES 
			(1, 2, 0, 'Categories', 'index.php?p=admin&s=categories', 'System', '', 0, '', 'glyphicon glyphicon-random'),
			(5, 2, 0, 'Email Users', 'index.php?p=admin&s=emailUsers', 'System', '', 8, '', 'glyphicon glyphicon-envelope'),
			(6, 2, 0, 'Graphs', 'index.php?p=admin&s=graphs', 'System', '', 2, '', 'glyphicons glyphicons-stats'),
			(7, 2, 0, 'Menus', 'index.php?p=admin&s=menus', 'System', '', 3, '', 'glyphicon glyphicon-list'),
			(8, 2, 0, 'Permissions', 'index.php?p=admin&s=permissions', 'System', '', 6, '', 'glyphicon glyphicon-lock'),
			(9, 2, 0, 'Reports', 'index.php?p=admin&s=reports', 'System', '', 1, '', 'glyphicons glyphicons-table'),
			(10, 2, 0, 'Themes', 'index.php?p=admin&s=themes', 'System', '', 5, '', 'glyphicon glyphicon-tint'),
			(11, 2, 0, 'User Administration', 'index.php?p=admin&s=users', 'System', '', 7, '', 'glyphicons glyphicons-group'),
			(12, 2, 0, 'Widgets', 'index.php?p=admin&s=widgets', 'System', '', 4, '', 'glyphicons glyphicons-cogwheels');";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "menuiteminserts" );

		$sql    = "INSERT INTO `" . DBTABLEPREFIX . "permissions` (name, file, role_ids) VALUES 
			('createMenuItem', '', '2,'),
			('deleteitem', '', ''),
			('saveMenuItems', '', '2,'),
			('menus_access', '', '2,'),
			('installModule', '', ''),
			('showSpinner', '', '-1,0,2,3,4,5,6,8,'),
			('showModuleStatusButtons', '', ''),
			('uninstallModule', '', ''),
			('activateModule', '', ''),
			('deactivateModule', '', ''),
			('clms_appointments_create', '', '2,'),
			('editUserRolePermissions', '', '');";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "permissioninserts" );

		$sql    = 'INSERT INTO `' . DBTABLEPREFIX . 'rewrites` (`match`, `query`, `added_by`) VALUES 
			(\'module/([A-Za-z-_]+)/([A-Za-z-_]+)/?$\', \'index.php?p=module&prefix=$matches[1]&module_page=$matches[2]\', \'System\'), 
			(\'module/([A-Za-z-_]+)/([A-Za-z-_]+)/([A-Za-z-_]+)/?$\', \'index.php?p=module&prefix=$matches[1]&module_page=$matches[2]&page=$matches[3]\', \'System\'), 
			(\'module/([A-Za-z-_]+)/([A-Za-z-_]+)/([0-9]+)/?$\', \'index.php?p=module&prefix=$matches[1]&module_page=$matches[2]&id=$matches[3]\', \'System\'), 
			(\'([A-Za-z-_]+)/?$\', \'index.php?p=$matches[1]\', \'System\'), 
			(\'([A-Za-z-_]+)/([A-Za-z-_]+)/?$\', \'index.php?p=$matches[1]&s=$matches[2]\', \'System\'), 
			(\'([A-Za-z-_]+)/([A-Za-z-_]+)/([0-9]+)/?$\', \'index.php?p=$matches[1]&s=$matches[2]&id=$matches[3]\', \'System\');';
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "rewriteinserts" );

		$sql    = "INSERT INTO `" . DBTABLEPREFIX . "roles` VALUES 
			(-1, 'Anonymous Access'),
			(0, 'User'),
			(2, 'Application Admin'),
			(3, 'Banned'),
			(4, 'Lead'),
			(5, 'Account Executive'),
			(6, 'Master Client'),
			(8, 'Pending Account Executive');";
		$result = $ftsdb->run( $sql );
		checkresult( $result, $sql, "roleinserts" );

		// Fix a bug where the User user role gets an ID of 1 instead of 0
		// This bug is taken into account within header.php but this makes sure our DB matches
		$result = $ftsdb->update( DBTABLEPREFIX . "roles",
			[
				"id" => '0',
			],
			"name = 'User' AND id != 0"
		);

		// Try and install any modules in the system
		$modulesInstalled = "";
		$sub_file_names   = [];

		// Themes available to all users
		$globalModulePath = BASEPATH . "/modules";
		$sub_dir_names    = getDirNames( $globalModulePath );

		// Cycle through our modules and print them out to our table
		foreach ( $sub_dir_names as $file => $path ) {
			// Include our module class file
			require_once( $path . "/" . $file . "/" . $file . ".php" );

			// Create an instance of our module class so that we can reference it for information
			$moduleInstance = new $file;

			// Install and activate the module
			callModuleHook( $file, "install", "", 0 );
			callModuleHook( $file, "activate", "", 0 );
			$modulesInstalled .= "$file, ";

			// Reset our moduleInstance variable so that we can use it again
			unset( $moduleInstance );
		}
		$modulesInstalled = rtrim( $modulesInstalled, ", " );

		// Print this page
		$page_content = "";

		if ( $totalfailure == 1 ) {
			$errors = 'Unable to create database tables. The following tables and or inserts failed:<br /><br />';

			foreach ( $failed as $table => $status ) {
				if ( $status == 'failed' ) {
					$errors .= "$table<br />";
				}
			}

			$page_content .= return_error_alert( $errors );
		} else {
			$page_content .= return_alert( 'Successfully created database tables.' );
		}

		if ( ! empty( $modulesInstalled ) ) {
			$page_content .= return_alert( 'Successfully installed modules: $modulesInstalled.' );
		}

		$page_content .= '
			<br />
			<form id="adminAccountForm" action="install.php?step=5" method="post" class="form-horizontal" role="form">
				<fieldset>
					<legend><i class="glyphicons glyphicons-user-add"></i> Create Your Admin Account</legend>
					<div class="help-block">Please enter your admin user information below:</div>
					<div class="form-group">
						<label for="username" class="col-sm-3 control-label">Username</label> 
						<div class="col-sm-9"><input type="text" name="username" id="username" class="required validate-alphanum" /></div>
					</div>
					<div class="form-group">
						<label for="email_address" class="col-sm-3 control-label">Email Address</label> 
						<div class="col-sm-9"><input type="text" name="email_address" id="email_address" class="required validate-email" /></div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-3 control-label">Password</label> 
						<div class="col-sm-9"><input type="password" name="password" id="password" class="required validate-password" /></div>
					</div>
					<div class="form-group">
						<label for="usrConfirmPassword" class="col-sm-3 control-label">Confirm Password</label> 
						<div class="col-sm-9"><input type="password" name="usrConfirmPassword" id="usrConfirmPassword" class="required validate-password-confirm" /></div>
					</div>
					<div class="form-group"><div class="col-sm-12"><input type="submit" name="submit" class="btn btn-primary" value="Next" /></div></div>
				</fieldset>
			</form>';

		$JQueryReadyScripts = '				
			var v = jQuery("#adminAccountForm").validate({
				highlight: function(element) {
					$(element).closest(\'.form-group\').addClass(\'has-error\');
				},
				unhighlight: function(element) {
					$(element).closest(\'.form-group\').removeClass(\'has-error\');
				},
				errorElement: \'span\',
				errorClass: \'help-block\',
				errorPlacement: function(error, element) {
					if(element.parent(\'.input-group\').length) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				},
				rules: {
					usrConfirmPassword: {
						equalTo: "#password",
						minlength: 5
					}
				},
			});';
		break;
	case 5:
		$page->setTemplateVar( 'PageTitle', INSTALLER_SCRIPT_NAME . " Step 5 - Finish" );

		// Create our admin account
		$username      = keepsafe( $_POST['username'] );
		$password      = md5( $_POST['password'] );
		$email_address = sanitize_email( $_POST['email_address'] );

		$result = $ftsdb->insert( USERSDBTABLEPREFIX . 'users',
			[
				"username"      => $username,
				"password"      => $password,
				"email_address" => $email_address,
				"signup_date"   => mysqldatetime(),
				"notes"         => '',
				"user_level"    => '1',
			] );
		checkresult( $result, $sql, "AdminUser" );

		$result = $ftsdb->insert( DBTABLEPREFIX . 'config',
			[
				'name'  => 'ftsmbp_system_email_address',
				'value' => $email_address,
			] );
		checkresult( $result, $sql, "configinsert" );

		// Print this page
		$page_content = "";

		if ( $totalfailure == 1 ) {
			$errors = 'Unable to create admin account. The following tables and or inserts failed:<br /><br />';

			foreach ( $failed as $table => $status ) {
				if ( $status == 'failed' ) {
					$errors .= "$table<br />";
				}
			}

			$page_content .= return_error_alert( $errors );
		} else {
			$page_content .= return_alert( 'Successfully created admin account.' );
		}

		$page_content .= '
			<h3>Installation Complete</h3>
			Before using the system please make sure and delete this file (install.php) so that it cannot be reused by someone else.
			<br /><br />
			<a href="index.php" class="btn btn-primary">Finish</a>';
		break;
}

// Send out the content
$page->setTemplateVar( 'PageContent', $page_content );
$page->setTemplateVar( 'JQueryReadyScript', $JQueryReadyScripts );

include BASEPATH . '/themes/installer/template.php';