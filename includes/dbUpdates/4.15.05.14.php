<?php

// Changes for 4.15.05.14

$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "notifications` (
		`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
		`user_id` BIGINT(19) NULL,
		`icon` VARCHAR(255) DEFAULT NULL,
		`message` TEXT DEFAULT NULL,
		`link` VARCHAR(255) DEFAULT NULL,
		`created` DATETIME NULL,
		`read` TINYINT(1) NULL DEFAULT 0,
		PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$result = $ftsdb->run( $sql );