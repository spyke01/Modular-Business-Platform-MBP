<?php

// Changes for 4.16.11.15.01

// Updates our permissions
if ( permision_setting_exists( 'createUser' ) ) {
	add_permision_setting( 'users_create', get_permission_setting( 'createUser' ) );
	delete_permision_setting( 'createUser' );
}
if ( permision_setting_exists( 'editUser' ) ) {
	add_permision_setting( 'users_edit', get_permission_setting( 'editUser' ) );
	delete_permision_setting( 'editUser' );
}
if ( ! permision_setting_exists( 'users_edit' ) ) {
	add_permision_setting( 'users_edit', '0,2,5,6,8,' );
}