<?php

// Changes for 4.16.11.15.02

// Updates our permissions
$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "permissions` 
	CHANGE `name` `name` VARCHAR(255) DEFAULT NULL,
	CHANGE `file` `file` VARCHAR(255) DEFAULT NULL";
$result = $ftsdb->run( $sql );