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



$cmsUserMenuItems  = array();
$cmsAdminMenuItems = array(
	'PAGES' => array(
		'text'        => 'Pages',
		'link'        => 'index.php?p=module&prefix=CMS&module_page=pages',
		'icon'        => 'glyphicons glyphicons-pen',
		'permissions' => '2'
	),
);
$cmsMenus          = array(
	'VIEWPAGE'         => array(
		'text'        => 'View Page',
		'link'        => 'index.php?p=module&prefix=CMS&module_page=viewPage',
		'permissions' => '-1,0,1,2,3,4,5,6,8'
	),
	'VIEWTESTIMONIALS' => array(
		'text'        => 'View Testimonials',
		'link'        => 'index.php?p=module&prefix=CMS&module_page=testimonials',
		'permissions' => '-1,0,1,2,3,4,5,6,8'
	)
);