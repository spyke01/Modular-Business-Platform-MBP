<?php
/***************************************************************************
 *                               permissions.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


//=========================================================
// Checks if we have access to an item
//=========================================================
function user_access( $string, $account = null, $reset = false ) {
	global $ftsdb;

	static $perm = [];
	$perms = [];

	if ( $reset ) {
		$perm = [];
	}

	// If a user isn't logged in their user level should be marked as anonymous
	$userID    = ( isset( $_SESSION['userid'] ) ) ? $_SESSION['userid'] : 'anonymous';
	$userLevel = ( isset( $_SESSION['userid'] ) ) ? $_SESSION['user_level'] : ANONYMOUS_ACCESS;

	// System Admins have all privileges:
	if ( $userLevel == SYSTEM_ADMIN ) {
		$perm[ $userID ][ $string ] = '';

		return true;
	}

	// To reduce the number of SQL queries, we cache the user's permissions
	// in a static variable.
	if ( ! isset( $perm[ $userID ] ) ) {

		// Check first if the permission is in the DB, if its not then add it
		$result = $ftsdb->select( DBTABLEPREFIX . "permissions",
			"name = :name",
			[
				":name" => $string,
			],
			'name' );

		if ( $result ) {
			$result = null;
		} else {
			// Add it to the DB
			$result = $ftsdb->insert( DBTABLEPREFIX . 'permissions',
				[
					"name" => $string,
				] );

			if ( $userLevel != SYSTEM_ADMIN ) {
				return false;
			}
		}

		$result = $ftsdb->select( DBTABLEPREFIX . "permissions",
			"role_ids LIKE :role_ids",
			[
				":role_ids" => '%' . $userLevel . ',%',
			],
			'name' );

		if ( $result ) {
			foreach ( $result as $row ) {
				$perms[ $row['name'] ] = '';
			}
			$result = null;
		}

		$perm[ $userID ] = $perms;
	}

	//print_r($perm);
	return isset( $perm[ $userID ][ $string ] );
}

//=========================================================
// Defines each user role as a constant that we can check against
//=========================================================
function createUserRoleDefinitions() {
	global $ftsdb;

	$result = $ftsdb->select( DBTABLEPREFIX . "roles" );

	if ( $result ) {
		foreach ( $result as $row ) {
			define( strtoupper( str_replace( ' ', '_', $row['name'] ) ), $row['id'] );
		}
		$result = null;
	}
	//define('USER', 0); // In case the DB is messed up on this level
	define( 'SYSTEM_ADMIN', 1 );
}

//==================================================
// Adds a permission setting in the database
//==================================================
function add_permision_setting( $name, $role_ids ) {
	global $ftsdb;

	delete_permision_setting( $name );
	$result = $ftsdb->insert( DBTABLEPREFIX . 'permissions',
		[
			"name"     => $name,
			"role_ids" => $role_ids,
		] );
}

//==================================================
// Deletes a permission setting from the database
//==================================================
function delete_permision_setting( $name ) {
	global $ftsdb;

	$result = $ftsdb->delete( DBTABLEPREFIX . 'permissions',
		"name = :name",
		[
			":name" => $name,
		] );
}

//==================================================
// Returns the role_ids of a permission setting in the database
//==================================================
function get_permission_setting( $name ) {
	return getDatabaseItem( 'permissions', 'role_ids', $name, "", "name" );
}

//==================================================
// Checks if a permission setting is in the database
//==================================================
function permision_setting_exists( $name ) {
	global $ftsdb;

	$exists  = 0;
	$results = $ftsdb->select( DBTABLEPREFIX . "permissions",
		"name = :name",
		[
			":name" => $name,
		] );
	if ( $results && count( $results ) > 0 ) {
		$exists = 1;
	}
	$results = null;

	return $exists;
}

//=================================================
// Create a form to edit user role permissions
//=================================================
function printEditUserRolePermissionsForm() {
	global $ftsdb, $menuvar;
	$roles = [];

	$result = $ftsdb->select( DBTABLEPREFIX . "roles", "1 ORDER BY id" );

	if ( $result ) {
		foreach ( $result as $row ) {
			$roles[ $row['id'] ] = $row['name'];
		}
		$result = null;
	}

	$result = $ftsdb->select( DBTABLEPREFIX . "permissions", "1 ORDER BY name" );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "editUserRolePermissionsTable" );

	// Create table title
	$numRows = ( ! $result ) ? 0 : count( $result );
	$table->addNewRow( [ [ 'data' => "User Role Permissions", "colspan" => "20" ] ], '', 'title1', 'thead' );

	// Create column headers
	$headerDataArray = [
		[ 'type' => 'th', 'data' => "" ],
	];

	// Add our role names
	foreach ( $roles as $id => $name ) {
		array_push( $headerDataArray, [ 'type' => 'th', 'data' => $name ] );
	}

	$table->addNewRow( $headerDataArray, '', 'title2', 'thead' );

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( [ [ 'data' => "There are no permissions in the system.", "colspan" => "20" ] ], "editUserRolePermissionsTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$rowDataArray = [
				[ 'data' => $row['name'] ],
			];

			// Add our role checkboxes
			foreach ( $roles as $id => $name ) {
				array_push( $rowDataArray, [ 'data' => "<input type=\"checkbox\" name=\"" . $row['id'] . "['" . $id . "']\" value=\"1\"" . testChecked( true, ( ( strpos( $row['role_ids'] . ',', $id . ',' ) === false ) ? false : true ) ) . " />" ] );
			}

			$table->addNewRow( $rowDataArray, $row['id'] . "_row", "" );
		}
		$result = null;
	}

	return "
		<form name=\"editUserRolePermissionsForm\" id=\"editUserRolePermissionsForm\" action=\"" . $menuvar['PERMISSIONS'] . "\" method=\"post\" class=\"inputForm\" onsubmit=\"return false;\">
			" . $table->returnTableHTML() . "
			<div class=\"form-actions\"><input type=\"submit\" class=\"btn btn-primary\" value=\"Update Permissions\" /></div>
		</form>
		<div id=\"editUserRolePermissionsResponse\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// edit order form
//=================================================
function returnEditUserRolePermissionsFormJQuery() {
	$JQueryReadyScripts = '
		$("#editUserRolePermissionsTable").tablesorter({ widgets: [\'zebra\'] });' . "
		var v = jQuery(\"#editUserRolePermissionsForm\").validate({
			errorElement: \"div\",
			errorClass: \"validation-advice\",
			submitHandler: function(form) {		
				$('#editUserRolePermissionsResponse').html('" . progressSpinnerHTML() . "');		
				jQuery.post('" . SITE_URL . "/ajax.php?action=editUserRolePermissions', $('#editUserRolePermissionsForm').serialize(), function(data) {
					// Update the proper div with the returned data
					$('#editUserRolePermissionsResponse').html(data);
				});
			}
		});";

	return $JQueryReadyScripts;
}