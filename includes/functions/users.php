<?php
/***************************************************************************
 *                               users.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


//=========================================================
// Checks our session and tries to recreate an expired one using a cookie
//=========================================================
function checkSessionCookie() {
	global $ftsdb, $mbp_config;

	$cookiename = $mbp_config['ftsmbp_cookie_name'];
	if ( isset( $_COOKIE[ $cookiename ] ) && $_SESSION['STATUS'] != 'true' && ! defined( 'IN_LOGIN' ) ) {
		$data = explode( '-', $_COOKIE[ $cookiename ] );

		// Call our perform login action
		do_action( 'perform_login', '', $data[1], $data[0] );
	}
}

//=========================================================
// Our login action - Called via the perform_login action
//=========================================================
function perform_login( $username, $encryptedPassword, $userID = '' ) {
	global $ftsdb, $mbp_config;

	$sql    = ( empty( $userID ) ) ? "( username = :username OR email_address = :username ) AND password = :password" : "id = :id AND password = :password";
	$result = $ftsdb->select( USERSDBTABLEPREFIX . "users",
		$sql,
		[
			":id"       => $userID,
			":username" => $username,
			":password" => $encryptedPassword,
		] );
	if ( $result && count( $result ) == 1 ) {
		$row = $result[0];

		$_SESSION['STATUS']          = 'true';
		$_SESSION['user_table']      = 'users';
		$_SESSION['userid']          = $row['id'];
		$_SESSION['username']        = $row['username'];
		$_SESSION['epassword']       = $row['password'];
		$_SESSION['email_address']   = $row['email_address'];
		$_SESSION['first_name']      = $row['first_name'];
		$_SESSION['last_name']       = $row['last_name'];
		$_SESSION['full_name']       = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
		$_SESSION['website']         = $row['website'];
		$_SESSION['user_level']      = $row['user_level'];
		$_SESSION['is_client']       = 0;
		$_SESSION['script_locale']   = rtrim( dirname( $_SERVER['PHP_SELF'] ), '/\\' );
		$_SESSION['canUploadImages'] = user_access( 'tinymce_upload_images_access' );

		// Trigger any additional actions needed during login
		do_action( 'perform_login_items_for_user', $_SESSION['userid'], 'users' );

		$result = null;
	}
}


/**
 * generateStrongPassword function.
 *
 * Generates a strong password of N length containing at least one lower case letter,
 * one uppercase letter, one digit, and one special character. The remaining characters
 * in the password are chosen at random from those four sets.
 *
 * The available characters in each set are user friendly - there are no ambiguous
 * characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
 * makes it much easier for users to manually type or speak their passwords.
 *
 * Note: the $add_dashes option will increase the length of the password by
 * floor(sqrt(N)) characters.
 *
 * @access public
 *
 * @param int    $length         (default: 9)
 * @param bool   $add_dashes     (default: false)
 * @param string $available_sets (default: 'luds')
 *
 * @return void
 */
function generateStrongPassword( $length = 9, $add_dashes = false, $available_sets = 'luds' ) {
	$sets = [];
	if ( strpos( $available_sets, 'l' ) !== false ) {
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	}
	if ( strpos( $available_sets, 'u' ) !== false ) {
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	}
	if ( strpos( $available_sets, 'd' ) !== false ) {
		$sets[] = '23456789';
	}
	if ( strpos( $available_sets, 's' ) !== false ) {
		$sets[] = '!@#$%&*?';
	}

	$all      = '';
	$password = '';
	foreach ( $sets as $set ) {
		$password .= $set[ array_rand( str_split( $set ) ) ];
		$all      .= $set;
	}

	$all = str_split( $all );
	for ( $i = 0; $i < $length - count( $sets ); $i ++ ) {
		$password .= $all[ array_rand( $all ) ];
	}

	$password = str_shuffle( $password );

	if ( ! $add_dashes ) {
		return $password;
	}

	$dash_len = floor( sqrt( $length ) );
	$dash_str = '';
	while ( strlen( $password ) > $dash_len ) {
		$dash_str .= substr( $password, 0, $dash_len ) . '-';
		$password = substr( $password, $dash_len );
	}
	$dash_str .= $password;

	return $dash_str;
}

