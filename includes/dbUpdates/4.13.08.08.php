<?php

// Changes before 4.13.08.08
// Add our new columns to the categories table
$sql = "ALTER TABLE `" . DBTABLEPREFIX . "categories` ADD `parent_id` bigint(19) NOT NULL default 0, ADD `order` bigint(19) NOT NULL";
$result = $ftsdb->run($sql);

// Add parent_id to menu_items
$sql = "ALTER TABLE `" . DBTABLEPREFIX . "menu_items` ADD `parent_id` BIGINT( 19 ) DEFAULT 0 NOT NULL AFTER `menu_id` ;";
$result = $ftsdb->run($sql);

// Add our new columns to the menus table
$sql = "ALTER TABLE `" . DBTABLEPREFIX . "menus` ADD `added_by` VARCHAR( 100 ) NOT NULL DEFAULT '', ADD `prefix` VARCHAR( 100 ) NOT NULL DEFAULT ''";
$result = $ftsdb->run($sql);

$sql = "ALTER TABLE `" . DBTABLEPREFIX . "config` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run($sql);

// Make sure we have our widgets table
$sql = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "widgets` (
	  `id` bigint(19) NOT NULL auto_increment,
	  `widget_id` varchar(100) NOT NULL DEFAULT '',
	  `area` varchar(100) NOT NULL DEFAULT '',
	  `order` bigint(19) NOT NULL default 999,
	  `type` varchar(100) NOT NULL DEFAULT '',
	  `settings` longtext NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 ;";
$result = $ftsdb->run($sql);

// Changes from 4.13.08.08
$sql = "ALTER TABLE `" . DBTABLEPREFIX . "categories`
	CHANGE `name` `name` varchar(50) DEFAULT NULL,
	CHANGE `color` `color` varchar(50) DEFAULT NULL, # appointments
	CHANGE `tags` `tags` text NULL, # articles
	CHANGE `order` `order` bigint(19) NULL default 999,
	ADD INDEX (`parent_id`),
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
$result = $ftsdb->run($sql);

$sql = "ALTER TABLE `" . DBTABLEPREFIX . "menus` 
	CHANGE `name` `name` varchar(100) DEFAULT NULL,
	CHANGE `added_by` `added_by` varchar(100) DEFAULT NULL,
	CHANGE `prefix` `prefix` varchar(100) DEFAULT NULL,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run($sql);

$sql = "ALTER TABLE `" . DBTABLEPREFIX . "menu_items` 
	CHANGE `text` `text` varchar(100) DEFAULT NULL,
	CHANGE `link` `link` text NULL,
	CHANGE `added_by` `added_by` varchar(100) DEFAULT NULL,
	CHANGE `prefix` `prefix` varchar(100) DEFAULT NULL,
	CHANGE `order` `order` bigint(19) NULL default 999,
	ADD INDEX (`menu_id`),
	ADD INDEX (`parent_id`),
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run($sql);

$sql = "ALTER TABLE `" . DBTABLEPREFIX . "modules` 
	CHANGE `name` `name` varchar(100) NULL DEFAULT '',
	CHANGE `description` `description` text NULL,
	CHANGE `developer` `developer` varchar(100) DEFAULT NULL,
	CHANGE `version` `version` varchar(100) DEFAULT NULL,
	CHANGE `prefix` `prefix` varchar(100) DEFAULT NULL,
	CHANGE `active` `active` tinyint(1) NULL DEFAULT 1,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run($sql);

$sql = "ALTER TABLE `" . DBTABLEPREFIX . "permissions` 
	CHANGE `name` `name` varchar(50) DEFAULT NULL,
	CHANGE `file` `file` varchar(50) DEFAULT NULL,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run($sql);

$sql = "ALTER TABLE `" . DBTABLEPREFIX . "rewrites` 
	CHANGE `match` `match` varchar(255) DEFAULT NULL,
	CHANGE `query` `query` varchar(255) DEFAULT NULL,
	CHANGE `added_by` `added_by` varchar(100) DEFAULT NULL,
	CHANGE `prefix` `prefix` varchar(100) DEFAULT NULL,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run($sql);

$sql = "ALTER TABLE `" . DBTABLEPREFIX . "roles` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run($sql);

$sql = "ALTER TABLE `" . USERSDBTABLEPREFIX . "users` 
	CHANGE `user_level` `user_level` tinyint(4) NULL DEFAULT 0,
	CHANGE `first_name` `first_name` varchar(50) DEFAULT NULL,
	CHANGE `last_name` `last_name` varchar(50) DEFAULT NULL,
	CHANGE `email_address` `email_address` varchar(35) DEFAULT NULL,
	CHANGE `website` `website` varchar(100) DEFAULT NULL,
	CHANGE `company` `company` varchar(50) NULL,
	CHANGE `signup_date` `signup_date` int(11) DEFAULT NULL,
	CHANGE `notes` `notes` text NULL,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run($sql);

$sql = "ALTER TABLE `" . DBTABLEPREFIX . "widgets` 
	CHANGE `area` `area` varchar(100) DEFAULT NULL,
	CHANGE `order` `order` bigint(19) NULL default 999,
	CHANGE `type` `type` varchar(100) DEFAULT NULL,
	CHANGE `settings` `settings` longtext NULL,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run($sql);