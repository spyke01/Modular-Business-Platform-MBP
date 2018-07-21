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



$trsUserMenuItems  = array(
	'MYTAGGEDREPORTS' => array(
		'text'        => 'My Reports',
		'link'        => 'index.php?p=module&prefix=TRS&module_page=mytaggedreports',
		'permissions' => '0,2,5,6'
	),
);
$trsAdminMenuItems = array(
	'GENERATETAGGEDREPORT' => array(
		'text'        => 'Generate Tagged Reports',
		'link'        => 'index.php?p=module&prefix=TRS&module_page=generatetaggedreports',
		'permissions' => '2'
	),
	'TAGGABLEREPORTS'      => array(
		'text'        => 'Taggable Reports',
		'link'        => 'index.php?p=module&prefix=TRS&module_page=taggablereports',
		'permissions' => '2'
	),
);
$trsMenus          = array(
	'VIEWTAGGEDREPORT' => array(
		'text'        => 'View Tagged Report',
		'link'        => 'index.php?p=module&prefix=TRS&module_page=viewtaggedreport',
		'permissions' => '0,2,5,6'
	),
);
?>