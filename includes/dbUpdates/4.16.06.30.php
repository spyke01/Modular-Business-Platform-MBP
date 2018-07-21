<?php

// Changes before 4.16.06.30

// Add our new email_logs table
$sql = "CREATE TABLE `" . DBTABLEPREFIX . "email_logs` (
		`id` bigint(19) NOT NULL auto_increment,
		`sent` datetime NULL,
		`email_address` varchar(255) DEFAULT NULL,
		`subject` varchar(255) DEFAULT NULL,
		`message` longtext DEFAULT NULL,
		PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$result = $ftsdb->run($sql);

// Add our new columns to the users table
$sql = "ALTER TABLE `" . USERSDBTABLEPREFIX . "users` ADD `token_date` DATETIME NULL AFTER `token_password_reset` ;";
$result = $ftsdb->run($sql);