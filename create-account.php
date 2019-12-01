<?php
/***************************************************************************
 *                               create-account.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


define( 'IN_LOGIN', 1 ); //let the header file know were here to stay Hey! Hey! Hey!
$current_time = time();

if ( $mbp_config['ftsmbp_enable_public_account_creation'] ) {
	$page_content       = printCreateAccountForm();
	$JQueryReadyScripts = returnCreateAccountFormJQuery();
} else {
	$page_content       = return_warning_alert( 'Account creation is currently disabled.' );
	$JQueryReadyScripts = '';
}

$page->setTemplateVar( 'Template', 'template-login.php' );
$page->setTemplateVar( 'PageContent', $page_content );
$page->setTemplateVar( "JQueryReadyScript", $JQueryReadyScripts );