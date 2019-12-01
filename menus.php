<?php
/***************************************************************************
 *                               menus.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *
 *
 ***************************************************************************/


 
if (user_access('menus_access')) {	
	//==================================================
	// Handle editing of menu items
	//==================================================	
	if ($actual_action == "editmenuitem" && isset($actual_id)) {
		// Add breadcrumb
		$page->addBreadCrumb("Edit Menu Item", "");
		
		$page_content .= '
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicon glyphicon-cog"></i> ' . __('Edit Menu Item') . '</h3>
						</div>
						<div class="box-content">
							' . printEditMenuItemForm($actual_id) . '
						</div>
					</div>';
		
		// Handle our JQuery needs
		$JQueryReadyScripts = returnEditMenuItemFormJQuery($actual_id);
	} else {	
		//==================================================
		// Print out our categories table
		//==================================================
		$page_content .= '
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicon glyphicon-list"></i> ' . __('Menus') . '</h3>
								<div class="toolbar">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#currentMenus" data-toggle="tab"><span>' . __('Current Menus') . '</span></a></li>
										<li><a href="#createANewMenuItem" data-toggle="tab"><span>' . __('Create a New Menu Items') . '</span></a></li>
										<li><a href="#createANewMenu" data-toggle="tab"><span>' . __('Create a New Menu') . '</span></a></li>
									</ul>
								</div>
							</div>
							<div class="tab-content">
								<div id="currentMenus" class="tab-pane active">
									<h1>' . __('Menus') . '</h1>
									' . __('Below are a list of menus that you can customize. Click and drag to reorder the menu items.') . '
									<div id="updateMeMenus">
										' . printMenusTable() . '
									</div>
								</div>
								<div id="createANewMenuItem" class="tab-pane">
									' . printNewMenuItemForm() . '
								</div>
								<div id="createANewMenu" class="tab-pane">
									' . printNewMenuForm() . '
								</div>
							</div>
						</div>';
				
				// Handle our JQuery needs
				$JQueryReadyScripts = returnMenusTableJQuery() . returnNewMenuItemFormJQuery(1) . returnNewMenuFormJQuery(1);
	}
	
	$page->setTemplateVar('PageContent', $page_content);
	$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
} else {
	$page->setTemplateVar('PageContent', notAuthorizedNotice());
}