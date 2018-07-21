<?php

// Make sure at least our default values are in place		
$defaultSettings = array(
	'ftsmbp_site_name' => 'Modular Business Platform',
	'ftsmbp_site_url' => 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'),
	'ftsmbp_logo' => SITE_URL . '/themes/default/images/logo.png',
	'ftsmbp_mod_rewrite' => 0,
	'ftsmbp_use_html_suffix' => 0,
	'ftsmbp_copyright' => 'Copyright &copy; 2011 - ' . date('Y') . ' Paden Clayton',
	'ftsmbp_show_powered_by' => 1,
	'ftsmbp_charset' => 'UTF-8',
);		

if (count($defaultSettings)) {
	foreach ($defaultSettings as $name => $value) {			
		if ( !config_value_exists($name) ) {
			add_config_value($name,  $value);
		}
	}
}

// Do menu updates		
$defaultMenuItems = array(
	'Top Menu' => array(
		'added_by' => 'System', 
		'prefix' => ''
	),
	'Footer Menu' => array(
		'added_by' => 'System', 
		'prefix' => ''
	),
);		

if (count($defaultMenuItems)) {
	foreach ($defaultMenuItems as $name => $infoArray) {			
		$result = $ftsdb->select(DBTABLEPREFIX . "menus", "name = :name", array(
			":name" => $name
		));
		if (!$result) {
			addMenu($name, $infoArray['added_by'], $infoArray['prefix']);
			$result = NULL;
		}
	}
}

// Do menu item updates		
$defaultMenuItems = array(
	'index.php?p=admin&s=widgets' => array(
		'menu_id' => 2,
		'parent_id' => 0, 
		'text' => 'Widgets',
		'added_by' => 'System', 
		'prefix' => '', 
		'order' => '4', 
		'role_ids' => ''
	),
);		

if ( count( $defaultMenuItems ) ) {
	foreach ( $defaultMenuItems as $link => $infoArray ) {			
		$result = $ftsdb->select(DBTABLEPREFIX . "menu_items", "link = :link AND menu_id = :menu_id ORDER BY id", array(
			":link" => $link,
			":menu_id" => $infoArray['menu_id'],
		));
		if ( !$result || count ( $result ) == 0 ) {
			$result2 = $ftsdb->insert(DBTABLEPREFIX . 'menu_items', array(
				"text" => $infoArray['text'],
				"menu_id" => $infoArray['menu_id'],
				"parent_id" => $infoArray['parent_id'],
				"link" => $link,
				"added_by" => $infoArray['added_by'],
				"prefix" => $infoArray['prefix'],
				"role_ids" => $infoArray['role_ids'],
				"order" => $infoArray['order'],
			));
		}
		if ( count( $result ) > 1 ) {
			// We have extra widgets page links for some reason so kill them
			$result2 = $ftsdb->delete(DBTABLEPREFIX . 'menu_items', "link = :link AND menu_id = :menu_id AND id > :firstID", array(
				":link" => $link,
				":menu_id" => $infoArray['menu_id'],
				":firstID" => $result[0]['id'],
			));
		}
		$result = NULL;
	}
}

// Fix default logo
$result = $ftsdb->update(DBTABLEPREFIX . "config", array(
		"value" => SITE_URL . '/themes/bootstrap/images/logo.png'
	), 
	"name = 'ftsmbp_logo' AND value = 'themes/default/images/logo.png'"
);

$result = $ftsdb->update(DBTABLEPREFIX . "config", array(
		"value" => SITE_URL . '/themes/modern/images/logo.png'
	), 
	"name = 'ftsmbp_logo' AND value = 'themes/bootstrap/images/logo.png'"
);

// Mark all the built in menus as System menus
$result = $ftsdb->update(DBTABLEPREFIX . "menus", array(
		"added_by" => 'System'
	), 
	"name IN ('User Menu', 'Admin Menu', 'Top Menu')"
);

// Fix a bug where the User user role gets an ID of 1 instead of 0
// This bug is taken into account within header.php but this makes sure our DB matches
$result = $ftsdb->update(DBTABLEPREFIX . "roles", array(
		"id" => '0'
	), 
	"name = 'User' AND id != 0"
);

// Make sure we have our rewrites table
$sql = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "rewrites` (
	  `id` bigint(19) NOT NULL auto_increment,
	  `match` varchar(255) NOT NULL DEFAULT '',
	  `query` varchar(255) NOT NULL DEFAULT '',
	  `added_by` varchar(100) NOT NULL DEFAULT '',
	  `prefix` varchar(100) NOT NULL DEFAULT '',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 ;";
$result = $ftsdb->run($sql);	

// Check our rewrites are in the DB		
$defaultRewrites = array(
	'module/([A-Za-z-_]+)/([A-Za-z-_]+)/?$' => 'index.php?p=module&prefix=$matches[1]&module_page=$matches[2]', 
	'module/([A-Za-z-_]+)/([A-Za-z-_]+)/([A-Za-z-_]+)/?$' => 'index.php?p=module&prefix=$matches[1]&module_page=$matches[2]&page=$matches[3]', 
	'module/([A-Za-z-_]+)/([A-Za-z-_]+)/([0-9]+)/?$' => 'index.php?p=module&prefix=$matches[1]&module_page=$matches[2]&id=$matches[3]', 
	'([A-Za-z-_]+)/?$' => 'index.php?p=$matches[1]', 
	'([A-Za-z-_]+)/([A-Za-z-_]+)/?$' => 'index.php?p=$matches[1]&s=$matches[2]', 
	'([A-Za-z-_]+)/([A-Za-z-_]+)/([0-9]+)/?$' => 'index.php?p=$matches[1]&s=$matches[2]&id=$matches[3]'
);

if (count($defaultRewrites)) {
	foreach ($defaultRewrites as $match => $query) {	
		if ( !url_rewrite_exists($name) ) {
			add_url_rewrite($match, $query, 'System');
		}
	}
}