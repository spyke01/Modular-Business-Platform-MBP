<?php

// Changes before 4.14.11.24

//==================================================
// Fixes icon names for new glyphicons update
//==================================================
$sql = "UPDATE `" . DBTABLEPREFIX . "menu_items` SET `icon` = REPLACE(`icon`, '_', '-')";
$result = $ftsdb->run( $sql );

$sql = "UPDATE `" . DBTABLEPREFIX . "menu_items` SET `icon` = REPLACE(`icon`, 'glyphicons ', 'glyphicons glyphicons-')";
$result = $ftsdb->run( $sql );

// Fix any bugs where we get duplicates
$sql = "UPDATE `" . DBTABLEPREFIX . "menu_items` SET `icon` = REPLACE(`icon`, 'glyphicons glyphicons-glyphicons-', 'glyphicons glyphicons-')";
$result = $ftsdb->run( $sql );