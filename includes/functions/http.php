<?php 
/***************************************************************************
 *                               http.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



/*
*
* is_https function is used to check whether we are using HTTPS or not
* $_SERVER['HTTPS'] is the pivotal variable to scrutinize
* According to official documentation, $_SERVER['HTTPS'] is set to some non-empty value if HTTPS is used
* So, first we have checked whether $_SERVER['HTTPS'] is empty or not
* Special case: when using ISAPI with IIS, the value will be off if the request was not made through the HTTPS protocol
* So, we will check that $_SERVER['HTTPS'] is not set to 'off'
* Along with above mentioned two conditions, some ill-configured servers might not have $_SERVER['HTTPS'] defined
* even if SSL is used. So, we have checked for port too.
*
*/
function is_https(){
    if ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443)
        return true;
    else
        return false;
} 

//==================================================
// Make sure the site is loaded with or without the WWW
//==================================================
function handleWWWRedirection() {
	global $mbp_config;
	
	$currentURL = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	$newURL = '';
	
	// Add 'www.' if it is absent and should be there
	if ( false !== strpos(site_url(), '://www.') && false === strpos($currentURL, '://www.') )
		$newURL = str_replace('://', '://www.', $currentURL);

	// Strip 'www.' if it is present and shouldn't be
	if ( false === strpos(site_url(), '://www.') && false !== strpos($currentURL, '://www.') ) 
		$newURL = str_replace('://www.', '://', $currentURL);
	
	if ( !empty( $newURL ) ) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $newURL");
	}
}

//==================================================
// Returns a valid internal link
//==================================================
function il( $link, $fullLink = 0 ) {
	global $mbp_config;
	
	if ($mbp_config['ftsmbp_mod_rewrite'] && !stristr($link, 'http://') && !stristr($link, 'https://')) { // Make sure we aren't trying t rewrite an external or asolute URL
		$newLink = createRewriteURL($link);
	} else {
		$newLink = $link;
	}
	if (!empty($newLink) && $fullLink && !stristr($newLink, 'http://') && !stristr($newLink, 'https://')) $newLink = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . $newLink;
	
	return $newLink;
}

//==================================================
// Takes a normal internal URL and rewrites it
//==================================================
function createRewriteURL( $link ) {
	global $mbp_config;
	
	$validRewrites = array('p', 's', 'prefix', 'module_page', 'page', 'id');
	$extraRewrites = array();
	$newLink = '';
	$useHTMLSuffix = $mbp_config['ftsmbp_use_html_suffix'];
	$link = str_replace(array('index.php?', 'index.php', '&amp;'), array('', '', '&'), $link);
	
	// If there is no result then do the default rewrites
	if (!empty($link)) {
		$rewriteArray = $validRewrites;
		parse_str($link, $queryArray);	
	
		// Let each module try to rewrite their links first
		if ($queryArray['prefix']) {
			$moduleRewriteInfo = callModuleHook($queryArray['prefix'], 'moduleRewriteInfo', array(
				'queryArray' => $queryArray
			), 1, 'array');
			
			// If our module needs custom rewrites lets do it
			if (is_array($moduleRewriteInfo) && count($moduleRewriteInfo) > 0) {
				if (isset($moduleRewriteInfo['rewriteArray'])) $rewriteArray = $moduleRewriteInfo['rewriteArray'];
				if (isset($moduleRewriteInfo['linkPrefix'])) $newLink = $moduleRewriteInfo['linkPrefix'];
				if (isset($moduleRewriteInfo['useHTMLSuffix'])) $useHTMLSuffix = $moduleRewriteInfo['useHTMLSuffix'];
			}
		}
		
		if (count($rewriteArray)) {
			foreach ($rewriteArray as $key => $value) {
				// Check $value=
				if (!empty($queryArray[$value])) $newLink .= $queryArray[$value] . '/';
			}
		}
		
		// Kill items from master array
		foreach (array_merge($validRewrites, $rewriteArray) as $key => $value) {
			// Kill already used items
			unset($queryArray[$value]);
		}
		
		if (count($queryArray)) {	
			// Create full link using foreach since we have numerical values in the array
			$extraItems = '';
			foreach($queryArray as $key => $value){
				if(!is_numeric($key)){
					if (is_array($key)) print_r($key);
					$extraItems .= "&" . $key . "=$value";
				}
			} 
			if (!empty($extraItems)) $newLink .= '?' . substr($extraItems, 1);
		}
		if (!empty($newLink) && $useHTMLSuffix) $newLink = rtrim($newLink, '/') . '.html';
		if (!empty($newLink)) $newLink = '/' . $newLink;
	}
	
	if (empty($newLink)) $newLink = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/';
	
    return $newLink;
}

