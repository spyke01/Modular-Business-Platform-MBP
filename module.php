<?php 
/***************************************************************************
 *                               module.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

//==================================================
// This page will handle all module pages, security will be handled at each modules level using the user_access() functuion
//==================================================		
callModuleHook($actual_prefix, 'showPage', array(
	'module_page' => $actual_module_page,
	'content' => $_REQUEST
));
callModuleHook('', 'changePageTemplate'); // Allow other modules to change the theme for these pages