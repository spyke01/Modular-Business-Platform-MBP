<?php

// Changes before 4.13.08.08
// Add our new columns to the categories table
$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "categories` ADD `parent_id` BIGINT(19) NOT NULL DEFAULT 0, ADD `order` BIGINT(19) NOT NULL";
$result = $ftsdb->run( $sql );

// Add parent_id to menu_items
$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "menu_items` ADD `parent_id` BIGINT( 19 ) DEFAULT 0 NOT NULL AFTER `menu_id` ;";
$result = $ftsdb->run( $sql );

// Add our new columns to the menus table
$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "menus` ADD `added_by` VARCHAR( 100 ) NOT NULL DEFAULT '', ADD `prefix` VARCHAR( 100 ) NOT NULL DEFAULT ''";
$result = $ftsdb->run( $sql );

$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "config` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run( $sql );

// Make sure we have our widgets table
$sql    = "CREATE TABLE IF NOT EXISTS `" . DBTABLEPREFIX . "widgets` (
	  `id` BIGINT(19) NOT NULL AUTO_INCREMENT,
	  `widget_id` VARCHAR(100) NOT NULL DEFAULT '',
	  `area` VARCHAR(100) NOT NULL DEFAULT '',
	  `order` BIGINT(19) NOT NULL DEFAULT 999,
	  `type` VARCHAR(100) NOT NULL DEFAULT '',
	  `settings` LONGTEXT NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 ;";
$result = $ftsdb->run( $sql );

// Changes from 4.13.08.08
$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "categories`
	CHANGE `name` `name` VARCHAR(50) DEFAULT NULL,
	CHANGE `color` `color` VARCHAR(50) DEFAULT NULL, # appointments
	CHANGE `tags` `tags` TEXT NULL, # articles
	CHANGE `order` `order` BIGINT(19) NULL DEFAULT 999,
	ADD INDEX (`parent_id`),
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
$result = $ftsdb->run( $sql );

$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "menus` 
	CHANGE `name` `name` VARCHAR(100) DEFAULT NULL,
	CHANGE `added_by` `added_by` VARCHAR(100) DEFAULT NULL,
	CHANGE `prefix` `prefix` VARCHAR(100) DEFAULT NULL,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run( $sql );

$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "menu_items` 
	CHANGE `text` `text` VARCHAR(100) DEFAULT NULL,
	CHANGE `link` `link` TEXT NULL,
	CHANGE `added_by` `added_by` VARCHAR(100) DEFAULT NULL,
	CHANGE `prefix` `prefix` VARCHAR(100) DEFAULT NULL,
	CHANGE `order` `order` BIGINT(19) NULL DEFAULT 999,
	ADD INDEX (`menu_id`),
	ADD INDEX (`parent_id`),
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run( $sql );

$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "modules` 
	CHANGE `name` `name` VARCHAR(100) NULL DEFAULT '',
	CHANGE `description` `description` TEXT NULL,
	CHANGE `developer` `developer` VARCHAR(100) DEFAULT NULL,
	CHANGE `version` `version` VARCHAR(100) DEFAULT NULL,
	CHANGE `prefix` `prefix` VARCHAR(100) DEFAULT NULL,
	CHANGE `active` `active` TINYINT(1) NULL DEFAULT 1,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run( $sql );

$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "permissions` 
	CHANGE `name` `name` VARCHAR(50) DEFAULT NULL,
	CHANGE `file` `file` VARCHAR(50) DEFAULT NULL,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run( $sql );

$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "rewrites` 
	CHANGE `match` `match` VARCHAR(255) DEFAULT NULL,
	CHANGE `query` `query` VARCHAR(255) DEFAULT NULL,
	CHANGE `added_by` `added_by` VARCHAR(100) DEFAULT NULL,
	CHANGE `prefix` `prefix` VARCHAR(100) DEFAULT NULL,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run( $sql );

$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "roles` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run( $sql );

$sql    = "ALTER TABLE `" . USERSDBTABLEPREFIX . "users` 
	CHANGE `user_level` `user_level` TINYINT(4) NULL DEFAULT 0,
	CHANGE `first_name` `first_name` VARCHAR(50) DEFAULT NULL,
	CHANGE `last_name` `last_name` VARCHAR(50) DEFAULT NULL,
	CHANGE `email_address` `email_address` VARCHAR(35) DEFAULT NULL,
	CHANGE `website` `website` VARCHAR(100) DEFAULT NULL,
	CHANGE `company` `company` VARCHAR(50) NULL,
	CHANGE `signup_date` `signup_date` INT(11) DEFAULT NULL,
	CHANGE `notes` `notes` TEXT NULL,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run( $sql );

$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "widgets` 
	CHANGE `area` `area` VARCHAR(100) DEFAULT NULL,
	CHANGE `order` `order` BIGINT(19) NULL DEFAULT 999,
	CHANGE `type` `type` VARCHAR(100) DEFAULT NULL,
	CHANGE `settings` `settings` LONGTEXT NULL,
	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$result = $ftsdb->run( $sql );