<?php
/***************************************************************************
 *                               activate-account.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/

 
define('IN_LOGIN', 1); //let the header file know were here to stay Hey! Hey! Hey! 
$current_time = time();
$JQueryReadyScripts = '';

// Check for token
if ( !empty( $_GET['token'] ) ) {
	$actualToken = keepsafe( $_GET['token'] );
	
	// Check if it's in the DB
	$result = $ftsdb->select( USERSDBTABLEPREFIX . "users", 'token_activation = :token_activation', array(
		':token_activation' => $actualToken,
	) );
	if ($result && count($result) == 1) {
		$row = $result[0];
		$result = NULL;		
		
		// Remove token from DB and activate
		$result = $ftsdb->update( DBTABLEPREFIX . "users", array(
				"token_activation" => '',
				"active" => 1,
			), 
			"id = :id", array(
				":id" => $row['id']
			)
		);
		
		$page_content = return_alert( 'Account activated!' ) . 'You can now <a href="' . il( $menuvar['LOGIN'] ) . '">' . __('Login') . '</a>.';
	} else {
		$page_content = return_warning_alert( 'You must supply a valid activation token.' );
	}
} else { 
	$page_content = return_warning_alert( 'You must supply an activation token.' );
}
	
$page->setTemplateVar('Template', 'template-login.php'); 
$page->setTemplateVar('PageContent', $page_content);	
$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);