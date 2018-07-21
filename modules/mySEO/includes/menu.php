<?php
/***************************************************************************
 *                               menu.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/



$mySEOUserMenuItems  = array();
$mySEOAdminMenuItems = array(
	'SEOCLIENTS' => array(
		'text'        => 'SEO Clients',
		'link'        => 'index.php?p=module&prefix=mySEO&module_page=seo_clients',
		'icon'        => 'glyphicon glyphicon-globe',
		'permissions' => '0,2'
	),
	/*
	// Disabled temporarily so that we ca release the basic version and then come back ad tackle it
	'SEOTASKS' => array( 
		'text' => 'SEO Tasks', 
		'link' => 'index.php?p=module&prefix=mySEO&module_page=seoTasks', 
		'icon' => 'glyphicon glyphicon-tasks', 
		'permissions' => '0,2'		
	),
	*/
);
$mySEOMenus          = array(
	'VIEWSEOTASKSREPORT' => array(
		'text'        => 'View SEO Tasks Report',
		'link'        => 'index.php?p=admin&s=reports&action=viewreport&prefix=mySEO&report=mySEOTasks',
		'permissions' => '0,2'
	),
);