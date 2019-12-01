<?php
/***************************************************************************
 *                               general.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/

/**
 * Create our database settings file.
 *
 * @param        $dbServer
 * @param        $dbName
 * @param        $dbUsername
 * @param        $dbPassword
 * @param        $DBTABLEPREFIX
 * @param string $serverPort
 *
 * @return false|int
 */
function saveDatabaseFile( $dbServer, $dbName, $dbUsername, $dbPassword, $DBTABLEPREFIX, $serverPort = '3306' ) {
	$str = "<?php\n\n// Connect to the database\n\n\$server = \"" . $dbServer . "\";\n\$serverPort = \"" . $serverPort . "\";\n\$dbuser = \"" . $dbUsername . "\";\n\$dbpass = \"" . $dbPassword . "\";\n\$dbname = \"" . $dbName . "\";\ndefine('DBTABLEPREFIX', '" . $DBTABLEPREFIX . "');\ndefine('USERSDBTABLEPREFIX', '" . $DBTABLEPREFIX . "');\n\n\$connect = mysql_connect(\$server,\$dbuser,\$dbpass);\n\n//display error if connection fails\nif (\$connect==FALSE) {\n   print 'Unable to connect to database: '.mysql_error();\n   exit;\n}\n\nmysql_select_db(\$dbname); // select database";

	$fp     = fopen( "_db.php", "w+" );
	$result = fwrite( $fp, $str );
	fclose( $fp );

	return $result;
}

/**
 * Converts a shorthand byte value to an integer byte value.
 *
 * @param string $value A (PHP ini) byte value, either shorthand or ordinary.
 *
 * @return int An integer byte value.
 * @link  https://secure.php.net/manual/en/function.ini-get.php
 * @link  https://secure.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
 *
 * @since 2.3.0
 * @since 4.6.0 Moved from media.php to load.php.
 *
 */
function convert_hr_to_bytes( $value ) {
	$value = strtolower( trim( $value ) );
	$bytes = (int) $value;

	if ( false !== strpos( $value, 'g' ) ) {
		$bytes *= GB_IN_BYTES;
	} elseif ( false !== strpos( $value, 'm' ) ) {
		$bytes *= MB_IN_BYTES;
	} elseif ( false !== strpos( $value, 'k' ) ) {
		$bytes *= KB_IN_BYTES;
	}

	// Deal with large (float) values which run into the maximum integer size.
	return min( $bytes, PHP_INT_MAX );
}

/**
 * Determines whether a PHP ini value is changeable at runtime.
 *
 * @param string $setting The name of the ini setting to check.
 *
 * @return bool True if the value is changeable at runtime. False otherwise.
 * @since 4.6.0
 *
 * @link  https://secure.php.net/manual/en/function.ini-get-all.php
 *
 */
function is_ini_value_changeable( $setting ) {
	static $ini_all;

	if ( ! isset( $ini_all ) ) {
		$ini_all = false;
		// Sometimes `ini_get_all()` is disabled via the `disable_functions` option for "security purposes".
		if ( function_exists( 'ini_get_all' ) ) {
			$ini_all = ini_get_all();
		}
	}

	// Bit operator to workaround https://bugs.php.net/bug.php?id=44936 which changes access level to 63 in PHP 5.2.6 - 5.2.17.
	if ( isset( $ini_all[ $setting ]['access'] ) && ( INI_ALL === ( $ini_all[ $setting ]['access'] & 7 ) || INI_USER === ( $ini_all[ $setting ]['access'] & 7 ) ) ) {
		return true;
	}

	// If we were unable to retrieve the details, fail gracefully to assume it's changeable.
	if ( ! is_array( $ini_all ) ) {
		return true;
	}

	return false;
}

/**
 * Increase our memory limit for PHP
 *
 * @param bool $toTheMax
 *
 * @since 4.6.0
 */
function increase_memory_limit( $toTheMax = false ) {
	$current_limit     = @ini_get( 'memory_limit' );
	$current_limit_int = convert_hr_to_bytes( $current_limit );

	// Define memory limits.
	if ( ! defined( 'FTS_MEMORY_LIMIT' ) ) {
		if ( false === is_ini_value_changeable( 'memory_limit' ) ) {
			define( 'FTS_MEMORY_LIMIT', $current_limit );
		} else {
			define( 'FTS_MEMORY_LIMIT', '64M' );
		}
	}

	if ( ! defined( 'FTS_MAX_MEMORY_LIMIT' ) ) {
		if ( false === is_ini_value_changeable( 'memory_limit' ) ) {
			define( 'FTS_MAX_MEMORY_LIMIT', $current_limit );
		} elseif ( - 1 === $current_limit_int || $current_limit_int > 268435456 /* = 256M */ ) {
			define( 'FTS_MAX_MEMORY_LIMIT', $current_limit );
		} else {
			define( 'FTS_MAX_MEMORY_LIMIT', '256M' );
		}
	}

	$memoryLimit = ( $toTheMax ) ? FTS_MAX_MEMORY_LIMIT : FTS_MEMORY_LIMIT;

	// Set memory limits.
	$fts_limit_int = convert_hr_to_bytes( $memoryLimit );
	if ( - 1 !== $current_limit_int && ( - 1 === $fts_limit_int || $fts_limit_int > $current_limit_int ) ) {
		@ini_set( 'memory_limit', $memoryLimit );
	}
}

/**
 * Centralized database connection function
 *
 * We are using a single function to handle this so that in the future we can
 * determine which class we want to load to handle DB calls.
 *
 * @param        $server
 * @param        $dbname
 * @param        $dbuser
 * @param        $dbpass
 * @param string $serverPort
 *
 * @return FTSDB
 */
function connectToDB( $server, $dbname, $dbuser, $dbpass, $serverPort = '3306' ) {
	return new FTSDB( "mysql:host=$server;port=$serverPort;dbname=$dbname;charset=utf8", $dbuser, $dbpass );
}

/**
 * Returns the formatted date for a MySQL datetime field
 *
 * This is the date based on the server's timezone settings and should not be used in most cases.
 * current_time('mysql') is the proper replacement.
 *
 * @param int|string $timestamp Optional. Whether to use a specific timestamp. Default to time().
 * @param int|bool   $gmt       Optional. Whether to use GMT timezone. Default false.
 *
 * @return string
 */
function mysqldatetime( $timestamp = '', $gmt = 0 ) {
	if ( empty( $timestamp ) ) {
		$timestamp = time();
	}

	return ( $gmt ) ? gmdate( 'Y-m-d H:i:s', $timestamp ) : date( 'Y-m-d H:i:s', $timestamp );
}

/**
 * Create a UTC+- zone if no timezone string exists or we are using old formats.
 *
 * @param $tzString
 *
 * @return mixed|string
 */
function formatTimeZoneString( $tzString ) {
	if ( is_numeric( $tzString ) ) {
		$tzString = str_replace( '+', '', $tzString );

		if ( $tzString < 0 ) {
			$tzString = 'UTC' . $tzString;
		} else {
			$tzString = 'UTC+' . $tzString;
		}
	}

	if ( substr( $tzString, 0, 3 ) == 'UTC' ) {
		$tzString = str_replace( 'UTC', 'Etc/GMT', $tzString );
	}

	return $tzString;
}

/**
 * get gmt_offset for smart timezone handling.
 *
 * @return float Timezone GMT offset.
 */
function get_timezone_offset() {
	$timezone_string = formatTimeZoneString( get_config_value( 'ftsmbp_time_zone' ) );

	if ( stristr( $timezone_string, 'UTC' ) !== false ) {
		return str_replace( 'UTC', '', $timezone_string );
	} else {
		$timezone_object = timezone_open( $timezone_string );
		$datetime_object = date_create();

		if ( false === $timezone_object || false === $datetime_object ) {
			return false;
		}

		return round( timezone_offset_get( $timezone_object, $datetime_object ) / HOUR_IN_SECONDS, 2 );
	}
}

/**
 * Returns the timezone string for a site, even if it's set to a UTC offset
 *
 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
 *
 * @return string valid PHP timezone string
 */
function get_timezone_string() {
	// if site timezone string exists, return it
	if ( $timezone = get_config_value( 'ftsmbp_time_zone' ) ) {
		return $timezone;
	}

	// get UTC offset, if it isn't set then return UTC
	if ( 0 === ( $utc_offset = get_config_value( 'ftsmbp_gmt_offset', 0 ) ) ) {
		return 'UTC';
	}

	// adjust UTC offset from hours to seconds
	$utc_offset *= 3600;

	// attempt to guess the timezone string from the UTC offset
	if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {
		return $timezone;
	}

	// last try, guess timezone string manually
	$is_dst = date( 'I' );

	foreach ( timezone_abbreviations_list() as $abbr ) {
		foreach ( $abbr as $city ) {
			if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset ) {
				return $city['timezone_id'];
			}
		}
	}

	// fallback to UTC
	return 'UTC';
}

/**
 * Returns the timestamp string based on a string
 * replacement for strtotime() that handles timezones
 *
 * Adapted from https://www.skyverge.com/blog/down-the-rabbit-hole-wordpress-and-timezones/
 *
 * @param $datetime_string
 *
 * @return string|bool valid PHP timezone string or false on error
 */
function get_timestamp_from_string( $datetime_string ) {
	try {
		// get datetime object from site timezone
		$datetime = new DateTime( $datetime_string, new DateTimeZone( get_timezone_string() ) );

		// get the unix timestamp (adjusted for the site's timezone already)
		$timestamp = $datetime->format( 'U' );

		return $timestamp;

	} catch ( Exception $e ) {

		// something broke
		return false;
	}
}

/**
 * Returns the timezoned timestamp string based on a timestamp
 *
 * Adapted from https://www.skyverge.com/blog/down-the-rabbit-hole-wordpress-and-timezones/
 *
 * @param $timestamp
 *
 * @return string|bool valid PHP timezone string or false on error
 */
function get_timestamp_from_timestamp( $timestamp ) {
	try {

		// get datetime object from unix timestamp
		$datetime = new DateTime( "@{$timestamp}", new DateTimeZone( 'UTC' ) );

		// set the timezone to the site timezone
		$datetime->setTimezone( new DateTimeZone( get_timezone_string() ) );

		// return the unix timestamp adjusted to reflect the site's timezone
		return $timestamp + $datetime->getOffset();

	} catch ( Exception $e ) {

		// something broke
		return false;
	}
}

