<?php

// Changes before 4.14.03.11
// Add our new columns to the menu_items table
$sql = "ALTER TABLE `" . DBTABLEPREFIX . "logging` ADD `assoc_id2` bigint(19) NULL, ADD `assoc_id3` bigint(19) NULL AFTER `assoc_id`";
$result = $ftsdb->run($sql);