//==================================================
// Returns an array of the user data
//==================================================
function getUser( $userID ) {
	return getDatabaseArray( 'users', $userID );
}

//=========================================================
// Gets a list of user IDs for the current user
//=========================================================
function getMyUserIDs( $reset = false ) {
	global $ftsdb, $mbp_config;
	static $myUsers = '';

	if ( $reset ) {
		$myUsers = '';
	}

	if ( empty( $myUsers ) ) {
		$extraSQL = ( user_access( 'manage_all_users' ) ) ? '1' : "id = :id";
		$result   = $ftsdb->select( USERSDBTABLEPREFIX . "users",
			$extraSQL,
			[
				":id" => $_SESSION['userid'],
			] );

		if ( $result ) {
			foreach ( $result as $row ) {
				$myUsers .= ',' . $row['id'];
			}
			$result = null;
		}
	}

	return ltrim( $myUsers, ',' );
}

//=========================================================
// Lets us know if we can access a specific id
//=========================================================
function canAccessUser( $userID ) {
	global $ftsdb, $mbp_config;

	$accessibleIDs = explode( ',', getMyUserIDs() );
	$canAccess     = ( in_array( $userID, $accessibleIDs ) ) ? true : false;

	return $canAccess;
}

//==================================================
// Checks if a username is in the database
//==================================================
function username_exists( $username ) {
	global $ftsdb;

	$exists  = 0;
	$results = $ftsdb->select( DBTABLEPREFIX . "users",
		"username = :username",
		[
			":username" => $username,
		] );
	if ( $results && count( $results ) > 0 ) {
		$exists = 1;
	}
	$results = null;

	return $exists;
}

//==================================================
// Gets the total number of users in the systen
//==================================================
function getUserCount() {
	global $ftsdb;

	$totalUsers = 0;
	$results    = $ftsdb->select( DBTABLEPREFIX . "users", "", [], 'COUNT(id) AS totalUsers' );
	if ( $results && count( $results ) > 0 ) {
		$totalUsers = $results[0]['totalUsers'];
	}
	$results = null;

	return $totalUsers;
}

//=========================================================
// Gets a username from a userid
//=========================================================
function getUsernameFromID( $userID ) {
	return getDatabaseItem( 'users', 'username', $userID );
}

//=========================================================
// Gets a username from a userid
//=========================================================
function getUsersNameFromID( $userID ) {
	$userData = getUser( $userID );

	return $userData['first_name'] . ' ' . $userData['last_name'];
}

//=========================================================
// Gets the text of a user level
//=========================================================
function returnUserlevelText( $userLevel ) {
	$level = ( $userLevel == SYSTEM_ADMIN ) ? 'System Admin' : getDatabaseItem( 'roles', 'name', $userLevel );

	return $level;
}

//=========================================================
// Gets a user's userlevel from a userid
//=========================================================
function getUserlevelFromID( $userID ) {
	$level = returnUserlevelText( getDatabaseItem( 'users', 'user_level', $userID ) );

	return $level;
}

//=========================================================
// Gets an email address from a userid
//=========================================================
function getEmailAddressFromID( $userID ) {
	return getDatabaseItem( 'users', 'email_address', $userID );
}

