<?php

// Changes before 4.16.02.23

// Add our new columns to the users table
// Also increase the size of a few columns
$sql = "ALTER TABLE `" . USERSDBTABLEPREFIX . "users` 
	CHANGE `username` `username` varchar(255) NOT NULL DEFAULT '',
	CHANGE `email_address` `email_address` varchar(255) DEFAULT NULL,
	CHANGE `website` `website` varchar(255) DEFAULT NULL,
	CHANGE `company` `company` varchar(255) NULL,
	ADD `title` varchar(255) DEFAULT NULL,
	ADD `phone_number` varchar(255) DEFAULT NULL,
	ADD `facebook` varchar(255) DEFAULT NULL,
	ADD `twitter` varchar(255) DEFAULT NULL,
	ADD `google_plus` varchar(255) DEFAULT NULL,
	ADD `pinterest` varchar(255) DEFAULT NULL,
	ADD `instagram` varchar(255) DEFAULT NULL,
	ADD `linkedin` varchar(255) DEFAULT NULL";
$result = $ftsdb->run($sql);