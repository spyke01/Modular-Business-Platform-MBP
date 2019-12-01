<?php

// Changes before 4.14.01.08
// Add our new columns to the menu_items table
$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "menu_items` ADD `icon` VARCHAR(255) DEFAULT NULL AFTER `text`";
$result = $ftsdb->run( $sql );