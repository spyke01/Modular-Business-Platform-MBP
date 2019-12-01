<?php 
/***************************************************************************
 *                               users.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


//==================================================
// Handle editing users
//==================================================	
if ( $actual_action == "edituser" && isset( $actual_id ) && user_access('users_edit') ) {
	// Add breadcrumb
	$page->addBreadCrumb("Edit User", "");
	
	$page_content = printEditUserForm($actual_id);
	
	// Handle our JQuery needs
	$JQueryReadyScripts = returnEditUserFormJQuery($actual_id);
} elseif ( user_access('users_access') ) {		
	//==================================================
	// Print out our users table
	//==================================================
	
	$page_content .= '
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicons glyphicons-group"></i> ' . __('Users') . '</h3>
							<div class="toolbar">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#currentUsers" data-toggle="tab"><span>' . __('Current Users') . '</span></a></li>
									' . ((user_access('users_create')) ? '<li><a href="#addAUser" data-toggle="tab"><span>' . __('Add a User') . '</span></a></li>' : '') . '
								</ul>
							</div>
						</div>
						<div class="tab-content">
							<div id="currentUsers" class="tab-pane active">
								' .printSearchUsersForm($_POST) . '
								<div id="updateMeUsers">
									' . printUsersTable($_POST) . '
								</div>
							</div>
							' . ((user_access('users_create')) ? '
							<div id="addAUser" class="tab-pane">
								' . ( 
									( !canHaveMultipleUsers() && getUserCount() > 0 ) 
										? return_error_alert('Your license only allows for 1 user account. To add additional users you need to upgrade to a paid license. <a href="https://www.fasttracksites.com/product/license-renewal/">Click here to purchase a new license.</a>') 
										: printNewUserForm() 
								) . '
							</div>
							' : '') . '
						</div>
					</div>';
			
	// Handle our JQuery needs
	$JQueryReadyScripts = returnSearchUsersFormJQuery() . returnUsersTableJQuery() . returnNewUserFormJQuery(1);
} else {
	$page_content .= return_error_alert( notAuthorizedNotice() );
}

$page->setTemplateVar('PageContent', $page_content);
$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);