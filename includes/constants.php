<?php 
/***************************************************************************
 *                               constants.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/



//=====================================================
// Application
//=====================================================
define( 'A_NAME', 'fts_mbp' );
define( 'A_VERSION', '{{ VERSION }}' );
define( 'APP_PREFIX', 'ftsmbp' );

//=====================================================
// Debug Level
//=====================================================
//define( 'DEBUG', 1 ); // Debugging on
define( 'DEBUG', 0 ); // Debugging off

//=====================================================
// Global state
//=====================================================
define( 'ACTIVE', 1 );
define( 'INACTIVE', 0 );

//=====================================================
// Urgency
//=====================================================
define( 'LOW', 0 );
define( 'MEDIUM', 1 );
define( 'HIGH', 2 );

//=====================================================
// User Levels <- Do not change these values!!
//=====================================================
/*
define('ANONYMOUS_ACCESS', -1);
define('USER', 0);
define('SYSTEM_ADMIN', 1);
define('APPLICATION_ADMIN', 2);
define('BANNED', 3);
define('LEAD', 4);
define('ACCOUNT_EXECUTIVE', 5);
define('MASTER_CLIENT', 6);
define('PENDING_ACCOUNT_EXECUTIVE', 8);
*/

//=====================================================
// File Permission
//=====================================================
if ( ! defined('FS_CHMOD_DIR') )
	define('FS_CHMOD_DIR', ( fileperms( ABSPATH ) & 0777 | 0755 ) );
