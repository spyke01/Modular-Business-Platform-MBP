<?php

// Changes before 4.14.08.05

//==================================================
// Change users signup_date to a datetime field
//==================================================
// Add our new columns to the users table
$sql    = "ALTER TABLE `" . USERSDBTABLEPREFIX . "users` ADD `signup_date_new` DATETIME NULL";
$result = $ftsdb->run( $sql );

// Copy the data over
$result = $ftsdb->select( USERSDBTABLEPREFIX . 'users' );

if ( $result ) {
	foreach ( $result as $row ) {
		$returnArray[ $row['id'] ] = $row['name'];

		$result2 = $ftsdb->update( USERSDBTABLEPREFIX . 'users',
			[
				'signup_date_new' => date( 'Y-m-d H:i:s', $row['signup_date'] ),
			],
			"id = :id",
			[
				":id" => $row['id'],
			]
		);
	}
	$result = null;
}

// Drop the old column
$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "users` DROP `signup_date`";
$result = $ftsdb->run( $sql );

// Rename the new one
$sql    = "ALTER TABLE `" . DBTABLEPREFIX . "users` CHANGE `signup_date_new` `signup_date` DATETIME NULL";
$result = $ftsdb->run( $sql );