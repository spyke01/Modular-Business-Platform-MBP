<?php

// Changes before 4.14.02.28
// Add our new columns to the menu_items table
$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "config` ADD `id` BIGINT( 19 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
$result = $ftsdb->run( $sql );