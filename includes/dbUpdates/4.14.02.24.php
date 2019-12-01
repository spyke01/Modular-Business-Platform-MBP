<?php

// Changes on 4.14.02.24
add_config_value( 'ftsmbp_email_account_new_subject', '%site_title%: New Account' );
add_config_value( 'ftsmbp_email_account_update_subject', '%site_title%: Account Updated' );

$result        = $ftsdb->delete( DBTABLEPREFIX . 'permissions',
	"name = :name",
	[
		":name" => 'generatePassword',
	] );
$result        = $ftsdb->insert( DBTABLEPREFIX . 'permissions',
	[
		"name"     => 'generatePassword',
		"file"     => '',
		"role_ids" => '0,2,4,5,6,8',
	] );
$fileFunctions = new fileFunctions();
$fileFunctions->delete( BASEPATH . '/javascripts/tiny_mce', true );