//==================================================
// Parses our rewritten URL
//==================================================
function parseRewrites() {
	global $ftsdb, $mbp_config;
		
	$req_uri = $_SERVER['REQUEST_URI'];
	$req_uri_array = explode('?', $req_uri);
	$req_uri = $req_uri_array[0];
	$req_uri = trim($req_uri, '/');
	$rewriteRules = array();

	// Not using rewrite rules, so we're out of options
	if ( !$mbp_config['ftsmbp_mod_rewrite'] )
		return 0;
	
	// Get our rewrites
	$result = $ftsdb->select(DBTABLEPREFIX . "rewrites", "1 ORDER BY prefix DESC", array(), '`match`, `query`');
	
	if ($result) {
		foreach ($result as $row) {
			$rewriteRules[$row['match']] = $row['query'];
		}
		$result = NULL;
	}
	
	// Add 'www.' if it is absent and should be there
	if ( false !== strpos(site_url(), '://www.') && false === strpos($req_uri, '://www.') )
		$req_uri = str_replace('://', '://www.', $req_uri);

	// Strip 'www.' if it is present and shouldn't be
	if ( false === strpos(site_url(), '://www.') )
		$req_uri = str_replace('://www.', '://', $req_uri);
		
	// Look for matches.
	$matched_rule = $matched_query = '';
	$request_match = $req_uri;
	foreach ( (array)$rewriteRules as $match => $query ) {
		// If the requesting file is the anchor of the match, prepend it
		// to the path info.
		if ( (! empty($req_uri)) && ($req_uri != $request) && (strpos($match, $req_uri) === 0) )
			$request_match = $req_uri . '/' . $request;
		
		if ( preg_match("#^$match#", $request_match, $matches) || preg_match("#^$match#", urldecode($request_match), $matches) ) {
			// Got a match.
			$matched_rule = $match;
	
			// Trim the query of everything up to the '?'.
			$query = preg_replace("!^.+\?!", '', $query);
	
			// Substitute the substring matches into the query.
			$query = addslashes(FTS_MapRewriteMatches::apply($query, $matches));
	
			$matched_query = $query;
	
			// Parse the query.
			parse_str($query, $newQueryData);
			
			// Fill our main global arrays
			foreach ($newQueryData as $key => $val) {
				$_GET[$key] = $val;
				$_REQUEST[$key] = $val;
			}
			
			break;
		}
	}
}

//=========================================================
// Prefixes links with http://
//=========================================================
function prefixHttpLinks($input) {
	global $mbp_config;
	
	$output = "http://" . str_replace(array("http://", "https://"), '', $input);

	return $output;
}

//=========================================================
// Returns the proper http or https depending on the system setting
//=========================================================
function returnHttpLinks($input) {
	global $mbp_config;
	
	$output = ($mbp_config['ftsmbp_use_https'] == 1) ? str_replace("http://", "https://", $input) : str_replace("https://", "http://", $input);
	
	return $output;
}

//=========================================================
// Adds a trailing slash to a string
//=========================================================
function addTrailingSlash($string) {
	return removeTrailingSlash($string) . '/';
}

//=========================================================
// Removes a trailing slash to a string
//=========================================================
function removeTrailingSlash($string) {
	return rtrim($string, '/');
}

//==================================================
// Adds a url rewrite in the database
//==================================================
function add_url_rewrite($match, $query, $added_by = 'System', $prefix = '') {
	global $ftsdb; 
	
	delete_url_rewrite($match);
	$result = $ftsdb->insert(DBTABLEPREFIX . 'rewrites', array(
		"match" => $match,
		"query" => $query,
		"added_by" => $added_by,
		"prefix" => $prefix
	));
}

//==================================================
// Deletes a url rewrite from the database
//==================================================
function delete_url_rewrite($match) {
	global $ftsdb; 
	
	$result = $ftsdb->delete(DBTABLEPREFIX . 'rewrites', "`match` = :match", array(
		":match" => $match
	));
}

//==================================================
// Returns the value of a url rewrite in the database
//==================================================
function get_url_rewrite($match) {
	return getDatabaseItem('rewrites', 'query', $match, "", "match");
}

//==================================================
// Updates a url rewrite in the database
//==================================================
function update_url_rewrite($match, $query) {
	global $ftsdb; 
	
	$result = $ftsdb->update(DBTABLEPREFIX . 'rewrites', array(
			":query" => $query
		), "`match` = :match", array(
			":match" => $match
		)
	);
}

//==================================================
// Checks if a url rewrite is in the database
//==================================================
function url_rewrite_exists($match) {
	global $ftsdb; 
	
	$exists = 0;
	$results = $ftsdb->select(DBTABLEPREFIX . "rewrites", "`match` = :match", array(
		":match" => $name
	));
	if ( $results && count( $results ) > 0 ) { $exists = 1; }
	$results = NULL;
	
	return $exists;
}

//=========================================================
// Allows us to get any remote file we need with post vars
// DEPRECATED
//=========================================================	
function returnRemoteFilePost($host, $directory, $filename, $urlVariablesArray = array()) {
	global $fts_http; 
	
	return $fts_http->request("http://" . $host . "/" . $directory . "/" . $filename, 'POST', $urlVariablesArray);
}

//=========================================================
// Allows us to get any remote URL using curl
// DEPRECATED
//=========================================================	
function curlGetURL($url) {
	global $fts_http; 
	
	return $fts_http->request($url);
}