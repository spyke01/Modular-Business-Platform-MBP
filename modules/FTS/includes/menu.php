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



$ftsUserMenuItems  = array();
$ftsAdminMenuItems = array(
	'CALLHOME' => array(
		'text'        => 'Call Home',
		'link'        => 'index.php?p=module&prefix=FTS&module_page=callhome',
		'icon'        => 'glyphicons glyphicons-phone-alt',
		'permissions' => ''
	),
	'VERSIONS' => array(
		'text'        => 'Versions',
		'link'        => 'index.php?p=module&prefix=FTS&module_page=versions',
		'icon'        => 'glyphicon glyphicon-cog',
		'permissions' => ''
	),
);
$ftsMenus          = array();