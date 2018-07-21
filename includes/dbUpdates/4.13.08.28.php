<?php

// Changes on 4.13.08.28
// Add our new columns to the users table
$sql = "ALTER TABLE `" . USERSDBTABLEPREFIX . "users` 
	ADD `token_activation` varchar(50) NULL,
	ADD `token_password_reset` varchar(50) NULL";
$result = $ftsdb->run($sql);

// Copy theme values from default
add_config_value( 'ftsmbp_enable_public_account_creation', 0 );
add_config_value( 'ftsmbp_require_account_activation', 0 );