<?php 
/***************************************************************************
 *                               index.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


// If the db connection file is missing we should redirect the user to install page
if ( !file_exists('_db.php') ) {
	header( "Location: http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/install.php" );	
	exit();
}

/* Define our Paths */
define('ABSPATH', dirname(__FILE__) . '/');
define('BASEPATH', rtrim(ABSPATH, '/'));

include BASEPATH . '/includes/header.php';

// Make sure we are in the https version if needed
if ( !is_https() && $mbp_config['ftsmbp_use_https'] == 1 ){
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $redirect");
}
	

//print_r($_SESSION);
// If we have mod_rewrite enabled then we need to pull our variables
if ($mbp_config['ftsmbp_mod_rewrite']) { parseRewrites(); }

$actual_page_id = ( !empty( $_GET['p'] ) ) ? keepsafe( $_GET['p'] ) : 1;
if ( isset( $_GET['s'] ) ) $actual_section = keepsafe( $_GET['s']) ;
if ( isset( $_GET['id'] ) ) $actual_id = intval( $_GET['id']);
$actual_action = ( isset( $_GET['action'] ) ) ? keepsafe( $_GET['action'] ) : '';
$actual_action2 = ( isset( $_GET['action2'] ) ) ? keepsafe( $_GET['action2'] ) : '';
$actual_style = ( isset( $_GET['style'])) ? keepsafe( $_GET['style'] ) : '';
$actual_report = ( isset( $_GET['report'] ) ) ? keepsafe( $_GET['report'] ) : '';
$actual_prefix = ( isset( $_GET['prefix'] ) ) ? keepsafe( $_GET['prefix'] ) : '';
$actual_module_page = ( isset( $_GET['module_page'] ) ) ? keepsafe( $_GET['module_page'] ) : '';
$actual_page = ( isset( $_GET['page'] ) ) ? keepsafe( $_GET['page'] ) : '';
$actual_startsWith = ( isset( $_GET['startsWith'] ) ) ? keepsafe( $_GET['startsWith'] ) : '';
$page_content = "";

// Warn the user if the install.php script is present
if ( file_exists( 'install.php' ) ) {
	$page_content = return_error_alert( 'Warning: install.php is present, please remove this file for security reasons.' );
}

// We want to show all of our menus by default
$page->setTemplateVar( 'sidebar_active', ACTIVE );

// Set theme values
$page->setTemplateVar( 'Theme', $mbp_config['ftsmbp_theme'] );
if (isset($actual_style) && $actual_style == "printerFriendly") $page->setTemplateVar('Template', 'printerFriendlyTemplate.php');
// Page Specific Template
elseif ( file_exists( "themes/" . $mbp_config['ftsmbp_theme'] . "/template-" . $actual_page_id . ".php" ) ) { $page->setTemplateVar( 'Template', 'template-' . $actual_page_id . '.php' ); }
// Base Template
else $page->setTemplateVar( 'Template', 'template.php' );


// Log this page load
if ( isset( $_SESSION['userid'] ) ) {
	$message = 'Loading Page ID: ' . $actual_page_id;
	if ( !empty( $actual_section ) ) { $message .= ', Section: ' . $actual_section; }
	if ( !empty( $actual_prefix ) ) { $message .= ', Prefix: ' . $actual_prefix; }
	if ( !empty( $actual_module_page ) ) { $message .= ', Module Page: ' . $actual_module_page; }
	if ( !empty( $actual_page ) ) { $message .= ', Page: ' . $actual_page; }
	addLogEvent( array(
		'type' => LOG_TYPE_PAGE,
		'message' => $message,
		'assoc_id' => $_SESSION['userid'],
	) );
}

//========================================
// Logout Function
//========================================
// Prevent spanning between apps to avoid a user getting more acces that they are allowed
if ( $_SESSION['script_locale'] != rtrim( dirname( $_SERVER['PHP_SELF'] ), '/\\' ) && isset( $_SESSION['userid'] ) ) {
	session_destroy();
}

