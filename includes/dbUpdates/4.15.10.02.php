<?php

// Changes for 4.15.10.02

$sql = "ALTER TABLE `" . DBTABLEPREFIX . "menu_items` ADD `rel` varchar(255) DEFAULT NULL AFTER `icon`";
$result = $ftsdb->run($sql);