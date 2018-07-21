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



$sntsUserMenuItems  = array();
$sntsAdminMenuItems = array(
	'SERIALS' => array(
		'text'        => 'Serials',
		'link'        => 'index.php?p=module&prefix=SNTS&module_page=serials',
		'icon'        => 'glyphicons glyphicons-keys',
		'permissions' => '2'
	),
);
$sntsMenus          = array();