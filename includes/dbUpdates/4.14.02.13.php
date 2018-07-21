<?php

// Changes on 4.14.02.13
$sql = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "logging` (
			`id` bigint(19) NOT NULL auto_increment,
			`created` datetime NULL,
			`type` mediumint(8) NOT NULL,
			`assoc_id` bigint(19) NULL,
			`message` text DEFAULT NULL,
			`start` varchar(25) DEFAULT NULL,
			`stop` varchar(25) DEFAULT NULL,
			PRIMARY KEY  (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$result = $ftsdb->run($sql);
if ( !config_value_exists('ftsmbp_enable_logging') ) { 
	add_config_value( 'ftsmbp_enable_logging', '0' );
}
if ( !config_value_exists('ftsmbp_logging_prune') ) { 
	add_config_value( 'ftsmbp_logging_prune', '1' );
}