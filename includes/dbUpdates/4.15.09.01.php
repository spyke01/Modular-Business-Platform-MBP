<?php

// Changes for 4.15.09.01

$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "notifications` ADD `type` BIGINT(19) NULL AFTER `user_id`";
$result = $ftsdb->run( $sql );