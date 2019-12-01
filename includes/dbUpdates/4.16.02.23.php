<?php

// Changes before 4.16.02.23

// Add our new columns to the users table
// Also increase the size of a few columns
$sql    = "ALTER TABLE `" . USERSDBTABLEPREFIX . "users` 
	CHANGE `username` `username` VARCHAR(255) NOT NULL DEFAULT '',
	CHANGE `email_address` `email_address` VARCHAR(255) DEFAULT NULL,
	CHANGE `website` `website` VARCHAR(255) DEFAULT NULL,
	CHANGE `company` `company` VARCHAR(255) NULL,
	ADD `title` VARCHAR(255) DEFAULT NULL,
	ADD `phone_number` VARCHAR(255) DEFAULT NULL,
	ADD `facebook` VARCHAR(255) DEFAULT NULL,
	ADD `twitter` VARCHAR(255) DEFAULT NULL,
	ADD `google_plus` VARCHAR(255) DEFAULT NULL,
	ADD `pinterest` VARCHAR(255) DEFAULT NULL,
	ADD `instagram` VARCHAR(255) DEFAULT NULL,
	ADD `linkedin` VARCHAR(255) DEFAULT NULL";
$result = $ftsdb->run( $sql );