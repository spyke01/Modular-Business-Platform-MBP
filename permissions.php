<?php 
/***************************************************************************
 *                               permissions.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

if ($_SESSION['user_level'] == SYSTEM_ADMIN) {	
	//==================================================
	// Print out our permissions table
	//==================================================
	
	$page_content .= '
				<div class="box tabbable">
					<div class="box-header">
						<h3><i class="glyphicon glyphicon-lock"></i> ' . __('Permissions') . '</h3>
						<div class="toolbar">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#permissions" data-toggle="tab"><span>' . __('Permissions') . '</span></a></li>
							</ul>
						</div>
					</div>
					<div class="tab-content">
						<div id="permissions" class="tab-pane active">
							' . printEditUserRolePermissionsForm() . '
						</div>
					</div>
				</div>';
			
	// Handle our JQuery needs
	$JQueryReadyScripts = returnEditUserRolePermissionsFormJQuery();
	
	$page->setTemplateVar('PageContent', $page_content);
	$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
} else {
	$page->setTemplateVar('PageContent', notAuthorizedNotice());
}