//=================================================
// Print the Users Table
//=================================================
function printUsersTable( $searchVars = [] ) {
	global $ftsdb, $menuvar, $mbp_config, $tableColumns;

	$currentTimestamp  = time();
	$todayTimestamp    = strtotime( gmdate( 'Y-m-d', $currentTimestamp + ( 3600 * '-7.00' ) ) );
	$tomorrowTimestamp = strtotime( gmdate( 'Y-m-d', strtotime( "+1 day" ) + ( 3600 * '-7.00' ) ) );

	$extraSQL = "1";
	$extraSQL .= ( ! empty( $searchVars['search_username'] ) ) ? " AND username LIKE :search_username" : "";
	$extraSQL .= ( ! empty( $searchVars['search_email_address'] ) ) ? " AND email_address LIKE :search_email_address" : "";
	$extraSQL .= ( ! empty( $searchVars['search_first_name'] ) ) ? " AND first_name LIKE :search_first_name" : "";
	$extraSQL .= ( ! empty( $searchVars['search_last_name'] ) ) ? " AND last_name LIKE :search_last_name" : "";

	$result = $ftsdb->select( USERSDBTABLEPREFIX . "users",
		$extraSQL . " ORDER BY signup_date DESC",
		[
			":search_username"      => '%' . $searchVars['search_username'] . '%',
			":search_email_address" => '%' . $searchVars['search_email_address'] . '%',
			":search_first_name"    => '%' . $searchVars['search_first_name'] . '%',
			":search_last_name"     => '%' . $searchVars['search_last_name'] . '%',
		] );

	// Prep our table columns
	$columns      = apply_filters( 'table_users_columns', $tableColumns['table_users'] );
	$numOfColumns = count( $columns );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "usersTable" );

	// Create table title
	$table->addNewRow( [ [ 'data' => "Current Users (" . count( $result ) . ")", "colspan" => $numOfColumns ] ], '', 'title1', 'thead' );

	// Create column headers	
	$table->addNewRow( $table->generateTableColumns( $columns ), '', 'title2', 'thead' );

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( [ [ 'data' => "There are no users in the system.", "colspan" => $numOfColumns ] ], "usersTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$rowData = [];

			foreach ( $columns as $column_name => $column_display_name ) {
				switch ( $column_name ) {
					case 'username':
						$rowData[] = [ 'data' => $row['username'] ];
						break;
					case 'email_address':
						$rowData[] = [ 'data' => $row['email_address'] ];
						break;
					case 'full_name':
						$rowData[] = [ 'data' => $row['first_name'] . ' ' . $row['last_name'] ];
						break;
					case 'signup_date':
						$rowData[] = [ 'data' => $row['signup_date'] ];
						break;
					case 'user_level':
						$rowData[] = [ 'data' => returnUserlevelText( $row['user_level'] ) ];
						break;
					case 'final':
						$rowData[] = [
							'data'  => '<span class="btn-group"><a href="' . $menuvar['USERS'] . '&amp;action=edituser&amp;id=' . $row['id'] . '"  class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ' . createDeleteLinkWithImage( $row['id'], $row['id'] . '_row', 'users', 'user' ) . '</span>',
							'class' => 'center',
						];
						break;
					default:
						$rowData[] = apply_filters( 'table_users_custom_column', '', $column_name, $row['id'] );
				}
			}
			$table->addNewRow( $rowData, $row['id'] . '_row', '' );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"usersTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// users table
//=================================================
function returnUsersTableJQuery() {
	$JQueryReadyScripts = "
			$('#usersTable').tablesorter({ widgets: ['zebra'], headers: { 5: { sorter: false } } });";

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new users
//=================================================
function printNewUserForm() {
	global $menuvar, $mbp_config;

	$formFields = apply_filters( 'form_fields_users_new',
		[
			'first_name'    => [
				'text'  => 'First Name',
				'type'  => 'text',
				'class' => 'required',
			],
			'last_name'     => [
				'text'  => 'Last Name',
				'type'  => 'text',
				'class' => 'required',
			],
			'email_address' => [
				'text'  => 'Email Address',
				'type'  => 'text',
				'class' => 'required',
			],
			'username'      => [
				'text'  => 'Username',
				'type'  => 'text',
				'class' => 'required',
			],
			'password'      => [
				'text'         => 'Password',
				'type'         => 'password',
				'class'        => 'required showStrength',
				'appendButton' => '<button class="btn btn-info generatePasswordLink" type="button"><span class="glyphicon glyphicon-repeat"></span> Generate</button>',
				'help_block'   => '<span class="pwstrength_viewport_progress">&nbsp;</span>',
				'formGroupID'  => 'pwd-container',
			],
			'password2'     => [
				'text'  => 'Confirm Password',
				'type'  => 'password',
				'class' => 'required',
			],
			'company'       => [
				'text' => 'Company',
				'type' => 'text',
			],
			'title'         => [
				'text' => 'Title',
				'type' => 'text',
			],
			'website'       => [
				'text' => 'Website',
				'type' => 'text',
			],
			'phone_number'  => [
				'text' => 'Phone Number',
				'type' => 'text',
			],
			'facebook'      => [
				'text' => 'Facebook',
				'type' => 'text',
			],
			'twitter'       => [
				'text' => 'Twitter',
				'type' => 'text',
			],
			'google_plus'   => [
				'text' => 'Google Plus',
				'type' => 'text',
			],
			'pinterest'     => [
				'text' => 'Pinterest',
				'type' => 'text',
			],
			'instagram'     => [
				'text' => 'Instagram',
				'type' => 'text',
			],
			'linkedin'      => [
				'text' => 'Linkedin',
				'type' => 'text',
			],
			'user_level'    => [
				'text'    => 'User Level',
				'type'    => 'select',
				'options' => getDropdownArray( 'userlevel' ),
				'class'   => 'required',
			],
			'send_msg'      => [
				'text'          => 'Send New Account Email?',
				'type'          => 'toggle',
				'data_on_text'  => 'YES',
				'data_off_text' => 'NO',
				'value'         => '1',
				'currentValue'  => intval( get_config_value( 'ftsmbp_enable_account_creation_alert' ) ),
			],
		] );

	return makeForm( 'newUser', il( $menuvar['USERS'] ), 'New User', 'Create User', $formFields, [], 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new user form
//=================================================
function returnNewUserFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$extraValidationStuff = '
		rules: {
			password2: {
				equalTo: "#password"
			}
		},';
	$table                = ( $reprintTable == 0 ) ? '' : 'usersTable';

	return makeFormJQuery( 'newUser', SITE_URL . "/ajax.php?action=createUser&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification, $table, 'user', $extraValidationStuff );
}

//=================================================
// Create a form to edit users
//=================================================
function printEditUserForm( $userID ) {
	global $ftsdb, $menuvar, $mbp_config;

	if ( canAccessUser( $userID ) ) {
		$result = $ftsdb->select( USERSDBTABLEPREFIX . "users",
			"id = :id LIMIT 1",
			[
				":id" => $userID,
			] );

		if ( $result && count( $result ) == 0 ) {
			$page_content = "<span class=\"center\">There was an error while accessing the user's details you are trying to update. You are being redirected to the main page.</span>
							<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "\">";
		} else {
			$row = $result[0];

			$formFields = apply_filters( 'form_fields_users_edit',
				[
					'first_name'    => [
						'text'  => 'First Name',
						'type'  => 'text',
						'class' => 'required',
					],
					'last_name'     => [
						'text'  => 'Last Name',
						'type'  => 'text',
						'class' => 'required',
					],
					'email_address' => [
						'text'  => 'Email Address',
						'type'  => 'text',
						'class' => 'required',
					],
					'username'      => [
						'text'  => 'Username',
						'type'  => 'text',
						'class' => 'required',
					],
					'password'      => [
						'text'         => 'Password',
						'type'         => 'password',
						'appendButton' => '<button class="btn btn-info generatePasswordLink" type="button"><span class="glyphicon glyphicon-repeat"></span> Generate</button>',
						'class'        => 'showStrength',
						'help_block'   => '<span class="pwstrength_viewport_progress">&nbsp;</span>',
						'formGroupID'  => 'pwd-container',
					],
					'password2'     => [
						'text' => 'Confirm Password',
						'type' => 'password',
					],
					'company'       => [
						'text' => 'Company',
						'type' => 'text',
					],
					'title'         => [
						'text' => 'Title',
						'type' => 'text',
					],
					'website'       => [
						'text' => 'Website',
						'type' => 'text',
					],
					'phone_number'  => [
						'text' => 'Phone Number',
						'type' => 'text',
					],
					'facebook'      => [
						'text' => 'Facebook',
						'type' => 'text',
					],
					'twitter'       => [
						'text' => 'Twitter',
						'type' => 'text',
					],
					'google_plus'   => [
						'text' => 'Google Plus',
						'type' => 'text',
					],
					'pinterest'     => [
						'text' => 'Pinterest',
						'type' => 'text',
					],
					'instagram'     => [
						'text' => 'Instagram',
						'type' => 'text',
					],
					'linkedin'      => [
						'text' => 'Linkedin',
						'type' => 'text',
					],
					'user_level'    => [
						'text'    => 'User Level',
						'type'    => 'select',
						'options' => getDropdownArray( 'userlevel' ),
						'class'   => 'required',
					],
					'send_msg'      => [
						'text'          => 'Send Password Updated Email?',
						'type'          => 'toggle',
						'data_on_text'  => 'YES',
						'data_off_text' => 'NO',
						'value'         => '1',
						'currentValue'  => intval( get_config_value( 'ftsmbp_enable_account_updated_alert' ) ),
					],
				] );

			// Handle additional permission checks
			if ( ! user_access( 'users_edit_user_level' ) || ( $userID == $_SESSION['userid'] && $_SESSION['user_level'] != SYSTEM_ADMIN ) ) {
				unset( $formFields['user_level'] );
			}

			$content = makeForm( 'editUser', il( $menuvar['USERS'] ), '<i class="glyphicon glyphicon-user"></i> Edit User', 'Update User', $formFields, $row, 1 );

			$result = null;
		}
	} else {
		$content = return_error_alert( notAuthorizedNotice() );
	}

	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// edit user form
//=================================================
function returnEditUserFormJQuery( $userID ) {
	return makeFormJQuery( 'editUser', SITE_URL . "/ajax.php?action=editUser&id=" . $userID );
}

//=================================================
// Create a form to add new accounts
//=================================================
function printCreateAccountForm() {
	global $menuvar, $mbp_config;

	$formFields = apply_filters( 'form_fields_account_create',
		[
			'email_address' => [
				'text'        => 'Email Address',
				'placeholder' => 'Email Address',
				'type'        => 'text',
				'class'       => 'required input-lg',
				'showLabel'   => '0',
				'prepend'     => '<i class="glyphicons glyphicons-envelope"></i>',
			],
			'username'      => [
				'text'        => 'Username',
				'placeholder' => 'Username',
				'type'        => 'text',
				'class'       => 'required input-lg',
				'showLabel'   => '0',
				'prepend'     => '<i class="glyphicons glyphicons-user"></i>',
			],
			'password'      => [
				'text'        => 'Password',
				'placeholder' => 'Password',
				'type'        => 'password',
				'class'       => 'required input-lg showStrength',
				'showLabel'   => '0',
				'prepend'     => '<i class="glyphicons glyphicons-keys"></i>',
				'help_block'  => '<span class="pwstrength_viewport_progress">&nbsp;</span>',
				'formGroupID' => 'pwd-container',
			],
			'password2'     => [
				'text'        => 'Confirm Password',
				'placeholder' => 'Confirm Password',
				'type'        => 'password',
				'class'       => 'required input-lg',
				'showLabel'   => '0',
				'prepend'     => '<i class="glyphicons glyphicons-keys"></i>',
			],
		] );

	$extraFormOptions = [
		'extraPrimaryButtonClasses' => ' btn-lg btn-block',
	];

	return makeForm( 'createAccount', il( $menuvar['CREATEACCOUNT'] ), 'Create Account', 'Create Account', $formFields, [], 1, 0, '', $extraFormOptions );
}

//=================================================
// Returns the JQuery functions used to run the 
// new order form
//=================================================
function returnCreateAccountFormJQuery() {
	$extraValidationStuff = '
		rules: {
			password2: {
				equalTo: "#password"
			}
		},';

	return makeFormJQuery( 'createAccount', SITE_URL . '/ajax.php?action=createAccount', '', '', $extraValidationStuff );
}

//=================================================
// Create a form to reset our password
//=================================================
function printForgotPasswordForm() {
	global $menuvar, $mbp_config;

	$formFields = apply_filters( 'form_fields_account_forgot_password',
		[
			'email_address' => [
				'text'        => 'Email Address',
				'placeholder' => 'Email Address',
				'type'        => 'text',
				'class'       => 'input-lg',
				'showLabel'   => '0',
				'prepend'     => '<i class="glyphicons glyphicons-envelope"></i>',
			],
			[
				'value' => '<div class="text-center"><strong>-or-</strong></div>',
				'type'  => 'html',
			],
			'username'      => [
				'text'        => 'Username',
				'placeholder' => 'Username',
				'type'        => 'text',
				'class'       => 'input-lg',
				'showLabel'   => '0',
				'prepend'     => '<i class="glyphicons glyphicons-user"></i>',
			],
		] );

	$extraFormOptions = [
		'extraPrimaryButtonClasses' => ' btn-lg btn-block',
	];

	return makeForm( 'forgotPassword', il( $menuvar['FORGOTPASSWORD'] ), 'Forgot Password?', 'Reset Password', $formFields, [], 1, 0, '', $extraFormOptions );
}

//=================================================
// Returns the JQuery functions used to run the 
// form
//=================================================
function returnForgotPasswordFormJQuery() {
	$extraValidationStuff = "
		rules: {
			email_address: {
				required: function(element) {
					return $('#username').val() == '';
				}
			},
			username: {
				required: function(element) {	
					return $('#email_address').val() == '';	
				}	
			}
		},
		messages: {
			email_address: {
				required: 'Either an email address or username is required.'
			},
			username: {
				required: 'Either an email address or username is required.'
			}
		},";

	return makeFormJQuery( 'forgotPassword', SITE_URL . '/ajax.php?action=forgotPassword', '', '', $extraValidationStuff );
}

//=================================================
// Create a form to reset our password
//=================================================
function printResetPasswordForm( $token ) {
	global $menuvar, $mbp_config;

	$formFields = apply_filters( 'form_fields_account_reset_password',
		[
			'token'     => [
				'value' => $token,
				'type'  => 'hidden',
			],
			'password'  => [
				'text'        => 'Password',
				'placeholder' => 'Password',
				'type'        => 'password',
				'class'       => 'required input-lg showStrength',
				'showLabel'   => '0',
				'prepend'     => '<i class="glyphicons glyphicons-keys"></i>',
				'help_block'  => '<span class="pwstrength_viewport_progress">&nbsp;</span>',
				'formGroupID' => 'pwd-container',
			],
			'password2' => [
				'text'        => 'Confirm Password',
				'placeholder' => 'Confirm Password',
				'type'        => 'password',
				'class'       => 'required input-lg',
				'showLabel'   => '0',
				'prepend'     => '<i class="glyphicons glyphicons-keys"></i>',
			],
		] );

	$extraFormOptions = [
		'extraPrimaryButtonClasses' => ' btn-lg btn-block',
	];

	return makeForm( 'resetPassword', il( $menuvar['FORGOTPASSWORD'] . '&token=' . $token ), 'Reset Password', 'Update Password', $formFields, [], 1, 0, '', $extraFormOptions );
}

//=================================================
// Returns the JQuery functions used to run the 
// form
//=================================================
function returnResetPasswordFormJQuery() {
	$extraValidationStuff = '
		rules: {
			password2: {
				equalTo: "#password"
			}
		},';

	return makeFormJQuery( 'resetPassword', SITE_URL . '/ajax.php?action=resetPassword', '', '', $extraValidationStuff );
}

//=================================================
// Print the Users Table
//=================================================
function printSearchUsersForm( $searchVars ) {
	global $menuvar, $mbp_config;

	$formFields = apply_filters( 'form_fields_users_search',
		[
			'notice'               => [
				'value' => '<strong>Choose any or all of the following to search by.</strong>',
				'type'  => 'html',
			],
			'search_first_name'    => [
				'text' => 'First Name',
				'type' => 'text',
			],
			'search_last_name'     => [
				'text' => 'Last Name',
				'type' => 'text',
			],
			'search_email_address' => [
				'text' => 'Email Address',
				'type' => 'text',
			],
			'search_username'      => [
				'text' => 'Username',
				'type' => 'text',
			],
		] );

	return makeForm( 'searchUsers', il( $menuvar['USERS'] ), '<i class="glyphicon glyphicon-search"></i> Search Users', 'Search!', $formFields, $searchVars, 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new order form
//=================================================
function returnSearchUsersFormJQuery() {
	return makeFormJQuery( 'searchUsers', SITE_URL . "/ajax.php?action=searchUsers", '', '', '', '', 'updateMeUsers' );
}