if ( $actual_page_id == 'logout' ) {	
	//Destroy Session Cookie
	$cookiename = $mbp_config['ftsmbp_cookie_name'];
	unset( $_COOKIE[$cookiename] );
	setcookie( $cookiename, '', time() - 2592000, '/' ); //set cookie to delete back for 1 month
	
	//Destroy Session
	session_destroy();
	header("Location: http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/index.php");	
	exit();
}

// Top Menus
$page->makeMenuItem("top", "Home", "index.php", "");
		
// Call home and license check
version_functions();
//Check to see if advanced options are allowed or not
if ( A_VALID_LICENSE || $actual_page_id == "version" ) {
	// If the system is locked, then only a moderator or admin should be able to view it
	if ( $_SESSION['user_level'] != SYSTEM_ADMIN && $_SESSION['user_level'] != APPLICATION_ADMIN && $mbp_config['ftsmbp_active'] != ACTIVE ) {
		if ( $actual_page_id == 'login' ) {
			include 'login.php';
		} else {	
			$page->setTemplateVar('PageTitle', 'Currently Disabled');
			$page->setTemplateVar('PageContent', bbcode($mbp_config['ftsmbp_inactive_msg']));
		
			// Let us login so we can access the system during a shutdown
			$page->setTemplateVar('PageTitle', "Login");	
			$page->addBreadCrumb("Login", $menuvar['LOGIN']);
			if ( !isset( $_SESSION['userid'] ) ) {
				include 'login.php';
			}
					
			// We want to show all of our menus by default
			$page->setTemplateVar("aOLm_active", INACTIVE);
			$page->makeMenuItem("userOptionsLeft", "Login", "index.php?p=login", "");
		}
	} else {
		//========================================
		// Page Load Settings
		//========================================
		if ($actual_page_id == "activateAccount") {
			$page->setTemplateVar('PageTitle', "Activate Account");	
			$page->addBreadCrumb( 'Home', $menuvar['HOME'], 'glyphicon glyphicon-home' );
			$page->addBreadCrumb("Activate Account", $menuvar['ACTIVATEACCOUNT']);
			include 'activate-account.php';
		} elseif ($actual_page_id == "admin") {
			// Add breadcrumb pointing home
			$page->addBreadCrumb( 'Admin', $menuvar['ADMIN'], 'glyphicon glyphicon-home' );
			
			if (!$_SESSION['username']) { include 'login.php'; } else {
				// Handle permissions items
				if (user_access('email_users_access')&& $actual_section == "emailUsers") {
					$page->setTemplateVar('PageTitle', "Email Users");		
					$page->addBreadCrumb("Email Users", $menuvar['EMAILUSERS']);
					include 'email-users.php';
				} elseif (user_access('graphs_access')&& $actual_section == "graphs") {
					$page->setTemplateVar('PageTitle', "Graphs");		
					$page->addBreadCrumb("Graphs", $menuvar['GRAPHS']);
					include 'graphs.php';
				} elseif (user_access('reports_access') && $actual_section == "reports") {
					$page->setTemplateVar('PageTitle', "Reports");		
					$page->addBreadCrumb("Reports", $menuvar['REPORTS']);
					include 'reports.php';
				} elseif (user_access('widgets_access') && $actual_section == "widgets") {
					$page->setTemplateVar('PageTitle', "Widgets");		
					$page->addBreadCrumb("Widgets", $menuvar['WIDGETS']);
					include 'widgets.php';
				} elseif ($_SESSION['user_level'] == APPLICATION_ADMIN || $_SESSION['user_level'] == SYSTEM_ADMIN) {
					if ($actual_section == "" || !isset($actual_section)) {
						$page->setTemplateVar('PageTitle', "Admin Panel");
						include 'dashboard.php'; 
					} elseif ($actual_section == "categories") {
						$page->setTemplateVar('PageTitle', "Client Categories");	
						$page->addBreadCrumb("Categories", $menuvar['CATEGORIES']);
						include 'categories.php';
					} elseif ($actual_section == "menus") {
						$page->setTemplateVar('PageTitle', "Menus");		
						$page->addBreadCrumb("Menus", $menuvar['MENUS']);
						include 'menus.php';
					} elseif ($actual_section == "modules") {
						$page->setTemplateVar('PageTitle', "Modules");		
						$page->addBreadCrumb("Modules", $menuvar['MODULES']);
						include 'modules.php';
					} elseif ($actual_section == "permissions") {
						$page->setTemplateVar('PageTitle', "Permissions");		
						$page->addBreadCrumb("Permissions", $menuvar['PERMISSIONS']);
						include 'permissions.php';
					} elseif ($actual_section == "settings") {
						$page->setTemplateVar('PageTitle', "Settings");
						$page->addBreadCrumb("Settings", $menuvar['SETTINGS']);
						include 'settings.php';
					} elseif ($actual_section == "themes") {
						$page->setTemplateVar('PageTitle', "Themes");
						$page->addBreadCrumb("Themes", $menuvar['THEMES']);
						include 'themes.php';
					} elseif ($actual_section == "users") {
						$page->setTemplateVar('PageTitle', "Users");	
						$page->addBreadCrumb("Users", $menuvar['USERS']);
						include 'users.php';
					} elseif ($actual_section == "notifications") {
                    $page->setTemplateVar('PageTitle', "Notifications");
                    $page->addBreadCrumb("Notifications", $menuvar['NOTIFICATIONS']);
                    include 'notifications.php';
                }
				} else { $page->setTemplateVar('PageContent', notAuthorizedNotice()); }
			}
		} elseif ($actual_page_id == "createAccount") {
			$page->setTemplateVar('PageTitle', "Create Account");	
			$page->addBreadCrumb( 'Home', $menuvar['HOME'], 'glyphicon glyphicon-home' );
			$page->addBreadCrumb("Create Account", $menuvar['CREATEACCOUNT']);
			include 'create-account.php';
		} elseif ($actual_page_id == "forgotPassword") {
			$page->setTemplateVar('PageTitle', "Forgot Password");	
			$page->addBreadCrumb( 'Home', $menuvar['HOME'], 'glyphicon glyphicon-home' );
			$page->addBreadCrumb("Forgot Password", $menuvar['FORGOTPASSWORD']);
			include 'forgot-password.php';
		} elseif ($actual_page_id == "login") {
			$page->setTemplateVar('PageTitle', "Login");	
			$page->addBreadCrumb( 'Home', $menuvar['HOME'], 'glyphicon glyphicon-home' );
			$page->addBreadCrumb("Login", $menuvar['LOGIN']);
			include 'login.php';
		} elseif ($actual_page_id == "module") {
			$page->addBreadCrumb( 'Home', $menuvar['HOME'], 'glyphicon glyphicon-home' );
			include 'module.php';
		} elseif ($actual_page_id == "version") {
			$page->setTemplateVar('PageTitle', "Version Information");	
			$page->addBreadCrumb( 'Home', $menuvar['HOME'], 'glyphicon glyphicon-home' );
			$page->addBreadCrumb("Version Information", "");
			version_functions( '', 1 );
			
			$page->setTemplateVar('PageContent', returnAppVersionInfo());	
		} else {		
			$page->setTemplateVar('PageTitle', "Home");	
			$page->addBreadCrumb( 'Home', $menuvar['HOME'], 'glyphicon glyphicon-home' );
			
			if (!isset($_SESSION['username'])) {
				$page->setTemplateVar('PageTitle', "Login");	
				$page->addBreadCrumb("Login", $menuvar['LOGIN']);
				include 'login.php';
			} else {
				$page->setTemplateVar('PageTitle', "Dashboard");
				$page->addBreadCrumb("Dashboard", $menuvar['DASHBOARD']);
				include 'dashboard.php'; 	
			}
			
			callModuleHook('', 'homePage');
			callModuleHook('', 'changePageTemplate');
		}
		
		// Add the menus to the page - duh :)
		addMenusToPage();		
	}
} else { $page->setTemplateVar('PageContent', A_VALID_LICENSE_TEXT); }

$ftsdb->profile();
// Include our template file
include BASEPATH . '/themes/' . $page->getTemplateVar('Theme') . '/' . $page->getTemplateVar('Template');