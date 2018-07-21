<?php
/***************************************************************************
 *                               general.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=========================================================
// Returns the HTML code for our delete links
//=========================================================
function createInvoicePaymentDeleteLinkWithImage( $DBTableRowID, $rowName, $DBTableName, $typeName, $invoiceID ) {
	global $mbp_config;

	return "<a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteInvoicePaymentNotifier('" . $DBTableRowID . $DBTableName . "Spinner', '" . SITE_URL . "/ajax.php?action=deleteitem&table=" . $DBTableName . "&id=" . $DBTableRowID . "', '" . $typeName . "', '" . $rowName . "', '" . $invoiceID . "');\" class=\"btn btn-danger\"><i class=\"glyphicon glyphicon-remove\"></i><span id=\"" . $DBTableRowID . $DBTableName . "Spinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span></a>";
}

//=========================================================
// Our login action - Called via the perform_login action
//=========================================================
function clms_perform_login( $username, $encryptedPassword, $userID = '' ) {
	global $ftsdb, $mbp_config;

	// Only login if the main one has failed
	if ( empty( $_SESSION['STATUS'] ) ) {
		$sql    = ( empty( $userID ) ) ? "username = :username AND password = :password" : "id = :id AND password = :password";
		$result = $ftsdb->select( USERSDBTABLEPREFIX . "clients", $sql, array(
			":id"       => $userID,
			":username" => $username,
			":password" => $encryptedPassword,
		) );

		if ( $result && count( $result ) == 1 ) {
			$row = $result[0];

			$_SESSION['STATUS']          = 'true';
			$_SESSION['user_table']      = 'clients';
			$_SESSION['userid']          = $row['id'];
			$_SESSION['username']        = $row['username'];
			$_SESSION['epassword']       = $row['password'];
			$_SESSION['email_address']   = $row['email_address'];
			$_SESSION['first_name']      = $row['first_name'];
			$_SESSION['last_name']       = $row['last_name'];
			$_SESSION['full_name']       = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
			$_SESSION['website']         = $row['website'];
			$_SESSION['user_level']      = USER;
			$_SESSION['is_client']       = 1;
			$_SESSION['script_locale']   = rtrim( dirname( $_SERVER['PHP_SELF'] ), '/\\' );
			$_SESSION['canUploadImages'] = user_access( 'tinymce_upload_images_access' );

			// Trigger any additional actions needed during login
			do_action( 'perform_login_items_for_user', $_SESSION['userid'], 'clients' );

			$result = null;
		}
	}
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
function clms_perform_impersonation( $impersonationID = '' ) {
	global $ftsdb, $mbp_config;

	// Set up our vars
	$myImpersonationPrefix = 'clients';
	$impersonationIDParts  = explode( '_', $impersonationID );
	$userID                = array_pop( $impersonationIDParts );
	$impersonationPrefix   = implode( '_', $impersonationIDParts );

	// Make sure this is ours to handle	
	if ( $impersonationPrefix == $myImpersonationPrefix ) {

		// Does the user exist?
		$result = $ftsdb->select( USERSDBTABLEPREFIX . "clients", "id = :id", array(
			":id" => $userID,
		) );
		if ( $result && count( $result ) == 1 ) {
			$row = $result[0];

			// Are we authorized to touch this user?
			if ( user_access( return_impersonate_access_level_name( $row['user_level'] ) ) || $impersonationID == $_SESSION['actual_user_impersonate_id'] ) {
				// Handle the impersonation

				// Track our old data
				$_SESSION['actual_userid']              = $_SESSION['userid'];
				$_SESSION['actual_username']            = $_SESSION['username'];
				$_SESSION['actual_user_table']          = $_SESSION['user_table'];
				$_SESSION['actual_user_impersonate_id'] = $_SESSION['actual_user_table'] . '_' . $_SESSION['actual_userid'];

				// Track our new data
				$_SESSION['STATUS']        = 'true';
				$_SESSION['user_table']    = 'clients';
				$_SESSION['userid']        = $row['id'];
				$_SESSION['username']      = $row['username'];
				$_SESSION['epassword']     = $row['password'];
				$_SESSION['email_address'] = $row['email_address'];
				$_SESSION['first_name']    = $row['first_name'];
				$_SESSION['last_name']     = $row['last_name'];
				$_SESSION['full_name']     = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
				$_SESSION['website']       = $row['website'];
				$_SESSION['user_level']    = USER;
				$_SESSION['is_client']     = 1;
				//$_SESSION['script_locale'] = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				$_SESSION['canUploadImages'] = user_access( 'tinymce_upload_images_access' );

				// Trigger any additional actions needed during login
				do_action( 'perform_login_items_for_user', $_SESSION['userid'], 'clients' );
			}

			$result = null;
		}
	}
}