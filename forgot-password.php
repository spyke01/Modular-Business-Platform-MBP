<?php
/***************************************************************************
 *                               forgot-password.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

 
define('IN_LOGIN', 1); //let the header file know were here to stay Hey! Hey! Hey! 
$current_time = time();

if ( isset( $_GET['token'] ) ) {
	$actualToken = keepsafe( $_GET['token'] );
	
	// Check if it's in the DB
	$result = $ftsdb->select( USERSDBTABLEPREFIX . "users", 'token_password_reset = :token_password_reset', array(
		':token_password_reset' => $actualToken,
	) );
	if ($result && count($result) == 1) {
		$row = $result[0];
		$result = NULL;
		
		$page_content = printResetPasswordForm( $actualToken );
		$JQueryReadyScripts = returnResetPasswordFormJQuery();
	} else {
		$page_content = return_warning_alert( 'You must supply a valid password reset token.' );
	}
} else {
	$page_content = printForgotPasswordForm();
	$JQueryReadyScripts = returnForgotPasswordFormJQuery();
}
	
$page->setTemplateVar('Template', 'template-login.php'); 
$page->setTemplateVar('PageContent', $page_content);	
$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);