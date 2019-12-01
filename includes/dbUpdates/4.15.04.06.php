<?php

// Changes before 4.15.04.06

// Fix permission field lengths
$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "permissions` 
	CHANGE `name` `name` VARCHAR(255) DEFAULT NULL,
	CHANGE `file` `file` LONGTEXT NULL";
$result = $ftsdb->run( $sql );

// Fix emails
$sql    = "UPDATE `" . DBTABLEPREFIX . "email_templates` SET subject = REPLACE(subject, 'tag_site_', 'tagsite_'), message = REPLACE(message, 'tag_site_', 'tagsite_');";
$result = $ftsdb->run( $sql );