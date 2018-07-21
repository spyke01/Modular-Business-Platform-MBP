<?php

// Changes for 4.15.05.14

$sql = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "notifications` (
		`id` bigint(19) NOT NULL auto_increment,
		`user_id` bigint(19) NULL,
		`icon` varchar(255) DEFAULT NULL,
		`message` text DEFAULT NULL,
		`link` varchar(255) DEFAULT NULL,
		`created` datetime NULL,
		`read` tinyint(1) NULL DEFAULT 0,
		PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$result = $ftsdb->run($sql);