if ( ! defined('FS_CHMOD_FILE') )
	define('FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );

//=====================================================
// Logging Types
//=====================================================
define( 'LOG_TYPE_LOGIN', 10 );
define( 'LOG_TYPE_LOGIN_FAIL', 11 );
define( 'LOG_TYPE_UPDATE', 20 );
define( 'LOG_TYPE_CRON', 30 );
define( 'LOG_TYPE_USER', 40 );
define( 'LOG_TYPE_USER_CREATE', 41 );
define( 'LOG_TYPE_USER_DELETE', 42 );
define( 'LOG_TYPE_USER_UPDATE', 43 );
define( 'LOG_TYPE_PAGE', 50 );
define( 'LOG_TYPE_EMAIL', 60 );
define( 'LOG_TYPE_EMAIL_SEND_SUCCESS', 61 );
define( 'LOG_TYPE_EMAIL_SEND_FAIL', 62 );

$LOG_TYPES = array(
	LOG_TYPE_LOGIN => 'Login',
	LOG_TYPE_LOGIN_FAIL => 'Login Failure',
	LOG_TYPE_UPDATE => 'Update',
	LOG_TYPE_CRON => 'Cron',
	LOG_TYPE_USER => 'User',
	LOG_TYPE_USER_CREATE => 'Create User',
	LOG_TYPE_USER_DELETE => 'Delete User',
	LOG_TYPE_USER_UPDATE => 'Update User',
	LOG_TYPE_PAGE => 'Load Page',
);

//============================
// Category Type
//============================
$CATEGORY_TYPE = array(
	'0' => 'Appointment',
	'1' => 'Article',
	'2' => 'Blog',
	'3' => 'Client',
	'4' => 'Email Template',
	'5' => 'Ticket',
	'6' => 'Serial Number'
);

//============================
// Notification Types
//============================
define( 'NOTIFICATION_TYPE_STORE', 10 );
define( 'NOTIFICATION_TYPE_UPDATE', 20 );
define( 'NOTIFICATION_TYPE_CRON', 30 );
define( 'NOTIFICATION_TYPE_USER', 40 );
define( 'NOTIFICATION_TYPE_SYSTEM_ERROR', 50 );
define( 'NOTIFICATION_TYPE_SYSTEM_WARNING', 51 );
define( 'NOTIFICATION_TYPE_SYSTEM_SUCCESS', 52 );

$NOTIFICATION_TYPES = array(
    NOTIFICATION_TYPE_STORE => array(
		'title' => 'Store',
		'icon' => 'fa fa-truck',
		'colorClass' => 'info',
	),
    NOTIFICATION_TYPE_UPDATE => array(
		'title' => 'Update',
		'icon' => 'fa fa-life-ring',
		'colorClass' => 'info',
	),
	NOTIFICATION_TYPE_CRON => array(
		'title' => 'Cron',
		'icon' => 'fa fa-clock-o',
		'colorClass' => 'default',
	),
	NOTIFICATION_TYPE_USER => array(
		'title' => 'User',
		'icon' => 'fa fa-user',
		'colorClass' => 'info',
	),
    NOTIFICATION_TYPE_SYSTEM_ERROR => array(
        'title' => 'System',
        'icon' => 'fa fa-hdd-o',
        'colorClass' => 'danger',
    ),
    NOTIFICATION_TYPE_SYSTEM_WARNING => array(
		'title' => 'System',
		'icon' => 'fa fa-hdd-o',
		'colorClass' => 'warning',
	),
    NOTIFICATION_TYPE_SYSTEM_SUCCESS => array(
		'title' => 'System',
		'icon' => 'fa fa-hdd-o',
		'colorClass' => 'success',
	),
);

//=====================================================
// System Settings
//=====================================================
// Constants for expressing human-readable intervals
// in their respective number of seconds.
define( 'MINUTE_IN_SECONDS', 60 );
define( 'HOUR_IN_SECONDS',   60 * MINUTE_IN_SECONDS );
define( 'DAY_IN_SECONDS',    24 * HOUR_IN_SECONDS   );
define( 'WEEK_IN_SECONDS',    7 * DAY_IN_SECONDS    );
define( 'YEAR_IN_SECONDS',  365 * DAY_IN_SECONDS    );
	
$FTS_COUNTRIES = array("USA" => "United States", "CAN" => "Canada", "MEX" => "Mexico", "AFG" => "Afghanistan", "ALB" => "Albania", "DZA" => "Algeria", "ASM" => "American Samoa", "AND" => "Andorra", "AGO" => "Angola", "AIA" => "Anguilla", "ATA" => "Antarctica", "ATG" => "Antigua and Barbuda", "ARG" => "Argentina", "ARM" => "Armenia", "ABW" => "Aruba", "AUS" => "Australia", "AUT" => "Austria", "AZE" => "Azerbaijan", "BHS" => "Bahamas", "BHR" => "Bahrain", "BGD" => "Bangladesh", "BRB" => "Barbados", "BLR" => "Belarus", "BEL" => "Belgium", "BLZ" => "Belize", "BEN" => "Benin", "BMU" => "Bermuda", "BTN" => "Bhutan", "BOL" => "Bolivia", "BIH" => "Bosnia and Herzegowina", "BWA" => "Botswana", "BVT" => "Bouvet Island", "BRA" => "Brazil", "IOT" => "British Indian Ocean Terr.", "BRN" => "Brunei Darussalam", "BGR" => "Bulgaria", "BFA" => "Burkina Faso", "BDI" => "Burundi", "KHM" => "Cambodia", "CMR" => "Cameroon", "CPV" => "Cape Verde", "CYM" => "Cayman Islands", "CAF" => "Central African Republic", "TCD" => "Chad", "CHL" => "Chile", "CHN" => "China", "CXR" => "Christmas Island", "CCK" => "Cocos (Keeling) Islands", "COL" => "Colombia", "COM" => "Comoros", "COG" => "Congo", "COK" => "Cook Islands", "CRI" => "Costa Rica", "CIV" => "Cote d'Ivoire", "HRV" => "Croatia (Hrvatska)", "CUB" => "Cuba", "CYP" => "Cyprus", "CZE" => "Czech Republic", "DNK" => "Denmark", "DJI" => "Djibouti", "DMA" => "Dominica", "DOM" => "Dominican Republic", "TMP" => "East Timor", "ECU" => "Ecuador", "EGY" => "Egypt", "SLV" => "El Salvador", "GNQ" => "Equatorial Guinea", "ERI" => "Eritrea", "EST" => "Estonia", "ETH" => "Ethiopia", "FLK" => "Falkland Islands/Malvinas", "FRO" => "Faroe Islands", "FJI" => "Fiji", "FIN" => "Finland", "FRA" => "France", "FXX" => "France, Metropolitan", "GUF" => "French Guiana", "PYF" => "French Polynesia", "ATF" => "French Southern Terr.", "GAB" => "Gabon", "GMB" => "Gambia", "GEO" => "Georgia", "DEU" => "Germany", "GHA" => "Ghana", "GIB" => "Gibraltar", "GRC" => "Greece", "GRL" => "Greenland", "GRD" => "Grenada", "GLP" => "Guadeloupe", "GUM" => "Guam", "GTM" => "Guatemala", "GIN" => "Guinea", "GNB" => "Guinea-Bissau", "GUY" => "Guyana", "HTI" => "Haiti", "HMD" => "Heard & McDonald Is.", "HND" => "Honduras", "HKG" => "Hong Kong", "HUN" => "Hungary", "ISL" => "Iceland", "IND" => "India", "IDN" => "Indonesia", "IRN" => "Iran", "IRQ" => "Iraq", "IRL" => "Ireland", "ISR" => "Israel", "ITA" => "Italy", "JAM" => "Jamaica", "JPN" => "Japan", "JOR" => "Jordan", "KAZ" => "Kazakhstan", "KEN" => "Kenya", "KIR" => "Kiribati", "PRK" => "Korea, North", "KOR" => "Korea, South", "KWT" => "Kuwait", "KGZ" => "Kyrgyzstan", "LAO" => "Lao People's Dem. Rep.", "LVA" => "Latvia", "LBN" => "Lebanon", "LSO" => "Lesotho", "LBR" => "Liberia", "LBY" => "Libyan Arab Jamahiriya", "LIE" => "Liechtenstein", "LTU" => "Lithuania", "LUX" => "Luxembourg", "MAC" => "Macau", "MKD" => "Macedonia", "MDG" => "Madagascar", "MWI" => "Malawi", "MYS" => "Malaysia", "MDV" => "Maldives", "MLI" => "Mali", "MLT" => "Malta", "MHL" => "Marshall Islands", "MTQ" => "Martinique", "MRT" => "Mauritania", "MUS" => "Mauritius", "MYT" => "Mayotte", "FSM" => "Micronesia", "MDA" => "Moldova", "MCO" => "Monaco", "MNG" => "Mongolia", "MSR" => "Montserrat", "MAR" => "Morocco", "MOZ" => "Mozambique", "MMR" => "Myanmar", "NAM" => "Namibia", "NRU" => "Nauru", "NPL" => "Nepal", "NLD" => "Netherlands", "ANT" => "Netherlands Antilles", "NCL" => "New Caledonia", "NZL" => "New Zealand", "NIC" => "Nicaragua", "NER" => "Niger", "NGA" => "Nigeria", "NIU" => "Niue", "NFK" => "Norfolk Island", "MNP" => "Northern Mariana Is.", "NOR" => "Norway", "OMN" => "Oman", "PAK" => "Pakistan", "PLW" => "Palau", "PAN" => "Panama", "PNG" => "Papua New Guinea", "PRY" => "Paraguay", "PER" => "Peru", "PHL" => "Philippines", "PCN" => "Pitcairn", "POL" => "Poland", "PRT" => "Portugal", "PRI" => "Puerto Rico", "QAT" => "Qatar", "REU" => "Reunion", "ROM" => "Romania", "RUS" => "Russian Federation", "RWA" => "Rwanda", "KNA" => "Saint Kitts and Nevis", "LCA" => "Saint Lucia", "VCT" => "St. Vincent & Grenadines", "WSM" => "Samoa", "SMR" => "San Marino", "STP" => "Sao Tome & Principe", "SAU" => "Saudi Arabia", "SEN" => "Senegal", "SYC" => "Seychelles", "SLE" => "Sierra Leone", "SGP" => "Singapore", "SVK" => "Slovakia (Slovak Republic)", "SVN" => "Slovenia", "SLB" => "Solomon Islands", "SOM" => "Somalia", "ZAF" => "South Africa", "SGS" => "S.Georgia & S.Sandwich Is.", "ESP" => "Spain", "LKA" => "Sri Lanka", "SHN" => "St. Helena", "SPM" => "St. Pierre & Miquelon", "SDN" => "Sudan", "SUR" => "Suriname", "SJM" => "Svalbard & Jan Mayen Is.", "SWZ" => "Swaziland", "SWE" => "Sweden", "CHE" => "Switzerland", "SYR" => "Syrian Arab Republic", "TWN" => "Taiwan", "TJK" => "Tajikistan", "TZA" => "Tanzania", "THA" => "Thailand", "TGO" => "Togo", "TKL" => "Tokelau", "TON" => "Tonga", "TTO" => "Trinidad and Tobago", "TUN" => "Tunisia", "TUR" => "Turkey", "TKM" => "Turkmenistan", "TCA" => "Turks & Caicos Islands", "TUV" => "Tuvalu", "UGA" => "Uganda", "UKR" => "Ukraine", "ARE" => "United Arab Emirates", "GBR" => "United Kingdom", "UMI" => "U.S. Minor Outlying Is.", "URY" => "Uruguay", "UZB" => "Uzbekistan", "VUT" => "Vanuatu", "VAT" => "Vatican (Holy See)", "VEN" => "Venezuela", "VNM" => "Viet Nam", "VGB" => "Virgin Islands (British)", "VIR" => "Virgin Islands (U.S.)", "WLF" => "Wallis & Futuna Is.", "ESH" => "Western Sahara", "YEM" => "Yemen", "YUG" => "Yugoslavia", "ZAR" => "Zaire", "ZMB" => "Zambia", "ZWE" => "Zimbabwe");

$FTS_STATES = array(
	"AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona", "AR" => "Arkansas", "CA" => "California", 
	"CO" => "Colorado", "CT" => "Connecticut", "DE" => "Delaware", "DC" => "District of Columbia", "FL" => "Florida", 
	"GA" => "Georgia", "HI" => "Hawaii", "ID" => "Idaho", "IL" => "Illinois", "IN" => "Indiana", "IA" => "Iowa", 
	"KS" => "Kansas", "KY" => "Kentucky", "LA" => "Louisiana", "ME" => "Maine", "MD" => "Maryland", 
	"MA" => "Massachusetts", "MI" => "Michigan", "MN" => "Minnesota", "MS" => "Mississippi", "MO" => "Missouri", 
	"MT" => "Montana", "NE" => "Nebraska", "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey", 
	"NM" => "New Mexico", "NY" => "New York", "NC" => "North Carolina", "ND" => "North Dakota", "OH" => "Ohio",
	"OK" => "Oklahoma", "OR" => "Oregon", "PA" => "Pennsylvania", "RI" => "Rhode Island", "SC" => "South Carolina", 
	"SD" => "South Dakota", "TN" => "Tennessee", "TX" => "Texas", "UT" => "Utah", "VT" => "Vermont", 
	"VA" => "Virginia", "WA" => "Washington", "WV" => "West Virginia", "WI" => "Wisconsin", "WY" => "Wyoming"
);

/**#@+
 * Constants for expressing human-readable data sizes in their respective number of bytes.
 *
 * @since 4.4.0
 */
define( 'KB_IN_BYTES', 1024 );
define( 'MB_IN_BYTES', 1024 * KB_IN_BYTES );
define( 'GB_IN_BYTES', 1024 * MB_IN_BYTES );
define( 'TB_IN_BYTES', 1024 * GB_IN_BYTES );
/**#@-*/