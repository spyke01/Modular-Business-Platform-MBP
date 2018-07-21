<?php

// Changes for 4.16.11.15.02

// Updates our permissions
$sql = "ALTER TABLE `" . DBTABLEPREFIX . "permissions` 
	CHANGE `name` `name` varchar(255) DEFAULT NULL,
	CHANGE `file` `file` varchar(255) DEFAULT NULL";
$result = $ftsdb->run($sql);