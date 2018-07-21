<?php 
/***************************************************************************
 *                               categories.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


 
if ($_SESSION['user_level'] == SYSTEM_ADMIN || $_SESSION['user_level'] == CLIENT_ADMIN) {	
	//==================================================
	// Handle editing of categories
	//==================================================	
	if ($actual_action == "editcategory" && isset($actual_id)) {
		// Add breadcrumb
		$page->addBreadCrumb("Edit Category", "");
		
		$page_content .= '
					<div class="box tabbable">
						<div class="box-header">
							<h3><i class="glyphicon glyphicon-list"></i> ' . __('Edit Category') . '</h3>
						</div>
						<div class="box-content">
							' . printEditCategoryForm($actual_id) . '
						</div>
					</div>';
		
		// Handle our JQuery needs
		$JQueryReadyScripts = returnEditCategoryFormJQuery($actual_id);
	} else {	
		//==================================================
		// Print out our categories table
		//==================================================
		$page_content .= '
						<div class="box tabbable">
							<div class="box-header">
								<h3><i class="glyphicon glyphicon-random"></i> ' . __('Categories') . '</h3>
								<div class="toolbar">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#currentCategories" data-toggle="tab"><span>' . __('Current Categories') . '</span></a></li>
										<li><a href="#createANewCategory" data-toggle="tab"><span>' . __('Create a New Category') . '</span></a></li>
									</ul>
								</div>
							</div>
							<div class="tab-content">
								<div id="currentCategories" class="tab-pane active">
									<div id="updateMeCategories">
										' . printCategoriesTable() . '
									</div>
								</div>
								<div id="createANewCategory" class="tab-pane">
									' . printNewCategoryForm() . '
								</div>
							</div>
						</div>';
				
				// Handle our JQuery needs
				$JQueryReadyScripts = returnCategoriesTableJQuery() . returnNewCategoryFormJQuery(1);
	}
	
	$page->setTemplateVar('PageContent', $page_content);
	$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
} else {
	$page->setTemplateVar('PageContent', notAuthorizedNotice());
}