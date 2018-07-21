<?PHP 
/***************************************************************************
 *                               header.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


ini_set( 'arg_separator.output','&amp;' );
error_reporting( E_ALL & ~(E_STRICT|E_NOTICE) );
//error_reporting( E_ALL );
//ini_set( 'display_errors', '0' );
//ini_set( 'display_errors', '1' );
ob_start( 'ob_gzhandler' );
session_start();

// We calculate offsets from UTC.
date_default_timezone_set( 'UTC' ); // This means that time() will always return UTC. When using the date_i18n() or other date functions, the timzone will be calculated

// Variables
$available_widgets = $available_widgetAreas = $modules = $mbp_filter = $merged_filters = $mbp_current_filter = $mbp_actions = $mbp_config = $mbp_update_data = array();
global $mbp_config, $ftsdb; 

// Do our main includes
include(BASEPATH . '/includes/classes/ftsdb.php');
//include(BASEPATH . '/includes/classes/ftsdb_mysql.php'); Not ready
include(BASEPATH . '/_license.php');
include(BASEPATH . '/_db.php');
include(BASEPATH . '/includes/classes/file-functions.php');
include(BASEPATH . '/includes/classes/fts-widget.php');
include(BASEPATH . '/includes/menu.php');
include(BASEPATH . '/includes/functions.php');
include(BASEPATH . '/includes/constants.php');
include(BASEPATH . '/includes/classes/fts-http.php');
include(BASEPATH . '/includes/classes/fts-locale.php');
include(BASEPATH . '/includes/classes/fts-map-rewrite-matches.php');
include(BASEPATH . '/includes/classes/pageclass.php');
include(BASEPATH . '/includes/classes/tableclass.php');
include(BASEPATH . '/includes/classes/phpmailer/PHPMailerAutoload.php');

// Increase our memory limit
increase_memory_limit();

// Include our class autoloader to enable MVC items
include(BASEPATH . '/vendor/autoload.php');

// Set up our Uses
use Illuminate\Database\Capsule\Manager as DB;
use App\Support\Registry;
use App\View\ViewFileFinder;

// Instantiate our classes
$fts_http = new fts_http; //initialize our curl handler
$fts_locale = new FTS_Locale();
$page = new pageClass; //initialize our page
Registry::add( $page ); // Store this in the registry so our views can access it
$viewFileFinder = new ViewFileFinder; // Needed for our views
Registry::add( $viewFileFinder, 'viewfilefinder' ); // Store this in the registry so our views can access it

// Create our database connection
$ftsdb = connectToDB( $server, $dbname, $dbuser, $dbpass, $serverPort );
//$ftsdb->profile = 1;
$ftsdb->setErrorCallbackFunction('echo');
$ftsdb->setProfileCallbackFunction('echo');

// Create our eloquent DB connection
$capsule = new DB;
$capsule->addConnection([
	'driver' => 'mysql',
    'host'      => $server . ':' . $serverPort,
    'database'  => $dbname,
    'username'  => $dbuser,
    'password'  => $dbpass,
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => DBTABLEPREFIX,
	'strict' => false, // This is needed since eloquent throws errors about no default set when there is one in the db see https://goo.gl/YSPGyL
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Continue with the includes
load_config_values();
handleWWWRedirection();

define('SITE_URL', $mbp_config['ftsmbp_site_url']);
$filepath = rtrim(str_replace(array('http://', 'https://'), '', SITE_URL), '/');
define('FILEPATH', substr($filepath, strpos($filepath, '/')));

// include our theme functions file
$themeFunctionsFile = BASEPATH . '/themes/' . $mbp_config['ftsmbp_theme'] . '/functions.php';
if (is_file($themeFunctionsFile)) include($themeFunctionsFile);

// Prep env values
if ( get_magic_quotes_gpc() ) {
    $_POST      = array_map( 'stripslashes_deep', $_POST );
    $_GET       = array_map( 'stripslashes_deep', $_GET );
    $_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
    $_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );
}

// Set PHP error handler
set_error_handler( defined('MBP_MSG_HANDLER') ? MBP_MSG_HANDLER : 'msg_handler' );

// Define our user levels for easy access
createUserRoleDefinitions();

// Initialize our modules
initializeModules();

// Include files requested by modules
// Since we could include variables within the files that need to be accessed globally we cannot make this into a function
$extraIncludes = callModuleHook('', 'returnIncludes');
$extraIncludes = explode(';', $extraIncludes);
//print_r($extraIncludes);

if (count($extraIncludes)) {
	foreach ($extraIncludes as $key => $includeMe) {
		if (!empty($includeMe)) include($includeMe);
	}
}

// Perform any needed configuration of variables or other items for each run
callModuleHook('', 'prepSettings');

// We now have any custom widget areas registered into the $available_widgetAreas var 
// so check the DB and make sure we don't have any orphan widget areas
checkWidgetAreas();

// Handle our widgets
callModuleHook('', 'registerWidgets');

// Check our session and login cookie
checkSessionCookie();