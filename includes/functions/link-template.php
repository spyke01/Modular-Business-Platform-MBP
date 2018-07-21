<?php 
/***************************************************************************
 *                               link-template.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=================================================
// Return the site URL
//=================================================
function site_url() {
	global $mbp_config;
	
	return $mbp_config['ftsmbp_site_url'];		
}