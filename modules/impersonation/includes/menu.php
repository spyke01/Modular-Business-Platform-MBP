<?php
/***************************************************************************
 *                               menu.php
 *                            -------------------
 *   begin                : Monday, December 20, 2016
 *   copyright            : (C) 2016 Paden Clayton
 *
 *
 ***************************************************************************/



$impersonationUserMenuItems  = array();
$impersonationAdminMenuItems = array();
$impersonationMenus          = array(
	'IMPERSONATE' => array(
		'text'        => 'Impersonate',
		'link'        => 'index.php?p=module&prefix=impersonation&module_page=impersonate',
		'permissions' => '2'
	),
);