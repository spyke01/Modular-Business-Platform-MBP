<?php

// Changes on 4.14.02.13
$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "logging` (
			`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
			`created` DATETIME NULL,
			`type` MEDIUMINT(8) NOT NULL,
			`assoc_id` BIGINT(19) NULL,
			`message` TEXT DEFAULT NULL,
			`start` VARCHAR(25) DEFAULT NULL,
			`stop` VARCHAR(25) DEFAULT NULL,
			PRIMARY KEY  (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$result = $ftsdb->run( $sql );
if ( ! config_value_exists( 'ftsmbp_enable_logging' ) ) {
	add_config_value( 'ftsmbp_enable_logging', '0' );
}
if ( ! config_value_exists( 'ftsmbp_logging_prune' ) ) {
	add_config_value( 'ftsmbp_logging_prune', '1' );
}