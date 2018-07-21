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



$ttsUserMenuItems  = array(
	'MYTICKETS' => array(
		'text'        => 'My Tickets',
		'link'        => 'index.php?p=module&prefix=TTS&module_page=mytickets',
		'icon'        => 'glyphicons glyphicons-flag',
		'permissions' => '0,2,5,6'
	),
);
$ttsAdminMenuItems = array(
	'TICKETS' => array(
		'text'        => 'Tickets',
		'link'        => 'index.php?p=module&prefix=TTS&module_page=tickets',
		'icon'        => 'glyphicons glyphicons-flag',
		'permissions' => '2'
	),
);
$ttsMenus          = array(
	'VIEWTICKET' => array(
		'text'        => 'Downloads',
		'link'        => 'index.php?p=module&prefix=TTS&module_page=viewticket',
		'permissions' => '0,2,5,6'
	),
);