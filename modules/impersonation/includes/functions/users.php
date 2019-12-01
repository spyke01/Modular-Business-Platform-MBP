<?php
/***************************************************************************
 *                               users.php
 *                            -------------------
 *   begin                : Monday, December 20, 2016
 *   copyright            : (C) 2016 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


/**
 * Returns the string used for user_access checks for impersonation.
 *
 * @param string $userLevel
 *
 * @return string
 */
function return_impersonate_access_level_name( $userLevel ) {
	$roleName = returnUserlevelText( $userLevel );
	$roleName = str_replace( ' ', '_', strtolower( $roleName ) );

	// Are we authorized to touch this user?
	return 'users_impersonate_' . $roleName;
}

/**
 * Attempt to impersonate a user - Called via the perform_impersonation action
 *
 * @access public
 *
 * @param string $impersonationID (default: '')
 *
 * @return void
 */
function perform_impersonation( $impersonationID = '' ) {
	global $ftsdb, $mbp_config;

	// Set up our vars
	$myImpersonationPrefix = 'users';
	$impersonationIDParts  = explode( '_', $impersonationID );
	$userID                = array_pop( $impersonationIDParts );
	$impersonationPrefix   = implode( '_', $impersonationIDParts );

	// Make sure this is ours to handle	
	if ( $impersonationPrefix == $myImpersonationPrefix ) {

		// Does the user exist?
		$result = $ftsdb->select( USERSDBTABLEPREFIX . "users",
			"id = :id",
			[
				":id" => $userID,
			] );
		if ( $result && count( $result ) == 1 ) {
			$row = $result[0];

			// Are we authorized to touch this user?
			if ( user_access( return_impersonate_access_level_name( $row['user_level'] ) ) || $impersonationID == $_SESSION['actual_user_impersonate_id'] ) {
				// Handle the impersonation

				// Track our old data
				$_SESSION['actual_userid'] = $_SESSION['userid'];

				$_SESSION['actual_username']            = $_SESSION['username'];
				$_SESSION['actual_user_table']          = $_SESSION['user_table'];
				$_SESSION['actual_user_impersonate_id'] = $_SESSION['actual_user_table'] . '_' . $_SESSION['actual_userid'];

				// Track our new data
				$_SESSION['STATUS']        = 'true';
				$_SESSION['user_table']    = 'users';
				$_SESSION['userid']        = $row['id'];
				$_SESSION['username']      = $row['username'];
				$_SESSION['epassword']     = $row['password'];
				$_SESSION['email_address'] = $row['email_address'];
				$_SESSION['first_name']    = $row['first_name'];
				$_SESSION['last_name']     = $row['last_name'];
				$_SESSION['full_name']     = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
				$_SESSION['website']       = $row['website'];
				$_SESSION['user_level']    = $row['user_level'];
				$_SESSION['is_client']     = 0;
				//$_SESSION['script_locale'] = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				$_SESSION['canUploadImages'] = user_access( 'tinymce_upload_images_access' );

				// Trigger any additional actions needed during login
				do_action( 'perform_login_items_for_user', $_SESSION['userid'], 'users' );
			}

			$result = null;
		}
	}
}

/**
 * Attempt to stop impersonating a user
 *
 * @access public
 * @return void
 */
function perform_stop_impersonation() {
	global $ftsdb, $mbp_config;

	// Cheat and call impersonate user
	do_action( 'perform_impersonation', $_SESSION['actual_user_table'] . '_' . $_SESSION['actual_userid'] );

	// Remove the actual_userid so we no longer show that we are impersonating
	unset( $_SESSION['actual_userid'] );
}

/**
 * Print the Impersonate Users Table
 *
 * @return string
 */
function returnImpersonationForm() {
	global $impersonationMenus, $mbp_config;

	$formFields = apply_filters( 'form_fields_impersonate_impersonate_users',
		[
			'impersonate_id' => [
				'text'    => 'Account to Impersonate',
				'type'    => 'select',
				'options' => getDropdownArray( 'impersonate_ids' ),
			],
		] );

	return makeForm( 'impersonateUsers', il( $impersonationMenus['IMPERSONATE']['link'] ), '<i class="glyphicon glyphicon-eye-open"></i> Impersonate Users', 'Impersonate', $formFields );
}

/**
 * Returns the JQuery functions used to run the new order form.
 *
 * @return string
 */
function returnImpersonationFormJQuery() {
	return makeFormJQuery( 'impersonateUsers' );
}

/**
 * Print the Inline Impersonate Users Table
 * This is used in the header of a site
 *
 * @return string
 */
function returnInlineImpersonationForm() {
	global $impersonationMenus, $page;

	if ( isset( $_SESSION['actual_userid'] ) ) {
		// Show the "Stop Impersonating" button
		return '<div id="impersontationHolder"><a href="' . il( $impersonationMenus['IMPERSONATE']['link'] . '&action=stop' ) . '"><span class="btn btn-danger">Stop Impersonating ' . $_SESSION['full_name'] . '</span></a></div>';
	} elseif ( user_access( 'impersonation_access' ) ) {
		// Print the impersonation form
		$page->setTemplateVar( "JQueryReadyScript", $page->getTemplateVar( "JQueryReadyScript" ) . returnImpersonationFormJQuery() );

		$formFields = apply_filters( 'form_fields_impersonate_impersonate_users',
			[
				'impersonate_id' => [
					'name'      => 'impersonate_id',
					'text'      => 'Account to Impersonate',
					'type'      => 'select',
					'options'   => getDropdownArray( 'impersonate_ids' ),
					'showLabel' => 0,
					'class'     => 'required',
					'required'  => true,
				],
			] );
		$formItems  = '';

		foreach ( $formFields as $name => $formItem ) {
			$formItems .= getFormItemFromArray( $formItem );
		}

		return '
			<div id="impersontationHolder">
				<form name="impersonateUsersForm" id="impersonateUsersForm" action="' . il( $impersonationMenus['IMPERSONATE']['link'] ) . '" method="post" class="form-inline" role="form">
					' . $formItems . '
					<input type="submit" name="submit" class="btn btn-success" value="Impersonate" />
				</form>
			</div>';
	}
}