/**
 * Retrieve the current time based on specified type.
 *
 * The 'mysql' type will return the time in the format for MySQL DATETIME field.
 * The 'timestamp' type will return the current timestamp.
 * Other strings will be interpreted as PHP date formats (e.g. 'Y-m-d').
 *
 * If $gmt is set to either '1' or 'true', then both types will use GMT time.
 * if $gmt is false, the output is adjusted with the GMT offset in the WordPress option.
 *
 * @param string   $type Type of time to retrieve. Accepts 'mysql', 'timestamp', or PHP date
 *                       format string (e.g. 'Y-m-d').
 * @param int|bool $gmt  Optional. Whether to use GMT timezone. Default false.
 *
 * @return int|string Integer if $type is 'timestamp', string otherwise.
 * @since 1.0.0
 *
 */
function current_time( $type, $gmt = 0 ) {
	$offset = get_timezone_offset();

	switch ( $type ) {
		case 'mysql':
			return ( $gmt ) ? gmdate( 'Y-m-d H:i:s' ) : gmdate( 'Y-m-d H:i:s', ( time() + ( $offset * HOUR_IN_SECONDS ) ) );
		case 'timestamp':
			return ( $gmt ) ? time() : time() + ( $offset * HOUR_IN_SECONDS );
		default:
			return ( $gmt ) ? date( $type ) : date( $type, time() + ( $offset * HOUR_IN_SECONDS ) );
	}
}

/**
 * Retrieve the date in localized format, based on timestamp.
 *
 * If the locale specifies the locale month and weekday, then the locale will
 * take over the format for the date. If it isn't, then the date format string
 * will be used instead.
 *
 * @param string   $dateFormatString Format to display the date.
 * @param bool|int $unixTimestamp    Optional. Unix timestamp.
 * @param bool     $gmt              Optional, default is false. Whether to convert to GMT for time.
 *
 * @return string The date, translated if locale specifies it.
 * @since 0.71
 *
 */
function date_i18n( $dateFormatString, $unixTimestamp = false, $gmt = false ) {
	global $fts_locale;
	$i = $unixTimestamp;

	if ( false === $i ) {
		if ( ! $gmt ) {
			$i = current_time( 'timestamp' );
		} else {
			$i = time();
		}
		// we should not let date() interfere with our
		// specially computed timestamp
		$gmt = true;
	}

	// store original value for language with untypical grammars
	// see http://core.trac.wordpress.org/ticket/9396
	$req_format = $dateFormatString;

	$dateFunc = $gmt ? 'gmdate' : 'date';

	if ( ( ! empty( $fts_locale->month ) ) && ( ! empty( $fts_locale->weekday ) ) ) {
		$dateMonth            = $fts_locale->get_month( $dateFunc( 'm', $i ) );
		$dateMonth_abbrev     = $fts_locale->get_month_abbrev( $dateMonth );
		$dateWeekday          = $fts_locale->get_weekday( $dateFunc( 'w', $i ) );
		$dateWeekday_abbrev   = $fts_locale->get_weekday_abbrev( $dateWeekday );
		$dateMeridiem         = $fts_locale->get_meridiem( $dateFunc( 'a', $i ) );
		$dateMeridiem_capital = $fts_locale->get_meridiem( $dateFunc( 'A', $i ) );
		$dateFormatString     = ' ' . $dateFormatString;
		$dateFormatString     = preg_replace( "/([^\\\])D/", "\\1" . backslashit( $dateWeekday_abbrev ), $dateFormatString );
		$dateFormatString     = preg_replace( "/([^\\\])F/", "\\1" . backslashit( $dateMonth ), $dateFormatString );
		$dateFormatString     = preg_replace( "/([^\\\])l/", "\\1" . backslashit( $dateWeekday ), $dateFormatString );
		$dateFormatString     = preg_replace( "/([^\\\])M/", "\\1" . backslashit( $dateMonth_abbrev ), $dateFormatString );
		$dateFormatString     = preg_replace( "/([^\\\])a/", "\\1" . backslashit( $dateMeridiem ), $dateFormatString );
		$dateFormatString     = preg_replace( "/([^\\\])A/", "\\1" . backslashit( $dateMeridiem_capital ), $dateFormatString );

		$dateFormatString = substr( $dateFormatString, 1, strlen( $dateFormatString ) - 1 );
	}
	$timezone_formats    = [ 'P', 'I', 'O', 'T', 'Z', 'e' ];
	$timezone_formats_re = implode( '|', $timezone_formats );
	if ( preg_match( "/$timezone_formats_re/", $dateFormatString ) ) {
		$timezone_string = formatTimeZoneString( get_config_value( 'ftsmbp_time_zone' ) );
		if ( $timezone_string ) {
			$timezone_object = timezone_open( $timezone_string );
			$date_object     = date_create( null, $timezone_object );
			foreach ( $timezone_formats as $timezone_format ) {
				if ( false !== strpos( $dateFormatString, $timezone_format ) ) {
					$formatted        = date_format( $date_object, $timezone_format );
					$dateFormatString = ' ' . $dateFormatString;
					$dateFormatString = preg_replace( "/([^\\\])$timezone_format/", "\\1" . backslashit( $formatted ), $dateFormatString );
					$dateFormatString = substr( $dateFormatString, 1, strlen( $dateFormatString ) - 1 );
				}
			}
		}
	}
	$j = @$dateFunc( $dateFormatString, $i );

	/**
	 * Filter the date formatted based on the locale.
	 *
	 * @param string $j          Formatted date string.
	 * @param string $req_format Format to display the date.
	 * @param int    $i          Unix timestamp.
	 * @param bool   $gmt        Whether to convert to GMT for time. Default false.
	 *
	 * @since 2.8.0
	 *
	 */
	$j = apply_filters( 'date_i18n', $j, $req_format, $i, $gmt );

	return $j;
}

/**
 * Creates a date from a timestamp.
 *
 * @param $time
 *
 * @return string
 */
function makeDate( $time ) {
	$date = date_i18n( 'l F d, Y', $time ); // Makes date in the format of: Thursday July 05, 2006

	return $date;
}

/**
 * @param $time
 *
 * @return string
 */
function makeTime( $time ) {
	$date = date_i18n( 'g:i A', $time ); // Makes date in the format of: 3:30 PM

	return $date;
}

/**
 * @param $time
 *
 * @return string
 */
function makeDateTime( $time ) {
	$date = date_i18n( 'l F d, Y - g:i A', $time ); // Makes date in the format of: Thursday July 5, 2006 - 3:30 pm

	return $date;
}

/**
 * @param $time
 *
 * @return string
 */
function makeOrderDateTime( $time ) {
	$date = date_i18n( 'M d, Y - g:i A', $time ); // Makes date in the format of: Jul 5, 2006 - 3:30 pm

	return $date;
}

/**
 * @param $time
 *
 * @return string
 */
function makeShortDate( $time ) {
	$date = ( $time == "" ) ? "" : date_i18n( 'm/d/Y', $time ); // Makes date in the format of: 07/05/2006

	return $date;
}

/**
 * @param $time
 *
 * @return string
 */
function makeShortDateTime( $time ) {
	$date = ( $time == "" ) ? "" : date_i18n( 'm/d/Y g:i A', $time ); // Makes date in the format of: 07/05/2006 - 3:30 pm

	return $date;
}

/**
 * @param $time
 *
 * @return string
 */
function makeCurrentYear( $time ) {
	$date = ( $time == "" ) ? "" : date_i18n( 'Y', $time ); // Makes date in the format of: 2006

	return $date;
}

/**
 * @param     $time
 * @param int $numOfYears
 *
 * @return string
 */
function makeXYearsFromCurrentYear( $time, int $numOfYears = 0 ) {
	$date = ( $time == "" ) ? "" : intval( date_i18n( 'Y', $time ) ) + $numOfYears; // Makes date in the format of: 2026

	return $date;
}

/**
 * @param $time
 *
 * @return string
 */
function makeYear( $time ) {
	$date = date_i18n( 'Y', $time ); // Makes date in the format of: 2006

	return $date;
}

/**
 * @param $time
 *
 * @return string
 */
function makeMonth( $time ) {
	$date = date_i18n( 'M', $time ); // Makes date in the format of: Jul

	return $date;
}

/**
 * @param $time
 *
 * @return string
 */
function makeShortMonth( $time ) {
	$date = date_i18n( 'm', $time ); // Makes date in the format of: 05

	return $date;
}

/**
 * @param $numOfMonths
 *
 * @return false|int
 */
function makeXMonthsFromCurrentMonthAsTimestamp( $numOfMonths ) {
	$currentTime  = time();
	$currentMonth = intval( makeShortMonth( $currentTime ) );
	$currentYear  = intval( makeYear( $currentTime ) );

	// Increase month count
	for ( $i = 0; $i < $numOfMonths; $i ++ ) {
		// Handle Dec
		$currentMonth = ( $currentMonth == "12" ) ? 1 : ( $currentMonth + 1 );
		$currentYear  = ( $currentMonth == "12" ) ? ( $currentYear + 1 ) : $currentYear;
	}

	$timestamp = strtotime( $currentMonth . "/01/" . $currentYear );

	return $timestamp;
}

/**
 * Y-M-D can cause strtotime issues so switch it up.
 *
 * @param $dateString
 *
 * @return false|int
 */
function makeYMDStringIntoMDYStamp( $dateString ) {
	$splitDate = explode( '-', $dateString );

	return mktime( 0, 0, 0, $splitDate[1], $splitDate[2], $splitDate[0] );
}

/**
 * Convert M/D/Y to Y-M-D.
 *
 * @param $dateString
 *
 * @return string
 */
function makeMDYStringIntoYMDString( $dateString ) {
	$splitDate = explode( '/', $dateString );

	return $splitDate[2] . '-' . $splitDate[0] . '-' . $splitDate[1];
}

/**
 * time_elapsed function.
 *
 * Formatted like "6d 15h 48m 19s"
 *
 * @param mixed $secs A number of seconds elapsed (ie stop - start)
 *
 * @return string                The time elapsed
 */
