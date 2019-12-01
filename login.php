<?php
/***************************************************************************
 *                               login.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


define( 'IN_LOGIN', 1 ); //let the header file know were here to stay Hey! Hey! Hey!
$current_time              = time();
$urls_login_log_in_success = apply_filters( 'urls_login_log_in_success', SITE_URL . '/' . $menuvar['LOGIN'] );
$urls_login_logged_in      = apply_filters( 'urls_login_logged_in', il( $menuvar['HOME'] ) );

//========================================
// Login Function for registering session
//========================================
if ( isset( $_POST['password'] ) ) {
	// Convert to simple variables
	$username = $_POST['username'];
	$password = $_POST['password'];

	if ( ( ! $username ) || ( ! $password ) ) {
		echo return_error_alert( 'Please enter ALL of the information!' ) . '<br />';
		exit();
	}

	// strip away any dangerous tags
	$username = keepsafe( $username );
	$password = keepsafe( $password );

	// Convert password to md5 hash
	$password = md5( $password );

	// Call our perform login action
	do_action( 'perform_login', $username, $password, '' );

	if ( isset( $_SESSION['STATUS'] ) && $_SESSION['STATUS'] == 'true' ) {
		// Create a login cookie
		$cookiename = $mbp_config['ftsmbp_cookie_name'];
		setcookie( $cookiename, $_SESSION['userid'] . "-" . $_SESSION['epassword'], time() + 2592000, '/' ); //set cookie for 1 month

		// Log the login :)
		addLogEvent( [
			'type'     => LOG_TYPE_LOGIN,
			'message'  => 'Login: ' . $username,
			'assoc_id' => $_SESSION['userid'],
		] );

		header( "Location: " . $urls_login_log_in_success );
		$page_content = return_success_alert( 'You are now logged in as ' . $_SESSION['username'] . '.' );
		$page_content .= '<br /><a href="' . il( $menuvar['LOGOUT'] ) . '">' . __( 'Logout' ) . '</a>';
	} else {
		$page_content = return_error_alert( 'You could not be logged in! Either the username and password do not match or you have not validated your membership!<br />Please try again!' );
		$page_content .= '<br /><a href="' . il( $menuvar['LOGIN'] ) . '">' . __( 'Return to Login Form' ) . '</a>';

		// Log the failure
		addLogEvent( [
			'type'    => LOG_TYPE_LOGIN_FAIL,
			'message' => 'Failed Login: ' . $username,
		] );
	}

	unset( $_POST['password'] );
}
//========================================
// If we got here check and see if they
// are logged in, if not print login page
//========================================
else {
	if ( isset( $_SESSION['username'] ) ) {
		$page_content = return_success_alert( 'You are logged in as ' . $_SESSION['username'] . ', and are being redirected to the main page.' );
		$page_content .= '
			<br /><a href="' . il( $menuvar['LOGOUT'] ) . '">' . __( 'Logout' ) . '</a>
			<meta http-equiv="refresh" content="1;url=' . $urls_login_logged_in . '">';
	} else {
		$formFields = apply_filters( 'form_fields_login',
			[
				'username' => [
					'text'        => 'Username',
					'placeholder' => 'Username',
					'type'        => 'text',
					'class'       => 'required input-lg',
					'size'        => '20',
					'maxlength'   => '40',
					'showLabel'   => '0',
					'prepend'     => '<i class="glyphicons glyphicons-user"></i>',
				],
				'password' => [
					'text'        => 'Password',
					'placeholder' => 'Password',
					'type'        => 'password',
					'class'       => 'required input-lg',
					'size'        => '20',
					'maxlength'   => '25',
					'showLabel'   => '0',
					'prepend'     => '<i class="glyphicons glyphicons-keys"></i>',
				],
			] );

		$extraFormOptions = [
			'extraPrimaryButtonClasses' => ' btn-lg btn-block',
		];

		if ( $mbp_config['ftsmbp_enable_public_account_creation'] ) {
			$extraFormOptions['extraButtons'] = ' <a href="' . il( $menuvar['CREATEACCOUNT'] ) . '" class="btn btn-success btn-lg btn-block">' . __( 'Create Account' ) . '</a>';
		}

		$extraFormOptions['extraButtons'] .= ' <a href="' . il( $menuvar['FORGOTPASSWORD'] ) . '" class="btn btn-warning btn-lg btn-block">' . __( 'Forgot Password?' ) . '</a>';

		$extraFormOptions = apply_filters( 'form_fields_login_extra_form_options', $extraFormOptions );

		$page_content = makeForm( 'login', $menuvar['LOGIN'], 'Login', 'Login', $formFields, [], 0, 0, '', $extraFormOptions );

		// Handle our JQuery needs
		$JQueryReadyScripts = makeFormJQuery( 'login' );
	}
}
$page->setTemplateVar( 'Template', 'template-login.php' );
$page->setTemplateVar( 'PageContent', $page_content );
$page->setTemplateVar( "JQueryReadyScript", $JQueryReadyScripts );