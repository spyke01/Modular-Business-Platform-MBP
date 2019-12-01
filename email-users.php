<?php
/***************************************************************************
 *                               email-users.php
 *                            -------------------
 *   begin                : Tuseday, July 10, 2014
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/

if ( user_access( 'email_users_access' ) ) {
	//==================================================
	// Handle editing email tempaltes
	//==================================================	
	if ( $actual_action == "editEmailTemplate" && isset( $actual_id ) && user_access( 'email_templates_edit' ) ) {
		// Add breadcrumb
		$page->addBreadCrumb( 'Edit Email Template', '' );

		$page_content = '
						<div class="row">
							<div class="col-xs-12 col-md-8">' . printEditEmailTemplateForm( $actual_id ) . '</div>
							<div class="col-xs-12 col-md-4">
								<div class="box">
									<div class="box-header"><h3>' . __( 'Available Email Template Tags' ) . '</h3></div>
									<div class="box-content">' . displayEmailTemplateTags() . '</div>
								</div>
							</div>
						</div>';

		// Handle our JQuery needs
		$JQueryReadyScripts = returnEditEmailTemplateFormJQuery( $actual_id );
	} else {
		//==================================================
		// Print out our users table
		//==================================================
		$page_content .= '
			<div class="box tabbable">
				<div class="box-header">
					<h3><i class="glyphicon glyphicon-envelope"></i> ' . __( 'Email Users' ) . '</h3>
					<div class="toolbar">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#emailUsers" data-toggle="tab"><span>Email Users</span></a></li>
							' . ( ( user_access( 'email_templates_access' ) ) ? '<li><a href="#currentEmailTemplates" data-toggle="tab"><span>Email Templates</span></a></li>' : '' ) . '
							' . ( ( user_access( 'email_templates_create' ) ) ? '<li><a href="#createANewEmailTemplate" data-toggle="tab"><span>Create a New Email Template</span></a></li>' : '' ) . '
						</ul>
					</div>
				</div>
				<div class="tab-content">
					<div id="emailUsers" class="tab-pane active">
						' . printSendEmailTemplateForm() . '
					</div>
					' . ( ( user_access( 'email_templates_access' ) ) ? '
					<div id="currentEmailTemplates" class="tab-pane">
						' . printEmailTemplatesTable() . '
					</div>
					' : '' ) . '
					' . ( ( user_access( 'email_templates_create' ) ) ? '
					<div id="createANewEmailTemplate" class="tab-pane">
						<div class="row">
							<div class="col-xs-12 col-md-8">' . printNewEmailTemplateForm() . '</div>
							<div class="col-xs-12 col-md-4">
								<div class="box">
									<div class="box-header"><h3>' . __( 'Available Email Template Tags' ) . '</h3></div>
									<div class="box-content">' . displayEmailTemplateTags() . '</div>
								</div>
							</div>
						</div>
					</div>
					' : '' ) . '
				</div>
			</div>';

		// Handle our JQuery needs
		$JQueryReadyScripts = returnSendEmailTemplateFormJQuery();
		if ( user_access( 'email_templates_access' ) ) {
			$JQueryReadyScripts .= returnEmailTemplatesTableJQuery();
		}
		if ( user_access( 'email_templates_create' ) ) {
			$JQueryReadyScripts .= returnNewEmailTemplateFormJQuery( 1 );
		}
	}

	$page->setTemplateVar( 'PageContent', $page_content );
	$page->setTemplateVar( "JQueryReadyScript", $JQueryReadyScripts );
} else {
	$page->setTemplateVar( 'PageContent', notAuthorizedNotice() );
}