function time_elapsed( $secs ) {
	$bit = [
		'y' => $secs / 31556926 % 12,
		'w' => $secs / 604800 % 52,
		'd' => $secs / 86400 % 7,
		'h' => $secs / 3600 % 24,
		'm' => $secs / 60 % 60,
		's' => $secs % 60,
	];
	$ret = [];

	foreach ( $bit as $k => $v ) {
		if ( $v > 0 ) {
			$ret[] = $v . $k;
		}
	}

	return join( ' ', $ret );
}


/**
 * time_elapsed_alt function.
 *
 * Formatted like "6 days 15 hours 48 minutes and 19 seconds ago."
 *
 * @param mixed $secs A number of seconds elapsed (ie stop - start)
 *
 * @return string                The time elapsed
 */
function time_elapsed_alt( $secs ) {
	$bit = [
		' year'   => $secs / 31556926 % 12,
		' week'   => $secs / 604800 % 52,
		' day'    => $secs / 86400 % 7,
		' hour'   => $secs / 3600 % 24,
		' minute' => $secs / 60 % 60,
		' second' => $secs % 60,
	];

	foreach ( $bit as $k => $v ) {
		if ( $v > 1 ) {
			$ret[] = $v . $k . 's';
		}
		if ( $v == 1 ) {
			$ret[] = $v . $k;
		}
	}
	array_splice( $ret, count( $ret ) - 1, 0, 'and' );
	$ret[] = 'ago.';

	return join( ' ', $ret );
}

/**
 * get_time_diff function.
 *
 * Short formatted like "2 days ago"
 *
 * @param mixed $from Start timestamp
 * @param mixed $to   End timestamp
 *
 * @return string                The time elapsed
 */

function get_time_diff( $from, $to ) {
	$diff = abs( $from - $to );

	$years   = $diff / 31557600;
	$months  = $diff / 2635200;
	$weeks   = $diff / 604800;
	$days    = $diff / 86400;
	$hours   = $diff / 3600;
	$minutes = $diff / 60;

	if ( $years > 1 ) {
		$duration = round( $years ) . ' years';
	} elseif ( $months > 1 ) {
		$duration = round( $months ) . ' months';
	} elseif ( $weeks > 1 ) {
		$duration = round( $weeks ) . ' weeks';
	} elseif ( $days > 1 ) {
		$duration = round( $days ) . ' days';
	} elseif ( $hours > 1 ) {
		$duration = round( $hours ) . ' hours';
	} else {
		$duration = round( $minutes ) . ' minutes';
	}

	$duration = ! empty( $duration ) ? $duration . ' ago' : '';

	return $duration;
}


//=================================================
// BBCode Functions Generated from: 
// http://bbcode.strefaphp.net/bbcode.php
// A gigantic thanks goes out to the 
// programmers there!!
// 
// Use the function like so: echo bbcode($string);
//=================================================
/**
 * @param $str
 *
 * @return mixed|string|string[]|null
 */
Function bbcode( $str ) {
	// Makes < and > page friendly
	//$str=str_replace("&","&amp;",$str);
	$str = str_replace( "<", "&lt;", $str );
	$str = str_replace( ">", "&gt;", $str );

	// Link inside tags new window
	$str = preg_replace( "#\[url](.*?)?(.*?)\[/url]#si", "<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>", $str );

	// Link inside first tag new window
	$str = preg_replace( "#\[url=(.*?)?(.*?)](.*?)\[/url]#si", "<a href=\"\\2\" target=\"_blank\">\\3</a>", $str );

	// Link inside tags
	$str = preg_replace( "#\[url2](.*?)?(.*?)\[/url2]#si", "<a href=\"\\1\\2\">\\1\\2</a>", $str );

	// Link inside first tag
	$str = preg_replace( "#\[url2=(.*?)?(.*?)](.*?)\[/url2]#si", "<a href=\"\\2\">\\3</a>", $str );

	// Automatic links if no url tags used
	$str = preg_replace_callback( "#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si", "bbcode_autolink", $str );
	$str = preg_replace( "#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]*)?)#i", " <a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $str );
	$str = preg_replace( "#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "\\1<a href=\"mailto: \\2@\\3\">\\2_(at)_\\3</a>", $str );

	// PHP Code
	$str = preg_replace( "#\[php](.*?)\[/php]#si", "<div class=\"codetop\"><u><strong>&lt?PHP:</strong></u></div><div class=\"codemain\">\\1</div>", $str );

	// Bold
	$str = preg_replace( "#\[b](.*?)\[/b]#si", "<strong>\\1</strong>", $str );

	// Italics
	$str = preg_replace( "#\[i](.*?)\[/i]#si", "<em>\\1</em>", $str );

	// Underline
	$str = preg_replace( "#\[u](.*?)\[/u]#si", "<u>\\1</u>", $str );

	// Alig text
	$str = preg_replace( "#\[align=(left|center|right)](.*?)\[/align]#si", "<div align=\"\\1\">\\2</div>", $str );

	// Font Color
	$str = preg_replace( "#\[color=(.*?)](.*?)\[/color]#si", "<span style=\"color:\\1\">\\2</span>", $str );

	// Font Size
	$str = preg_replace( "#\[size=(.*?)](.*?)\[/size]#si", "<span style=\"font-size:\\1\">\\2</span>", $str );

	// Image
	$str = preg_replace( "#\[img](.*?)\[/img]#si", "<img src=\"\\1\" border=\"0\" alt=\"\" />", $str );

	// Uploaded image
	$str = preg_replace( "#\[ftp_img](.*?)\[/ftp_img]#si", "<img src=\"img/\\1\" border=\"0\" alt=\"\" />", $str );

	// HR Line
	$str = preg_replace( "#\[hr=(\d{1,2}|100)]#si", "<hr class=\"linia\" width=\"\\1%\" />", $str );

	// Code
	$str = preg_replace( "#\[code](.*?)\[/code]#si", "<div class=\"codetop\"><u><strong>Code:</strong></u></div><div class=\"codemain\">\\1</div>", $str );

	// Code, Provide Author
	$str = preg_replace( "#\[code=(.*?)](.*?)\[/code]#si", "<div class=\"codetop\"><u><strong>Code \\1:</strong></u></div><div class=\"codemain\">\\2</div>", $str );

	// Quote
	$str = preg_replace( "#\[quote](.*?)\[/quote]#si", "<div class=\"quotetop\"><u><strong>Quote:</strong></u></div><div class=\"quotemain\">\\1</div>", $str );

	// Quote, Provide Author
	$str = preg_replace( "#\[quote=(.*?)](.*?)\[/quote]#si", "<div class=\"quotetop\"><u><strong>Quote \\1:</strong></u></div><div class=\"quotemain\">\\2</div>", $str );

	// Email
	$str = preg_replace( "#\[email]([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email]#i", "<a href=\"mailto:\\1@\\2\">\\1@\\2</a>", $str );

	// Email, Provide Author
	$str = preg_replace( "#\[email=([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)?(.*?)](.*?)\[/email]#i", "<a href=\"mailto:\\1@\\2\">\\5</a>", $str );

	// YouTube
	$str = preg_replace( "#\[youtube]http://(?:www\.)?youtube.com/v/([0-9A-Za-z-_]{11})[^[]*\[/youtube]#si", "<object width=\"425\" height=\"350\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://www.youtube.com/v/\\1\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"425\" height=\"350\"></embed></object>", $str );
	$str = preg_replace( "#\[youtube]http://(?:www\.)?youtube.com/watch\?v=([0-9A-Za-z-_]{11})[^[]*\[/youtube]#si", "<object width=\"425\" height=\"350\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://www.youtube.com/v/\\1\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"425\" height=\"350\"></embed></object>", $str );

	// Google Video
	$str = preg_replace( "#\[gvideo]http://video.google.[A-Za-z0-9.]{2,5}/videoplay\?docid=([0-9A-Za-z-_]*)[^[]*\[/gvideo]#si", "<object width=\"425\" height=\"350\"><param name=\"movie\" value=\"http://video.google.com/googleplayer.swf\?docId=\\1\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://video.google.com/googleplayer.swf\?docId=\\1\" type=\"application/x-shockwave-flash\" allowScriptAccess=\"sameDomain\" quality=\"best\" bgcolor=\"#ffffff\" scale=\"noScale\" salign=\"TL\"  FlashVars=\"playerMode=embedded\" wmode=\"transparent\" width=\"425\" height=\"350\"></embed></object>", $str );

	// change \n to <br />
	$str = nl2br( $str );

	// return bbdecoded string
	return $str;
}


/**
 * @param $str
 *
 * @return string
 */
function bbcode_autolink( $str ) {
	$lnk = $str[3];
	if ( strlen( $lnk ) > 30 ) {
		if ( substr( $lnk, 0, 3 ) == 'www' ) {
			$l = 9;
		} else {
			$l = 5;
		}
		$lnk = substr( $lnk, 0, $l ) . '(...)' . substr( $lnk, strlen( $lnk ) - 8 );
	}

	return ' <a href="' . $str[2] . '://' . $str[3] . '" target="_blank">' . $str[2] . '://' . $lnk . '</a>';
}

/**
 * Replacement for die()
 * Used to display messages without displaying the board
 *
 * @param string $msgText
 * @param string $msgTitle
 */
function message_die( $msgText = '', $msgTitle = '' ) {
	echo "<html>\n<body>\n" . $msgTitle . "\n<br /><br />\n" . $msgText . "</body>\n</html>";
	include( BASEPATH . '/includes/footer.php' );
	exit;
}

/**
 * Error and message handler, call with trigger_error if required.
 *
 * @param $errNo
 * @param $msgText
 * @param $errFile
 * @param $errLine
 *
 * @return bool|void
 */
function msg_handler( $errNo, $msgText, $errFile, $errLine ) {
	global $mbp_config, $page;

	// Do not display notices if we suppress them via @
	if ( ! ( error_reporting() & $errNo ) ) {
		return;
	}

	// Message handler is stripping text. In case we need it, we are possible to define long text...
	if ( isset( $msg_long_text ) && $msg_long_text && ! $msgText ) {
		$msgText = $msg_long_text;
	}

	if ( ! defined( 'E_DEPRECATED' ) ) {
		define( 'E_DEPRECATED', 8192 );
	}

	switch ( $errNo ) {
		case E_NOTICE:
		case E_WARNING:

			$error_name = ( $errNo === E_WARNING ) ? 'PHP Warning' : 'PHP Notice';
			echo '<strong>[MBP Debug] ' . $error_name . '</strong>: in file <strong>' . $errFile . '</strong> on line <strong>' . $errLine . '</strong>: <strong>' . $msgText . '</strong><br />' . "\n";

			// echo '<br /><br />BACKTRACE<br />' . get_backtrace() . '<br />' . "\n";

			return;

			break;

		case E_USER_ERROR:

			$msgTitle = __( 'General Error' );
			$l_notify = '';

			if ( ! empty( $mbp_config['ftsmbp_system_email_address'] ) ) {
				$l_notify = '<p>Please notify the system administrator or webmaster: <a href="mailto:' . $mbp_config['ftsmbp_system_email_address'] . '">' . $mbp_config['ftsmbp_system_email_address'] . '</a></p>';
			}

			if ( defined( 'DEBUG' ) ) {
				$msgText .= '<br /><br />BACKTRACE<br />' . get_backtrace();
			}

			// Do not send 200 OK, but service unavailable on errors
			send_status_line( 503, 'Service Unavailable' );

			// Try to not call the adm page data...
			$page->setTemplateVar( 'PageTitle', $msgTitle );
			$page->setTemplateVar( 'sidebar_active', INACTIVE );

			$page_content = '
				<div class="box tabbable">
					<div class="box-header">
						<h3><i class="glyphicon glyphicon-download-alt"></i> ' . $msgTitle . '</h3>
					</div>
					<div class="box-content">
						' . $msgText . '
						<br /><br />
						' . $l_notify . '
					</div>
				</div>';

			$page->setTemplateVar( 'PageContent', $page_content );
			$page->setTemplateVar( "JQueryReadyScript", '' );
			include BASEPATH . '/themes/' . $page->getTemplateVar( 'Theme' ) . '/template.php';

			exit_handler();

			// On a fatal error (and E_USER_ERROR *is* fatal) we never want other scripts to continue and force an exit here.
			exit;
			break;

		case E_USER_WARNING:
		case E_USER_NOTICE:
			define( 'IN_ERROR_HANDLER', true );
			$msgTitle = __( 'Information' );

			$page->setTemplateVar( 'PageTitle', $msgTitle );
			$page->setTemplateVar( 'sidebar_active', INACTIVE );

			$page_content = '
				<div class="box tabbable">
					<div class="box-header">
						<h3><i class="glyphicon glyphicon-download-alt"></i> ' . $msgTitle . '</h3>
					</div>
					<div class="box-content">
						' . $msgText . '
					</div>
				</div>';

			$page->setTemplateVar( 'PageContent', $page_content );
			$page->setTemplateVar( "JQueryReadyScript", '' );
			include BASEPATH . '/themes/' . $page->getTemplateVar( 'Theme' ) . '/template.php';

			exit_handler();
			break;

		// PHP4 compatibility
		case E_DEPRECATED:
			return true;
			break;
	}

	// If we notice an error not handled here we pass this back to PHP by returning false
	// This may not work for all php versions
	return false;
}

/**
 * Return a nicely formatted backtrace.
 *
 * Turns the array returned by debug_backtrace() into HTML markup.
 * Also filters out absolute paths to phpBB root.
 *
 * @return string    HTML markup
 */
function get_backtrace() {
	$output    = '<div style="font-family: monospace;">';
	$backtrace = debug_backtrace();

	// We skip the first one, because it only shows this file/function
	unset( $backtrace[0] );

	foreach ( $backtrace as $trace ) {
		// Strip the current directory from path
		$trace['file'] = ( empty( $trace['file'] ) ) ? '(not given by php)' : htmlspecialchars( $trace['file'] );
		$trace['line'] = ( empty( $trace['line'] ) ) ? '(not given by php)' : $trace['line'];

		// Only show function arguments for include etc.
		// Other parameters may contain sensible information
		$argument = '';
		if ( ! empty( $trace['args'][0] ) && in_array( $trace['function'], [ 'include', 'require', 'include_once', 'require_once' ] ) ) {
			$argument = htmlspecialchars( $trace['args'][0] );
		}

		$trace['class'] = ( ! isset( $trace['class'] ) ) ? '' : $trace['class'];
		$trace['type']  = ( ! isset( $trace['type'] ) ) ? '' : $trace['type'];

		$output .= '<br />';
		$output .= '<b>FILE:</b> ' . $trace['file'] . '<br />';
		$output .= '<b>LINE:</b> ' . ( ( ! empty( $trace['line'] ) ) ? $trace['line'] : '' ) . '<br />';

		$output .= '<b>CALL:</b> ' . htmlspecialchars( $trace['class'] . $trace['type'] . $trace['function'] );
		$output .= '(' . ( ( $argument !== '' ) ? "'$argument'" : '' ) . ')<br />';
	}
	$output .= '</div>';

	return $output;
}

/**
 * Handler for exit calls.
 * This function supports hooks.
 */
function exit_handler() {
	callModuleHook( '', __FUNCTION__ );

	// As a pre-caution... some setups display a blank page if the flush() is not there.
	( ob_get_level() > 0 ) ? @ob_flush() : @flush();

	exit;
}

/**
 * Returns a bootstrap alert.
 *
 * @param        $text
 * @param int    $showIcon
 * @param string $icon
 * @param string $id
 * @param string $class
 *
 * @return string
 */
function return_alert( $text, $showIcon = 1, $icon = 'glyphicon glyphicon-ok', $id = '', $class = '' ) {
	return '<div class="alert ' . ( ( ! empty ( $class ) ) ? $class : '' ) . '" ' . ( ( ! empty ( $id ) ) ? ' id="' . $id . '"' : '' ) . '>' . ( ( $showIcon ) ? '<i class="' . $icon . '"></i> ' : '' ) . $text . '</div>';
}

/**
 * Returns a bootstrap error alert.
 *
 * @param        $text
 * @param int    $showIcon
 * @param string $icon
 * @param string $id
 *
 * @return string
 */
function return_error_alert( $text, $showIcon = 1, $icon = 'glyphicons glyphicons-warning-sign', $id = '' ) {
	return return_alert( $text, $showIcon, $icon, $id, 'alert-danger' );
}

/**
 * Returns a bootstrap info alert.
 *
 * @param        $text
 * @param int    $showIcon
 * @param string $icon
 * @param string $id
 *
 * @return string
 */
function return_info_alert( $text, $showIcon = 1, $icon = 'glyphicons glyphicons-warning-sign', $id = '' ) {
	return return_alert( $text, $showIcon, $icon, $id, 'alert-info' );
}

/**
 * Returns a bootstrap success alert.
 *
 * @param        $text
 * @param int    $showIcon
 * @param string $icon
 * @param string $id
 *
 * @return string
 */
function return_success_alert( $text, $showIcon = 1, $icon = 'glyphicons glyphicons-ok', $id = '' ) {
	return return_alert( $text, $showIcon, $icon, $id, 'alert-success' );
}

/**
 * Returns a bootstrap warning alert.
 *
 * @param        $text
 * @param int    $showIcon
 * @param string $icon
 * @param string $id
 *
 * @return string
 */
function return_warning_alert( $text, $showIcon = 1, $icon = 'glyphicons glyphicons-warning-sign', $id = '' ) {
	return return_alert( $text, $showIcon, $icon, $id, 'alert-warning' );
}

/**
 * nl2br replacement for ajax calls since newlines are escaped.
 *
 * @param $string
 *
 * @return mixed
 */
function ajaxnl2br( $string ) {
	return str_replace( [ "\\r\\n", "\\r", "\\n" ], "<br />", $string );
}

/**
 * Check if this item should be selected.
 *
 * @param $testFor
 * @param $testAgainst
 *
 * @return string
 */
function testSelected( $testFor, $testAgainst ) {
	if ( $testFor == $testAgainst ) {
		return ' selected="selected"';
	}

	return '';
}

/**
 * Check if this item should be checked.
 *
 * @param $testFor
 * @param $testAgainst
 *
 * @return string
 */
function testChecked( $testFor, $testAgainst ) {
	if ( $testFor == $testAgainst ) {
		return ' checked="checked"';
	}

	return '';
}

/**
 * Outputs Yes or No.
 *
 * @param $value
 *
 * @return string
 */
function returnYesNo( $value ) {
	if ( $value == 1 || $value == true ) {
		return "Yes";
	} else {
		return "No";
	}
}

/**
 * Returns the system's selected currency symbol.
 *
 * @return string
 */
function returnCurrencySymbol() {
	global $mbp_config;

	return ( ( ! empty( $mbp_config['ftsmbp_clms_currency_type'] ) ) ? $mbp_config['ftsmbp_clms_currency_type'] : '$' );
}

/**
 * Pads a string to a certain length.
 *
 * @param        $input
 * @param        $pad_length
 * @param string $pad_string
 * @param int    $pad_type
 *
 * @return string
 */
function paddString( $input, $pad_length, $pad_string = " ", $pad_type = STR_PAD_RIGHT ) {
	$pad_type = ( $pad_type == 'L' ) ? STR_PAD_LEFT : $pad_type;
	$pad_type = ( $pad_type == 'B' ) ? STR_PAD_BOTH : $pad_type;

	return str_pad( $input, $pad_length, $pad_string, $pad_type );
}

/**
 * Returns an array based on default values.
 *
 * @param $defaults
 * @param $theArray
 *
 * @return array
 */
function prepArrayDefaults( $defaults, $theArray ) {
	$theArray = (array) $theArray;
	$out      = [];

	foreach ( $defaults as $name => $default ) {
		if ( array_key_exists( $name, $theArray ) ) {
			$out[ $name ] = $theArray[ $name ];
		} else {
			$out[ $name ] = $default;
		}
	}

	return $out;
}

/**
 * Puts items into money format.
 *
 * @param $value
 *
 * @return string
 */
function formatCurrency( $value ) {
	// All non numeric values should be turned into 0
	if ( ! is_numeric( $value ) ) {
		$value = 0;
	}

	return returnCurrencySymbol() . number_format( $value, 2, '.', ',' );
}

/**
 * Takes the change off of a number without rounding it up.
 *
 * @param $value
 *
 * @return string
 */
function stripChange( $value ) {
	$returnVar = "";

	if ( is_numeric( $value ) ) {
		// If we have multiple periods then we will output all but the last one
		$explodedValue = explode( ".", $value );

		if ( count( $explodedValue ) > 1 ) {
			for ( $x = 0; $x < count( $explodedValue ) - 1; $x ++ ) {
				$returnVar = ( $returnVar == "" ) ? $explodedValue[ $x ] : "." . $explodedValue[ $x ];
			}
		} else {
			$returnVar = $explodedValue[0];
		}
	} else {
		$returnVar = $value;
	}

	return $returnVar;
}

/**
 * Add leading zeros when necessary.
 *
 * If you set the threshold to '4' and the number is '10', then you will get
 * back '0010'. If you set the threshold to '4' and the number is '5000', then you
 * will get back '5000'.
 *
 * Uses sprintf to append the amount of zeros based on the $threshold parameter
 * and the size of the number. If the number is large enough, then no zeros will
 * be appended.
 *
 * @param mixed $number    Number to append zeros to if not greater than threshold.
 * @param int   $threshold Digit places number needs to be to not have zeros added.
 *
 * @return string Adds leading zeros to number if needed.
 * @since 0.71
 *
 */
function zeroise( $number, $threshold ) {
	return sprintf( '%0' . $threshold . 's', $number );
}

/**
 * Adds backslashes before letters and before a number at the start of a string.
 *
 * @param string $string Value to which backslashes will be added.
 *
 * @return string String with backslashes inserted.
 * @since 0.71
 *
 */
function backslashit( $string ) {
	$string = preg_replace( '/^([0-9])/', '\\\\\\\\\1', $string );
	$string = preg_replace( '/([a-z])/i', '\\\\\1', $string );

	return $string;
}

/**
 * Navigates through an array and removes slashes from the values.
 *
 * If an array is passed, the array_map() function causes a callback to pass the
 * value back to the function. The slashes from this value will removed.
 *
 * @param mixed $value The value to be stripped.
 *
 * @return mixed Stripped value.
 * @since 4.13.09.25
 *
 */
function stripslashes_deep( $value ) {
	if ( is_array( $value ) ) {
		$value = array_map( 'stripslashes_deep', $value );
	} elseif ( is_object( $value ) ) {
		$vars = get_object_vars( $value );
		foreach ( $vars as $key => $data ) {
			$value->{$key} = stripslashes_deep( $data );
		}
	} elseif ( is_string( $value ) ) {
		$value = stripslashes( $value );
	}

	return $value;
}

/**
 * Compress a chunk of code to output.
 *
 * @param string $buffer Text to compress
 *
 * @return array $buffer Compressed text
 * @since 4.14.07.09
 *
 */
function compressCode( $buffer ) {
	// Remove comments
	$buffer = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer );

	// Remove tabs, spaces, newlines, etc.
	$buffer = str_replace( [ "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ], '', $buffer );

	return $buffer;
}

/**
 * Returns the HTML code for our delete links.
 *
 * @param $DBTableRowID
 * @param $rowName
 * @param $DBTableName
 * @param $typeName
 *
 * @return string
 */
function createDeleteLinkWithImage( $DBTableRowID, $rowName, $DBTableName, $typeName ) {
	return "<a class=\"btn btn-danger\" onclick=\"ajaxDeleteNotifier('" . $DBTableRowID . $DBTableName . "Spinner', '" . SITE_URL . "/ajax.php?action=deleteitem&table=" . $DBTableName . "&id=" . $DBTableRowID . "', '" . $typeName . "', '" . $rowName . "');\"><i class=\"glyphicon glyphicon-remove\"></i><span id=\"" . $DBTableRowID . $DBTableName . "Spinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span></a>";
}

/**
 * Returns the HTML code for our spinner.
 *
 * @return string
 */
function progressSpinnerHTML() {
	global $mbp_config;

	return "<img src=\"" . SITE_URL . "/themes/" . $mbp_config['ftsmbp_theme'] . "/images/indicator.gif\" alt=\"spinner\" />";
}

/**
 * Returns the HTML code for a table update notice.
 *
 * @return string
 */
function tableUpdateNoticeHTML() {
	return "<div class=\"alert alert-info\">A new row has been added to this table, inline editing for this new row will be disabled until the next page refresh.</div>";
}

/**
 * Returns the text/HTML for the not authorized notice.
 *
 * @return string
 */
function notAuthorizedNotice() {
	return "ACCESS DENIED: You are not authorized to access this area. Please refrain from trying to do so again.";
}

/**
 * Returns the text/HTML for a titled box.
 *
 * @param        $title
 * @param        $content
 * @param string $extraContentClass
 *
 * @return string
 */
function returnBoxHTML( $title, $content, $extraContentClass = '' ) {
	return '
		<div class="box tabbable">
			<div class="box-header">
				<h3>' . $title . '</h3>				
			</div>
			<div class="tab-content ' . $extraContentClass . '">
				' . $content . '	
			</div>
		</div>';
}

/**
 * Returns the JQUERY code for our edit in-place.
 *
 * @param        $DBTableRowID
 * @param        $DBTableFieldName
 * @param        $DBTableName
 * @param string $inputType
 * @param string $extraOptions
 *
 * @return string
 */
function returnEditInPlaceJQuery( $DBTableRowID, $DBTableFieldName, $DBTableName, $inputType = "", $extraOptions = "" ) {
	$inputTypeJQuery = ( $inputType != "" ) ? "type      : '" . $inputType . "'," : "";
	$extraOptions    = ( $extraOptions != "" ) ? $extraOptions . "," : "";

	return "
					$('#" . $DBTableRowID . "_" . $DBTableFieldName . "').addClass('editableItemHolder').editable('" . SITE_URL . "/ajax.php?action=updateitem&table=" . $DBTableName . "&item=" . $DBTableFieldName . "&id=" . $DBTableRowID . "', { 
						" . $inputTypeJQuery . "
						" . $extraOptions . "
						cancel    : '<button class=\"btn btn-default btn-sm editable-cancel\" type=\"button\"><i class=\"glyphicon glyphicon-remove\"></i></button>',
						submit    : '<button class=\"btn btn-primary btn-sm editable-submit\" type=\"submit\"><i class=\"glyphicon glyphicon-ok\"></i></button>',					    
						indicator : '" . progressSpinnerHTML() . "',
						tooltip   : 'Click to edit...',
						style: 'display: inline;',
        					width: 'none'
					});";
}

/**
 * Sort-helper for timezones.
 *
 * @param array $a
 * @param array $b
 *
 * @return int
 * @since  2.9.0
 * @access private
 *
 */
function _wp_timezone_choice_usort_callback( $a, $b ) {
	// Don't use translated versions of Etc
	if ( 'Etc' === $a['continent'] && 'Etc' === $b['continent'] ) {
		// Make the order of these more like the old dropdown
		if ( 'GMT+' === substr( $a['city'], 0, 4 ) && 'GMT+' === substr( $b['city'], 0, 4 ) ) {
			return - 1 * ( strnatcasecmp( $a['city'], $b['city'] ) );
		}
		if ( 'UTC' === $a['city'] ) {
			if ( 'GMT+' === substr( $b['city'], 0, 4 ) ) {
				return 1;
			}

			return - 1;
		}
		if ( 'UTC' === $b['city'] ) {
			if ( 'GMT+' === substr( $a['city'], 0, 4 ) ) {
				return - 1;
			}

			return 1;
		}

		return strnatcasecmp( $a['city'], $b['city'] );
	}
	if ( $a['t_continent'] == $b['t_continent'] ) {
		if ( $a['t_city'] == $b['t_city'] ) {
			return strnatcasecmp( $a['t_subcity'], $b['t_subcity'] );
		}

		return strnatcasecmp( $a['t_city'], $b['t_city'] );
	} else {
		// Force Etc to the bottom of the list
		if ( 'Etc' === $a['continent'] ) {
			return 1;
		}
		if ( 'Etc' === $b['continent'] ) {
			return - 1;
		}

		return strnatcasecmp( $a['t_continent'], $b['t_continent'] );
	}
}

/**
 * Used by our createDropdown and form functions.
 *
 * @param        $type
 * @param int    $addDefaultOption
 * @param string $prefix
 * @param array  $arguments
 *
 * @return array
 */
function getDropdownArray( $type, $addDefaultOption = 1, $prefix = '', array $arguments = [] ) {
	global $ftsdb, $CATEGORY_TYPE, $FTS_COUNTRIES;
	extract( (array) $arguments ); // Extract our arguments into variables

	$returnArray = [];

	if ( $addDefaultOption ) {
		$returnArray[''] = '--Select One--';
	}

	if ( $type == "blogcategories" ) {
		$result = $ftsdb->select( DBTABLEPREFIX . "categories",
			"type = :type ORDER BY name",
			[
				":type" => '2',
			],
			'id, name' );

		if ( $result ) {
			foreach ( $result as $row ) {
				$returnArray[ $row['id'] ] = $row['name'];
			}
			$result = null;
		}
	} elseif ( $type == "categories" ) {
		$result = $ftsdb->select( DBTABLEPREFIX . "categories", "1 ORDER BY name", [], 'id, name' );

		if ( $result ) {
			foreach ( $result as $row ) {
				$returnArray[ $row['id'] ] = $row['name'];
			}
			$result = null;
		}
	} elseif ( $type == "categoriesforsending" ) {
		$returnArray['allUsers'] = 'All Users';

		$result = $ftsdb->select( DBTABLEPREFIX . "categories",
			"type = :type ORDER BY name",
			[
				":type" => '3',
			],
			'id, name' );

		if ( $result ) {
			foreach ( $result as $row ) {
				$returnArray[ $row['id'] ] = $row['name'];
			}
			$result = null;
		}
	} elseif ( $type == "categorytypes" ) {
		$returnArray = $returnArray + $CATEGORY_TYPE; // Preserve numeric keys by not using array_merge
	} elseif ( $type == "countries" ) {
		$returnArray = $returnArray + $FTS_COUNTRIES; // Preserve numeric keys by not using array_merge
	} elseif ( $type == "daterange" ) {
		$returnArray = array_merge( $returnArray,
			[
				'today'     => "Today",
				'thisWeek'  => "This Week",
				'thisMonth' => "This Month",
				'thisYear'  => "This Year",
				'allTime'   => "Alltime",
				'custom'    => "Custom Date Range",
			] );
	} elseif ( $type == "email_templates" ) {
		$result = $ftsdb->select( DBTABLEPREFIX . "email_templates", "1 ORDER BY name", [], 'id, name' );

		if ( $result ) {
			foreach ( $result as $row ) {
				$returnArray[ $row['id'] ] = $row['name'];
			}
			$result = null;
		}
	} elseif ( $type == "email_users" ) {
		$userLevels  = array_merge( [
			'all' => 'All Users',
		],
			getDropdownArray( 'userlevel', 0, 'ul_' )
		);
		$returnArray = array_merge( $returnArray,
			[
				'Email Users by Group'   => (array) $userLevels,
				'Email Individual Users' => getDropdownArray( 'users', 0, 'u_' ),
			] );
	} elseif ( $type == "graphs" ) {
		$returnArray = array_merge( $returnArray,
			[
				'invoicedVsPaid' => "Invoiced vs Paid",
				'totalPayments'  => "Total Payments",
				'totalProfit'    => "Total Profit",
			] );
	} elseif ( $type == "graphtypes" ) {
		$returnArray = array_merge( $returnArray,
			[
				'area2d'     => "Area (2D)",
				'bar2d'      => "Bar (2D)",
				'column'     => "Column",
				'column2d'   => "Column (2D)",
				'doughnut2d' => "Doughnut (2D)",
				'funnel'     => "Funnel",
				'line'       => "Line",
				'pie'        => "Pie",
				'pie2d'      => "Pie (2D)",
			] );
	} elseif ( $type == "linkRelList" ) {
		$returnArray = array_merge( $returnArray,
			[
				'follow'   => "Do Follow",
				'nofollow' => "No Follow",
			] );
	} elseif ( $type == "menus" ) {
		$result = $ftsdb->select( DBTABLEPREFIX . "menus", "1 ORDER BY name", [], 'id, name' );

		if ( $result ) {
			foreach ( $result as $row ) {
				$returnArray[ $row['id'] ] = $row['name'];
			}
			$result = null;
		}
	} elseif ( $type == "pruning" ) {
		$returnArray = array_merge( $returnArray,
			[
				'0'  => "Do Not Prune Logs",
				'1'  => "1 month",
				'2'  => "2 months",
				'6'  => "6 months",
				'12' => "1 year",
			] );
	} elseif ( $type == "timezone" ) {
		$continents = [ 'Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific' ];

		$zones = [];
		foreach ( timezone_identifiers_list() as $zone ) {
			$zone = explode( '/', $zone );
			if ( ! in_array( $zone[0], $continents ) ) {
				continue;
			}

			// This determines what gets set and translated - we don't translate Etc/* strings here, they are done later
			$exists    = [
				0 => ( isset( $zone[0] ) && $zone[0] ),
				1 => ( isset( $zone[1] ) && $zone[1] ),
				2 => ( isset( $zone[2] ) && $zone[2] ),
			];
			$exists[3] = ( $exists[0] && 'Etc' !== $zone[0] );
			$exists[4] = ( $exists[1] && $exists[3] );
			$exists[5] = ( $exists[2] && $exists[3] );

			$zones[] = [
				'continent'   => ( $exists[0] ? $zone[0] : '' ),
				'city'        => ( $exists[1] ? $zone[1] : '' ),
				'subcity'     => ( $exists[2] ? $zone[2] : '' ),
				't_continent' => ( $exists[3] ? translate( str_replace( '_', ' ', $zone[0] ), 'continents-cities' ) : '' ),
				't_city'      => ( $exists[4] ? translate( str_replace( '_', ' ', $zone[1] ), 'continents-cities' ) : '' ),
				't_subcity'   => ( $exists[5] ? translate( str_replace( '_', ' ', $zone[2] ), 'continents-cities' ) : '' ),
			];
		}
		usort( $zones, '_wp_timezone_choice_usort_callback' );

		foreach ( $zones as $key => $zone ) {
			// Build value in an array to join later
			$value = [ $zone['continent'] ];

			if ( empty( $zone['city'] ) ) {
				// It's at the continent level (generally won't happen)
				$display = $zone['t_continent'];
			} else {
				// It's inside a continent group	
				// Add the city to the value
				$value[] = $zone['city'];

				$display = $zone['t_city'];
				if ( ! empty( $zone['subcity'] ) ) {
					// Add the subcity to the value
					$value[] = $zone['subcity'];
					$display .= ' - ' . $zone['t_subcity'];
				}
			}

			// Build the value
			$value                                         = join( '/', $value );
			$returnArray[ $zone['t_continent'] ][ $value ] = $display;
		}

		// Do UTC
		$returnArray['UTC']['UTC'] = 'UTC';

		// Do manual UTC offsets
		$offset_range = array(
			- 12,
			- 11.5,
			- 11,
			- 10.5,
			- 10,
			- 9.5,
			- 9,
			- 8.5,
			- 8,
			- 7.5,
			- 7,
			- 6.5,
			- 6,
			- 5.5,
			- 5,
			- 4.5,
			- 4,
			- 3.5,
			- 3,
			- 2.5,
			- 2,
			- 1.5,
			- 1,
			- 0.5,
			0,
			0.5,
			1,
			1.5,
			2,
			2.5,
			3,
			3.5,
			4,
			4.5,
			5,
			5.5,
			5.75,
			6,
			6.5,
			7,
			7.5,
			8,
			8.5,
			8.75,
			9,
			9.5,
			10,
			10.5,
			11,
			11.5,
			12,
			12.75,
			13,
			13.75,
			14,
		);

		foreach ( $offset_range as $offset ) {
			if ( 0 <= $offset ) {
				$offset_name = '+' . $offset;
			} else {
				$offset_name = (string) $offset;
			}

			$offset_value = $offset_name;
			$offset_name  = str_replace( [ '.25', '.5', '.75' ], [ ':15', ':30', ':45' ], $offset_name );
			$offset_name  = 'UTC' . $offset_name;
			$offset_value = 'UTC' . $offset_value;

			$returnArray['Manual Offsets'][ $offset_value ] = $offset_name;
		}

	} elseif ( $type == "urgency" ) {
		$returnArray = $returnArray + [
				LOW    => "Low",
				MEDIUM => "Medium",
				HIGH   => "High",
			]; // Preserve numeric keys by not using array_merge
	} elseif ( $type == "users" ) {
		$result = $ftsdb->select( USERSDBTABLEPREFIX . "users", "1 ORDER BY last_name", [], 'id, email_address, first_name, last_name' );

		if ( $result ) {
			foreach ( $result as $row ) {
				$returnArray[ $prefix . $row['id'] ] = $row['last_name'] . ', ' . $row['first_name'] . ' (' . $row['email_address'] . ')';
			}
			$result = null;
		}
	} elseif ( $type == "userlevel" ) {
		$returnArray[ $prefix . SYSTEM_ADMIN ] = 'System Admin';

		$result = $ftsdb->select( DBTABLEPREFIX . "roles" );

		if ( $result ) {
			foreach ( $result as $row ) {
				$returnArray[ $prefix . $row['id'] ] = $row['name'];
			}
			$result = null;
		}
	}

	// Load module dropdowns
	// Preserve numeric keys by not using array_merge
	$returnArray = $returnArray + (array) callModuleHook( '',
			'getDropdownArray',
			[
				'addDefaultOption' => $addDefaultOption,
				'prefix'           => $prefix,
				'type'             => $type,
			],
			1,
			'array'
		);

	return $returnArray;
}

/**
 * Create a dropdown without the need for repeating code.
 *
 * @param        $type
 * @param        $inputName
 * @param string $currentSelection
 * @param string $onChange
 * @param string $class
 * @param string $differentID
 *
 * @return string
 */
function createDropdown( $type, $inputName, $currentSelection = "", $onChange = "", $class = "", $differentID = "" ) {
	$onChangeVar = ( ! empty( $onChange ) ) ? ' onChange="' . $onChange . '"' : '';
	$classVar    = ( ! empty( $class ) ) ? ' class="' . $class . '"' : '';
	$id          = ( ! empty( $differentID ) ) ? $differentID : $inputName;

	$dropdown = '';

	$dropdownItems = getDropdownArray( $type );
	foreach ( $dropdownItems as $key => $value ) {
		if ( is_array( $value ) ) {
			// This is actually an optgroup so handle it accordingly
			$dropdown .= '<optgroup label="' . $key . '">';

			foreach ( $value as $realValue => $realName ) {
				$dropdown .= '<option value="' . $realValue . '" ' . testSelected( $realValue, $currentSelection ) . '>' . $realName . '</option>';
			}

			$dropdown .= '</optgroup>';
		} else {
			// Normal select option
			$dropdown .= '<option value="' . $key . '" ' . testSelected( $key, $currentSelection ) . '>' . $value . '</option>';
		}
	}

	// Load module dropdowns
	$dropdown .= callModuleHook( '',
		'createDropdown',
		[
			'type'             => $type,
			'inputName'        => $inputName,
			'currentSelection' => $currentSelection,
			'onChange'         => $onChange,
			'class'            => $class,
		] );

	return "<select name=\"{$inputName}\" id=\"{$id}\" {$classVar} {$onChangeVar}>
		{$dropdown}
	</select>";
}

if ( ! function_exists( 'str_ireplace' ) ) {
	/**
	 * Case insensitive str_replace.
	 *
	 * @param $search
	 * @param $replace
	 * @param $subject
	 *
	 * @return string|string[]|null
	 */
	function str_ireplace( $search, $replace, $subject ) {
		if ( is_array( $search ) ) {
			array_walk( $search, 'make_pattern' );
		} else {
			$search = '/' . preg_quote( $search, '/' ) . '/i';
		}

		return preg_replace( $search, $replace, $subject );
	}
}

/**
 * Add a prefix to the keys of an array.
 *
 * @access public
 *
 * @param mixed  $array
 * @param string $prefix (default: "")
 *
 * @return array The prefixed array.
 */
function prefixArray( $array, $prefix = "" ): array {
	$returnArray = [];

	if ( is_array( $array ) ) {
		foreach ( $array as $key => $value ) {
			if ( ! is_numeric( $key ) ) {
				$returnArray[ $prefix . $key ] = $value;
			}
		}
	}

	return $returnArray;
}

/**
 * Returns the value of a field from the database.
 *
 * @param        $table
 * @param        $field
 * @param        $input
 * @param string $defaultValue
 * @param string $checkField
 * @param string $extraSQL
 * @param string $order
 *
 * @return string
 */
function getDatabaseItem( $table, $field, $input, $defaultValue = "", $checkField = "id", $extraSQL = "", $order = "" ) {
	global $ftsdb;

	$table     = ( $table == "users" ) ? USERSDBTABLEPREFIX . $table : DBTABLEPREFIX . $table;
	$returnVar = $defaultValue;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( $input );
	$selectBindData   = $preparedInClause['data'];
	$selectBindData   = array_merge( $selectBindData,
		[
			":extraSQL" => $extraSQL,
			":order"    => $order,
		] );

	// Get our table data
	$sql = "$checkField IN (" . $preparedInClause['binds'] . ")";
	if ( ! empty( $extraSQL ) ) {
		$sql .= " :extraSQL";
	}
	if ( ! empty( $order ) ) {
		$sql .= " :order";
	}

	$result = $ftsdb->select( $table, $sql . " LIMIT 1", $selectBindData, $field );

	if ( $result ) {
		foreach ( $result as $row ) {
			$returnVar = $row[ $field ];
		}
		$result = null;
	}

	return $returnVar;
}

/**
 * Returns a database row as a tag array.
 *
 * @param        $table
 * @param        $id
 * @param string $valuePrefix
 * @param string $checkField
 *
 * @return array|void
 */
function getDatabaseArray( $table, $id, $valuePrefix = "", $checkField = "id" ) {
	global $ftsdb;

	$table         = ( $table == "users" ) ? USERSDBTABLEPREFIX . $table : DBTABLEPREFIX . $table;
	$dataArrayTemp = [];

	// Get our table data
	$result = $ftsdb->select( $table,
		$checkField . " = :id LIMIT 1",
		[
			":id" => $id,
		] );

	if ( $result ) {
		$dataArrayTemp = $result[0];
	}

	// Special tags
	if ( $table == USERSDBTABLEPREFIX . "users" ) {
		$dataArrayTemp['login_id'] = "u" . $dataArrayTemp['id'];
		$dataArrayTemp['name']     = $dataArrayTemp['first_name'] . " " . $dataArrayTemp['last_name'];
	}
	if ( $table == DBTABLEPREFIX . "clients" ) {
		$dataArrayTemp['login_id']      = $dataArrayTemp['id'];
		$dataArrayTemp['name']          = getPrimaryContactNameFromID( $dataArrayTemp['id'] );
		$dataArrayTemp['phone']         = getPrimaryContactPhoneNumberFromID( $dataArrayTemp['id'] );
		$dataArrayTemp['email_address'] = getPrimaryContactEmailAddressFromID( $dataArrayTemp['id'] );
	}

	// Add our prefix
	$dataArray = prefixArray( $dataArrayTemp, $valuePrefix );
	unset( $dataArrayTemp );

	// Add any built in tags
	$dataArray = array_merge( $dataArray, getBuiltinTags() );

	return $dataArray;
}

/**
 * Returns an array of builtin tags.
 *
 * @return array
 */
function getBuiltinTags() {
	$dataArray = [];

	// Add any built in tags
	$time                                 = time();
	$dataArray['current_date']            = makeDate( $time ); // Makes date in the format of: Thursday July 05, 2006
	$dataArray['current_time']            = makeTime( $time ); // Makes date in the format of: 3:30 PM
	$dataArray['current_date_time']       = makeDateTime( $time ); // Makes date in the format of: Thursday July 5, 2006 - 3:30 pm
	$dataArray['current_order_date_time'] = makeOrderDateTime( $time ); // Makes date in the format of: Jul 5, 2006 - 3:30 pm
	$dataArray['current_short_date']      = makeShortDate( $time ); // Makes date in the format of: 07/05/2006
	$dataArray['current_short_date_time'] = makeShortDateTime( $time ); // Makes date in the format of: 07/05/2006 - 3:30 pm
	$dataArray['current_year']            = makeYear( $time ); // Makes date in the format of: 2006
	$dataArray['current_month']           = makeMonth( $time ); // Makes date in the format of: Jul
	$dataArray['current_short_month']     = makeShortMonth( $time ); // Makes date in the format of: 05

	return $dataArray;
}

/**
 * Parses our content for tags and replaces them with the actual value.
 *
 * @param     $content
 * @param     $tag
 * @param     $value
 * @param int $useCleaner
 *
 * @return mixed
 */
function parseForTags( $content, $tag, $value, $useCleaner = 1 ) {
	$webTags = [ 'WEB1', 'WEB2', 'WEB3', 'WEB4', 'WEB5', 'WEB6', 'WEB7', 'WEB8' ];
	$urlTags = [ 'URL', 'MAIN_URL', 'VIEW_TICKET_URL', 'WEB1', 'WEB2', 'WEB3', 'WEB4', 'WEB5', 'WEB6', 'WEB7', 'WEB8' ];

	$cleanValue = ( $useCleaner == 1 ) ? keeptasafe( trim( $value ) ) : trim( $value );
	$cleanValue = ( $useCleaner == 2 ) ? str_replace( "'", "\'", $value ) : $cleanValue;
	$cleanValue = ( in_array( $tag, $webTags ) ) ? strtolower( $cleanValue ) : $cleanValue;
	$cleanValue = ( in_array( $tag, $urlTags ) ) ? prefixHttpLinks( $cleanValue ) : $cleanValue;

	// Handle {name} tags
	if ( $cleanValue != "" ) {
		$content = str_replace( '{' . strtolower( $tag ) . '}', $cleanValue, $content );
	}

	// Handle *NAME* tags
	if ( $cleanValue != "" ) {
		$content = str_replace( '*' . strtoupper( $tag ) . '*', $cleanValue, $content );
	}

	// Handle TAG_NAME tags
	if ( $cleanValue != "" ) {
		$content = str_replace( 'TAG_' . strtoupper( $tag ), $cleanValue, $content );
	}

	// Handle tagname tags
	$find    = [ ' ', 'www-', '-com', '-net', '-org' ];
	$replace = [ '-', 'www.', '.com', '.net', '.org' ];
	if ( $cleanValue != "" ) {
		$content = str_replace( strtolower( 'tag' . $tag ), strtolower( str_replace( $find, $replace, $cleanValue ) ), $content );
	}

	// Handle %name% tags
	if ( $cleanValue != "" ) {
		$content = str_replace( strtolower( '%' . $tag . '%' ), $cleanValue, $content );
	}

	return $content;
}

/**
 * Parses our content for tags and replaces them with the actual value.
 *
 * @param     $content
 * @param     $array
 * @param int $useCleaner
 *
 * @return mixed
 */
function parseForTagsFromArray( $content, $array, $useCleaner = 1 ) {
	if ( is_array( $array ) ) {
		foreach ( $array as $key => $value ) {
			$content = parseForTags( $content, $key, $value, $useCleaner );
		}
	}

	// Fix bad links
	$content = str_replace( [ 'http://http://', 'https://https://' ], [ 'http://', 'https://' ], $content );

	return $content;
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param int    $s     Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d     Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r     Maximum rating (inclusive) [ g | pg | r | x ]
 * @param bool   $img   True to return a complete IMG tag False for just the URL
 * @param array  $atts  Optional, additional key/value attributes to include in the IMG tag
 *
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 * @since 4.13.08.13
 */
function get_gravatar( $email, int $s = 80, $d = 'mm', $r = 'g', $img = false, array $atts = [] ) {
	$url = '//www.gravatar.com/avatar/';
	$url .= md5( strtolower( trim( $email ) ) );
	$url .= "?s=$s&d=$d&r=$r";
	if ( $img ) {
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val ) {
			$url .= ' ' . $key . '="' . $val . '"';
		}
		$url .= ' />';
	}

	return $url;
}

/**
 * Returns company info block.
 *
 * @return string
 */
function returnCompanyInfoBlock() {
	global $mbp_config;

	$companyInfoBlock = "";
	$companyInfoBlock .= ( $mbp_config['ftsmbp_invoice_company_name'] != "" ) ? $mbp_config['ftsmbp_invoice_company_name'] . "<br />" : "";
	$companyInfoBlock .= ( $mbp_config['ftsmbp_invoice_address'] != "" ) ? nl2br( $mbp_config['ftsmbp_invoice_address'] ) . "<br />" : "";
	$companyInfoBlock .= ( $mbp_config['ftsmbp_invoice_city'] != "" ) ? $mbp_config['ftsmbp_invoice_city'] . ", " . $mbp_config['ftsmbp_invoice_state'] . " " . $mbp_config['ftsmbp_invoice_zip'] . "<br />" : "";
	$companyInfoBlock .= ( $mbp_config['ftsmbp_invoice_phone_number'] != "" ) ? "Phone: " . $mbp_config['ftsmbp_invoice_phone_number'] . "<br />" : "";
	$companyInfoBlock .= ( $mbp_config['ftsmbp_invoice_fax'] != "" ) ? "Fax: " . $mbp_config['ftsmbp_invoice_fax'] . "<br />" : "";
	$companyInfoBlock .= ( $mbp_config['ftsmbp_invoice_email_address'] != "" ) ? "Email: " . $mbp_config['ftsmbp_invoice_email_address'] . "<br />" : "";
	$companyInfoBlock .= ( $mbp_config['ftsmbp_invoice_website'] != "" ) ? "Website: " . $mbp_config['ftsmbp_invoice_website'] . "<br />" : "";

	return $companyInfoBlock;
}

/**
 * Returns an array of directory names.
 *
 * @param        $dirRequested
 * @param string $ignore
 *
 * @return array
 */
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

/**
 * Returns an array of file names in a directory.
 *
 * @param     $dirRequested
 * @param int $keepExtension
 *
 * @return array
 */
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

/**
 * Returns a list of links and categories based on the sitemaps var.
 *
 * @return string
 */
function returnSitemapList() {
	global $page;

	$returnVar           = '<ul class="sitemap">';
	$currentSitemapLinks = $page->getTemplateVar( "SitemapLinks" );
	if ( is_array( $currentSitemapLinks ) && count( $currentSitemapLinks ) > 0 ) {
		foreach ( $currentSitemapLinks as $sectionName => $sectionLinks ) {
			$returnVar .= "<li>
							<strong>$sectionName</strong>
							<ul>";
			foreach ( $sectionLinks as $key => $values ) {
				$nameHTML  = '<span>' . $values['name'] . '</span>';
				$linkMe    = ( ! empty( $values['link'] ) ) ? '<a href="' . $values['link'] . '">' . $nameHTML . '</a>' : $nameHTML;
				$returnVar .= "<li>$linkMe</li>";
			}
			$returnVar .= '</ul>';
		}
	}
	$returnVar .= '</ul>';

	return $returnVar;
}

/**
 * Rebuilds the sitemap.xml file.
 */
function rebuildSitemapXML() {
	global $page;

	$baseURL             = 'http://' . $_SERVER['HTTP_HOST'] . rtrim( dirname( $_SERVER['PHP_SELF'] ), '/\\' );
	$pages               = "<url><loc>$baseURL/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>";
	$currentSitemapLinks = $page->getTemplateVar( "SitemapLinks" );

	if ( is_array( $currentSitemapLinks ) && count( $currentSitemapLinks ) > 0 ) {
		foreach ( $currentSitemapLinks as $sectionName => $sectionLinks ) {
			foreach ( $sectionLinks as $key => $values ) {
				if ( ! empty( $values['link'] ) ) {
					$pages .= "<url><loc>" . ( ( ! stristr( $values['link'], 'http://' ) && ! stristr( $values['link'], 'https://' ) ) ? $baseURL : '' ) . $values['link'] . "</loc><changefreq>daily</changefreq><priority>1.0</priority></url>";
				}
			}
		}
	}
	$sitemap = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . $pages . '</urlset>';

	// Write the file
	$fp     = fopen( "sitemap.xml", "w+" );
	$result = fwrite( $fp, $sitemap );
	fclose( $fp );
	//echo $storeMe;
}

/**
 * Outputs correct status line header.
 *
 * Depending on php sapi one of the two following forms is used:
 *
 * Status: 404 Not Found
 *
 * HTTP/1.x 404 Not Found
 *
 * HTTP version is taken from HTTP_VERSION environment variable,
 * and defaults to 1.0.
 *
 * Sample usage:
 *
 * send_status_line(404, 'Not Found');
 *
 * @param int    $code    HTTP status code
 * @param string $message Message for the status code
 */
function send_status_line( $code, $message ) {
	if ( substr( strtolower( @php_sapi_name() ), 0, 3 ) === 'cgi' ) {
		// in theory, we shouldn't need that due to php doing it. Reality offers a differing opinion, though
		header( "Status: $code $message", true, $code );
	} else {
		if ( ! empty( $_SERVER['SERVER_PROTOCOL'] ) ) {
			$version = $_SERVER['SERVER_PROTOCOL'];
		} else {
			$version = 'HTTP/1.0';
		}
		header( "$version $code $message", true, $code );
	}
}

/**
 * Gets the version for the specified app.
 *
 * @param string $app
 */
function returnCurrentAppVersion( $app = A_NAME ) {
	// global $fts_http;

	//	$result = $fts_http->request( 'https://www.fasttracksites.com/versions/versionChecker.php', 'POST', [
	//		'app' => $app,
	//		'type' => 1
	//	] );

	//	return $result;
}

/**
 * Checks for a valid serial number.
 *
 * @param string $app
 * @param string $serial
 *
 * @return int
 */
function checkSerialNumber( $app = A_NAME, $serial = A_LICENSE ) {
	// global $fts_http;

	//	$result = $fts_http->request( 'https://www.fasttracksites.com/versions/serialChecker.php', 'POST', [
	//		'app' => $app,
	//		'serial' => $serial
	//	] );

	//	return $result;
	return 1;
}

/**
 * Checks if a license allows for multiple users.
 *
 * @return int
 */
function canHaveMultipleUsers() {
	return ( A_LICENSE == 'FREE_VERSION' ) ? 0 : 1;
}

/**
 * Returns an image serial validation.
 *
 * @param string $app
 * @param string $serial
 *
 * @return string
 */
function returnIsValidSerialNumberImage( $app = A_NAME, $serial = A_LICENSE ) {
	$versionImage = ( checkSerialNumber( $app, $serial ) == 1 ) ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove"></i>';

	return $versionImage;
}

//==================================================
// This function will notify user of updates and
// other important information
//
// USAGE:
// version_functions();
// 
// Removal or hindrance is a direct violation of
// the program license and is constituted as a 
// breach of contract as is punishable by law.
//==================================================
/**
 * @param string $function
 * @param int    $forceCheck
 */
function version_functions( $function = '', $forceCheck = 0 ) {
	// global $ftsdb, $mbp_config, $fts_http;

	//	//=========================================================
	//	// Should we print out wether or not to renew the license?
	//	//=========================================================
	//	if ( $function == 'checkForExpiredLicense' ) {
	//		// Perform check once a day
	//		if ( $mbp_config['ftsmbp_last_license_check'] < time() || $forceCheck ) {
	//			$showRenewLicenseBox = 0;
	//			$renewLicenseBoxText = '';
	//			$nextCheck = '';
	//
	//			$updateData = returnAppUpdateData();
	//			//var_export($updateData);
	//
	//			if ( $updateData->expiredSerial || $updateData->expires->days <= 45 || $forceCheck ) {
	//				$showRenewLicenseBox = 1;
	//
	//				if ( $updateData->expiredSerial ) {
	//					$class = 'alert-danger';
	//					$title = 'Your License is Expired';
	//				} else {
	//					$class = 'alert-warning';
	//
	//					if ( $updateData->expires->days <= 7 ) {
	//						$title = 'Your License is Expiring in ' . $updateData->expires->days . ' Days';
	//						$nextCheck = strtotime( '+1 day' );
	//					} else {
	//						$title = 'Your License is Expiring in ' . $updateData->expires->weeks . ' Weeks';
	//						$nextCheck = strtotime( '+1 week' );
	//					}
	//				}
	//
	//				$renewLicenseBoxText = '
	//					<div id="expiringLicenseNotice" class="jumbotron text-center alert ' . $class . '">
	//						<h1>' . $title . '</h1>
	//						<p>Please renew your license to continue receiving:</p>
	//						<ul>
	//							<li>Security Updates</li>
	//							<li>New Features</li>
	//							<li>Priority Support</li>
	//							<li>And more!</li>
	//						</ul>
	//						<a href="https://www.fasttracksites.com/product/license-renewal" class="btn btn-primary btn-lg">Renew Your License</a>
	//					</div>';
	//			}
	//
	//			// Update our last license check
	//			add_config_value( 'ftsmbp_show_renew_license_box', $showRenewLicenseBox );
	//			add_config_value( 'ftsmbp_renew_license_box_text', $renewLicenseBoxText );
	//			add_config_value( 'ftsmbp_last_license_check', $nextCheck );
	//		}
	//
	//		// Pull our cached copy of the box and display it
	//		if ( get_config_value('ftsmbp_show_renew_license_box') ) {
	//			return get_config_value('ftsmbp_renew_license_box_text');
	//		}
	//	}
	//	//=========================================================
	//	// Should we print out wether or not to update?
	//	//=========================================================
	//	elseif ( $function == 'checkForUpdates' ) {
	//		// Check the current version against the latest one
	//		if ( $latest_version = returnCurrentAppVersion( A_NAME ) ) {
	//			//echo "'" . trim($A_Version) . "' '" . trim($latest_version) . "'";
	//			if ( trim( A_VERSION ) == trim( $latest_version ) ) {
	//				return return_alert( 'Your application version is the newest. Thank you for staying up to date.', 1, 'glyphicon glyphicon-ok', 'updateNotification' );
	//			} elseif ( trim( A_VERSION ) > trim( $latest_version ) ) {
	//				return return_info_alert( 'You are running a development version. Sneaky sneaky :)', 1, 'glyphicons glyphicons-fire', 'updateNotification' );
	//			} else {
	//				return return_error_alert( 'An update is available, please <a href="http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/' . 'update.php" rel="nofollow">update now</a> to keep your system secure.', 1, 'glyphicons glyphicons-warning-sign', 'updateNotification' );
	//			}
	//		} else {
	//			return return_error_alert( 'Version check connection failed.', 0 );
	//		}
	//	} else {
	//		// Perform call in-ins and version checks once a week
	//		if ( $mbp_config['ftsmbp_last_license_check'] < strtotime( '-7 days' ) || $forceCheck ) {
	//			$validLicense = 1;
	//			$validLicenseText = '';
	//
	//			// Call in
	//			$callHomeResult = $fts_http->request( 'https://www.fasttracksites.com/callhome.php', 'POST', [
	//				'version' => A_VERSION,
	//				'application' => A_NAME,
	//				'license' => A_LICENSE,
	//				'licensedto' => A_LICENSED_TO,
	//				'website' => "http://" . $_SERVER['HTTP_HOST'],
	//				'location' => "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']
	//			] );
	//
	//			// Check serial number
	//			if ( $valdSerial = checkSerialNumber( A_NAME, A_LICENSE ) ) {
	//				$validLicense = $valdSerial;
	//			}
	//
	//			// Check for blacklisitng
	//			if ( $blacklistCheckResult = $fts_http->request('https://www.fasttracksites.com/versions/advancedOptionsChecker.php', 'POST', [ 'site' => urlencode( $_SERVER['HTTP_HOST'] ) ] ) ) {
	//				if ($blacklistCheckResult != '') {
	//					$validLicense = 0;
	//					$validLicenseText = $blacklistCheckResult;
	//				}
	//			}
	//
	//			// Use default message if necessary
	//			if ( $validLicense == 0 && empty( $validLicenseText ) ) {
	//				$validLicenseText = 'You are using an invalid serial number please check the number and contact <a href="https://www.fasttracksites.com">Paden Clayton</a> for further support.';
	//			}
	//
	//			// Update license file
	//			$str = "<?php\n\ndefine('A_LICENSE', '" . A_LICENSE . "');\ndefine('A_LICENSED_TO', '" . A_LICENSED_TO . "');\ndefine('A_VALID_LICENSE', '" . intval( $validLicense ) . "');\ndefine('A_VALID_LICENSE_TEXT', '" . htmlspecialchars( $validLicenseText ) . "');";
	//
	//			$fp=fopen( '_license.php', 'w+' );
	//			$result = fwrite( $fp, $str );
	//			fclose( $fp );
	//
	//			// Update our last license check
	//			add_config_value( 'ftsmbp_last_license_check', time() );
	//		}
	//	}
}